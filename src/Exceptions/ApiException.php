<?php

namespace Zdearo\Meli\Exceptions;

use Exception;

/**
 * Exception thrown when an API request fails.
 */
class ApiException extends Exception 
{
    /**
     * The HTTP status code.
     *
     * @var int
     */
    protected int $statusCode;

    /**
     * Create a new API exception instance.
     *
     * @param string $message The exception message
     * @param int $statusCode The HTTP status code
     */
    public function __construct(string $message, int $statusCode = 500) 
    {
        parent::__construct($message, $statusCode);
        $this->statusCode = $statusCode;
    }

    /**
     * Get the HTTP status code.
     *
     * @return int
     */
    public function getStatusCode(): int 
    {
        return $this->statusCode;
    }
}
