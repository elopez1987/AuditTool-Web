<?php

declare(strict_types=1);

namespace App\Exceptions;

use RuntimeException;

final class LocalAuthException extends RuntimeException
{
    public function __construct(
        string $message,
        public readonly int $status = 401,
        public readonly string $reason = 'authentication_failed',
        public readonly array $headers = [],
        public readonly ?int $userId = null,
        public readonly ?string $familyId = null,
    ) {
        parent::__construct($message);
    }
}
