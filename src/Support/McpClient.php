<?php

namespace Zdearo\Meli\Support;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class McpClient
{
    protected const MCP_ENDPOINT = 'https://mcp.mercadolibre.com/mcp';

    protected const JSON_RPC_VERSION = '2.0';

    protected ?string $sessionId = null;

    protected bool $initialized = false;

    public function __construct(
        protected string $accessToken
    ) {}

    /**
     * Call an MCP tool by name with the given arguments.
     *
     * @param  string  $toolName  The MCP tool name
     * @param  array  $arguments  The tool arguments
     * @return array The tool call result
     *
     * @throws \Illuminate\Http\Client\RequestException If the request fails
     * @throws \RuntimeException If the MCP response contains an error
     */
    public function callTool(string $toolName, array $arguments = []): array
    {
        $this->ensureInitialized();

        $response = $this->request()
            ->post(static::MCP_ENDPOINT, [
                'jsonrpc' => static::JSON_RPC_VERSION,
                'id' => $this->generateRequestId(),
                'method' => 'tools/call',
                'params' => [
                    'name' => $toolName,
                    'arguments' => $arguments,
                ],
            ]);

        $data = $response->json();

        if (isset($data['error'])) {
            throw new \RuntimeException(
                "MCP error [{$data['error']['code']}]: {$data['error']['message']}"
            );
        }

        return $data['result'] ?? [];
    }

    /**
     * List all available tools on the MCP server.
     *
     * @return array The available tools
     *
     * @throws \Illuminate\Http\Client\RequestException If the request fails
     */
    public function listTools(): array
    {
        $this->ensureInitialized();

        $response = $this->request()
            ->post(static::MCP_ENDPOINT, [
                'jsonrpc' => static::JSON_RPC_VERSION,
                'id' => $this->generateRequestId(),
                'method' => 'tools/list',
            ]);

        $data = $response->json();

        return $data['result']['tools'] ?? [];
    }

    /**
     * Initialize the MCP session if not already initialized.
     */
    protected function ensureInitialized(): void
    {
        if ($this->initialized) {
            return;
        }

        $response = $this->request()
            ->post(static::MCP_ENDPOINT, [
                'jsonrpc' => static::JSON_RPC_VERSION,
                'id' => $this->generateRequestId(),
                'method' => 'initialize',
                'params' => [
                    'protocolVersion' => '2025-03-26',
                    'capabilities' => new \stdClass,
                    'clientInfo' => [
                        'name' => 'meli-php',
                        'version' => '1.0.0',
                    ],
                ],
            ]);

        $sessionId = $response->header('mcp-session-id');

        if ($sessionId) {
            $this->sessionId = $sessionId;
        }

        $this->initialized = true;
    }

    /**
     * Get a configured HTTP request instance.
     */
    protected function request(): PendingRequest
    {
        $request = Http::acceptJson()
            ->contentType('application/json')
            ->throw()
            ->withToken($this->accessToken);

        if ($this->sessionId) {
            $request->withHeaders(['mcp-session-id' => $this->sessionId]);
        }

        return $request;
    }

    /**
     * Generate a unique request ID for JSON-RPC.
     */
    protected function generateRequestId(): string
    {
        return uniqid('mcp_', true);
    }
}
