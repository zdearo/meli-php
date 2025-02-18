<?php

namespace Zdearo\Meli\Exceptions;

use Exception;

class ApiException extends Exception {
    protected int $statusCode;

    public function __construct($message, int $statusCode = 500) {
        parent::__construct($message, $statusCode);
        $this->statusCode = $statusCode;
    }

    public function getStatusCode(): int {
        return $this->statusCode;
    }
}
