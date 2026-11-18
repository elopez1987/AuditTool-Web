<?php

declare(strict_types=1);

namespace App\Models\Security;

use App\User;
use Laravel\Passport\Contracts\OAuthenticatable;

final class LocalAppUser extends User implements OAuthenticatable
{
    protected $table = 'users';

    public function guardName(): string
    {
        return 'web';
    }

    public function getMorphClass(): string
    {
        return (new User)->getMorphClass();
    }

    public function getProviderName(): string
    {
        return 'local-app-users';
    }
}
