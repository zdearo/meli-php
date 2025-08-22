<?php

namespace Zdearo\Meli\Support;

use Illuminate\Http\Client\Response;
use Zdearo\Meli\Enums\HttpMethod;
use Zdearo\Meli\Facades\Meli;

class ApiRequest
{
    protected array $headers = [];

    protected array $query = [];

    protected array $body = [];

    public function __construct(
        protected HttpMethod $method = HttpMethod::GET,
        protected string $uri = ''
    ) {}

    public function send(): Response
    {
        return Meli::send($this);
    }

    public function withQuery(array $query): ApiRequest
    {
        $this->query = $query;

        return $this;
    }

    public function withHeaders(array $headers): ApiRequest
    {
        $this->headers = $headers;

        return $this;
    }

    public function withBody(array $body): ApiRequest
    {
        $this->body = $body;

        return $this;
    }

    public function getHeaders(): array
    {
        return $this->headers;
    }

    public function getQuery(): array
    {
        return $this->query;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getUri(): string
    {
        if (empty($this->query) || $this->method === HttpMethod::GET) {
            return $this->uri;
        }

        return $this->uri.'?'.http_build_query($this->query);
    }

    public function getMethod(): HttpMethod
    {
        return $this->method;
    }

    public static function __callStatic(string $method, array $arguments): static
    {
        $uri = $arguments[0] ?? '';

        try {
            $httpMethod = HttpMethod::from(strtolower($method));
        } catch (\ValueError $e) {
            throw new \InvalidArgumentException("Unsupported HTTP method: {$method}");
        }

        return new static($httpMethod, $uri);
    }
}
