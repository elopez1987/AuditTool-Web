<?php

declare(strict_types=1);

namespace App\Models\Security;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Laravel\Passport\Client;

final class LocalPassportClient extends Client
{
    public function initializeHasUniqueStringIds(): void
    {
        $this->usesUniqueIds = false;
    }

    protected function grantTypes(): Attribute
    {
        return Attribute::get(static fn (): array => ['password', 'refresh_token']);
    }
}
