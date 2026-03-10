<?php

namespace Zdearo\Meli\Support;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Zdearo\Meli\Enums\HttpMethod;
use Zdearo\Meli\Exceptions\MeliAuthException;
use Zdearo\Meli\Exceptions\MeliException;
use Zdearo\Meli\Exceptions\MeliForbiddenException;
use Zdearo\Meli\Exceptions\MeliNotFoundException;
use Zdearo\Meli\Exceptions\MeliRateLimitException;
use Zdearo\Meli\Exceptions\MeliServerException;
use Zdearo\Meli\Exceptions\MeliValidationException;
use Zdearo\Meli\Services\AuthService;
use Zdearo\Meli\Services\CategoryService;
use Zdearo\Meli\Services\DocumentationService;
use Zdearo\Meli\Services\NotificationService;
use Zdearo\Meli\Services\OrderService;
use Zdearo\Meli\Services\PaymentService;
use Zdearo\Meli\Services\ProductService;
use Zdearo\Meli\Services\QuestionService;
use Zdearo\Meli\Services\SearchItemService;
use Zdearo\Meli\Services\UserService;
use Zdearo\Meli\Services\VisitsService;

class MeliApiClient
{
    protected ?string $contextualToken = null;

    public static function getAuthUrl($state): string
    {
        $redirectUri = config('meli.redirect_uri');
        $clientId = config('meli.client_id');
        $authDomain = config('meli.auth_domain', 'mercadolibre.com.br');

        return "https://auth.{$authDomain}/authorization?response_type=code&client_id={$clientId}&redirect_uri={$redirectUri}&state={$state}";
    }

    public function send(ApiRequest $request): Response
    {
        try {
            return $this->getBaseRequest()
                ->withHeaders($request->getHeaders())
                ->{$request->getMethod()->value}(
                    $request->getUri(),
                    $request->getMethod() === HttpMethod::GET
                        ? $request->getQuery()
                        : $request->getBody()
                );
        } catch (RequestException $e) {
            throw $this->mapException($e->response, $e);
        }
    }

    public function auth(): AuthService
    {
        return app(AuthService::class);
    }

    public function products(): ProductService
    {
        return app(ProductService::class);
    }

    public function searchItem(): SearchItemService
    {
        return app(SearchItemService::class);
    }

    public function visits(): VisitsService
    {
        return app(VisitsService::class);
    }

    public function users(): UserService
    {
        return app(UserService::class);
    }

    public function categories(): CategoryService
    {
        return app(CategoryService::class);
    }

    public function orders(): OrderService
    {
        return app(OrderService::class);
    }

    public function questions(): QuestionService
    {
        return app(QuestionService::class);
    }

    public function notifications(): NotificationService
    {
        return app(NotificationService::class);
    }

    public function payments(): PaymentService
    {
        return app(PaymentService::class);
    }

    public function documentation(): DocumentationService
    {
        return app(DocumentationService::class);
    }

    public function withToken(string $token): static
    {
        $this->contextualToken = $token;

        return $this;
    }

    public function forConnection($connection): static
    {
        if (is_object($connection) && property_exists($connection, 'access_token')) {
            $this->contextualToken = $connection->access_token;
        } elseif (is_object($connection) && method_exists($connection, 'getAccessToken')) {
            $this->contextualToken = $connection->getAccessToken();
        } elseif (is_string($connection) || is_int($connection)) {
            $tokenResolver = config('meli.access_token_resolver');
            if (is_callable($tokenResolver)) {
                $this->contextualToken = call_user_func($tokenResolver, $connection);
            }
        }

        return $this;
    }

    protected function getBaseRequest(): PendingRequest
    {
        $request = Http::acceptJson()
            ->contentType('application/json')
            ->throw()
            ->baseUrl(config('meli.base_url'));

        $retryTimes = config('meli.retry.times', 0);

        if ($retryTimes > 0) {
            $retrySleepMs = config('meli.retry.sleep_ms', 500);

            $request->retry(
                times: $retryTimes,
                sleepMilliseconds: $retrySleepMs,
                when: function (\Exception $exception) {
                    if ($exception instanceof RequestException) {
                        return in_array($exception->response->status(), [429, 500, 502, 503, 504]);
                    }

                    return false;
                },
                throw: true,
            );
        }

        return $this->authorize($request);
    }

    protected function authorize(PendingRequest $request): PendingRequest
    {
        $token = $this->resolveAccessToken();

        if ($token) {
            return $request->withToken($token);
        }

        return $request;
    }

    public function resolveAccessToken(): ?string
    {
        if ($this->contextualToken) {
            return $this->contextualToken;
        }

        $tokenResolver = config('meli.access_token_resolver');

        if (is_callable($tokenResolver)) {
            return call_user_func($tokenResolver);
        }

        if (is_string($tokenResolver) && class_exists($tokenResolver)) {
            $resolver = app($tokenResolver);
            if (method_exists($resolver, 'resolve')) {
                return $resolver->resolve();
            }
        }

        return config('meli.api_token');
    }

    protected function mapException(Response $response, ?\Throwable $previous = null): MeliException
    {
        $status = $response->status();

        return match (true) {
            $status === 401 => MeliAuthException::fromResponse($response, $previous),
            $status === 403 => MeliForbiddenException::fromResponse($response, $previous),
            $status === 404 => MeliNotFoundException::fromResponse($response, $previous),
            $status === 429 => MeliRateLimitException::fromResponse($response, $previous),
            $status === 400 => MeliValidationException::fromResponse($response, $previous),
            $status >= 500 => MeliServerException::fromResponse($response, $previous),
            default => MeliException::fromResponse($response, $previous),
        };
    }
}
