<?php

namespace Zdearo\Meli\Services;

use Zdearo\Meli\Support\McpClient;

/**
 * Service for searching and reading Mercado Libre technical documentation
 * via the official MCP server.
 */
class DocumentationService
{
    public function __construct(
        protected McpClient $mcpClient
    ) {}

    /**
     * Search the Mercado Libre technical documentation.
     *
     * @param  string  $query  The search query (e.g., "items", "orders", "OAuth")
     * @param  string  $language  The language code (e.g., "pt_BR", "es", "en")
     * @param  string|null  $siteId  Optional site ID filter (e.g., "MLB", "MLA")
     * @param  int|null  $limit  Optional limit of results
     * @param  int|null  $offset  Optional offset for pagination
     * @return array The search results
     *
     * @throws \RuntimeException If the MCP call fails
     */
    public function search(
        string $query,
        string $language = 'pt_BR',
        ?string $siteId = null,
        ?int $limit = null,
        ?int $offset = null
    ): array {
        $arguments = [
            'query' => $query,
            'language' => $language,
        ];

        if ($siteId !== null) {
            $arguments['siteId'] = $siteId;
        }

        if ($limit !== null) {
            $arguments['limit'] = $limit;
        }

        if ($offset !== null) {
            $arguments['offset'] = $offset;
        }

        return $this->mcpClient->callTool('search_documentation', $arguments);
    }

    /**
     * Get the full content of a documentation page.
     *
     * @param  string  $path  The documentation page path (e.g., "/pt_br/product-search")
     * @param  string  $language  The language code (e.g., "pt_BR", "es", "en")
     * @param  string|null  $siteId  Optional site ID filter (e.g., "MLB", "MLA")
     * @return array The page content
     *
     * @throws \RuntimeException If the MCP call fails
     */
    public function getPage(
        string $path,
        string $language = 'pt_BR',
        ?string $siteId = null
    ): array {
        $arguments = [
            'path' => $path,
            'language' => $language,
        ];

        if ($siteId !== null) {
            $arguments['siteId'] = $siteId;
        }

        return $this->mcpClient->callTool('get_documentation_page', $arguments);
    }

    /**
     * List all available tools on the MCP server.
     *
     * @return array The available MCP tools
     */
    public function availableTools(): array
    {
        return $this->mcpClient->listTools();
    }
}
