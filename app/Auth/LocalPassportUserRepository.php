<?php

declare(strict_types=1);

namespace App\Auth;

use App\Models\Security\LocalAppUser;
use App\Services\Security\LocalAccountService;
use Laravel\Passport\Bridge\User;
use League\OAuth2\Server\Entities\ClientEntityInterface;
use League\OAuth2\Server\Entities\UserEntityInterface;
use League\OAuth2\Server\Repositories\UserRepositoryInterface;

final class LocalPassportUserRepository implements UserRepositoryInterface
{
    public ?LocalAppUser $user = null;

    public function __construct(private readonly LocalAccountService $accounts) {}

    public function getUserEntityByUserCredentials(
        string $username,
        #[\SensitiveParameter] string $password,
        string $grantType,
        ClientEntityInterface $clientEntity,
    ): ?UserEntityInterface {
        $this->user = $this->accounts->authenticate($username, $password);

        return new User((string) $this->user->id);
    }
}
