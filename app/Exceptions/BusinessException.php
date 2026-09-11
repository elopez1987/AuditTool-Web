<?php

namespace App\Exceptions;

use Exception;

class BusinessException extends Exception
{
    public function __construct(
        string $userMessage,
        private readonly ?string $internalDetail = null,
        int $code = 0,
        ?\Throwable $previous = null
    ) {
        parent::__construct($userMessage, $code, $previous);
    }

    public function internalDetail(): ?string
    {
        return $this->internalDetail;
    }
}
