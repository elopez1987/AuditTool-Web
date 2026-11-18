<?php

declare(strict_types=1);

namespace App\Repositories\Security;

use App\Models\Security\Logs\AuthenticationAuditLog;

final class LocalAuthenticationAuditRepository
{
    /** @param array<string, mixed> $attributes */
    public function record(array $attributes): void
    {
        $record = new AuthenticationAuditLog;
        $record->forceFill($attributes)->save();
    }
}
