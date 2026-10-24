<?php

declare(strict_types=1);

namespace App\Auth;

use App\Services\Security\MobileAuthenticationService;
use Auth0\Laravel\UserRepositoryAbstract;
use Auth0\Laravel\UserRepositoryContract;
use Illuminate\Contracts\Auth\Authenticatable;

final class MobileUserRepository extends UserRepositoryAbstract implements UserRepositoryContract
{
    public function __construct(
        private readonly MobileAuthenticationService $authentication,
    ) {}

    public function fromAccessToken(array $user): ?Authenticatable
    {
        return $this->authentication->resolveUser($user);
    }

    public function fromSession(array $user): ?Authenticatable
    {
        return null;
    }
}
