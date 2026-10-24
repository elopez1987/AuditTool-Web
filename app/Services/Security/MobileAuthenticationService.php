<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Repositories\Security\MobileUserRepository;
use App\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\Collection;

final class MobileAuthenticationService
{
    public function __construct(
        private readonly MobileUserRepository $users,
        private readonly MobileAuthConfiguration $configuration,
    ) {}

    /**
     * Claims passed here have already been verified by the Auth0 SDK.
     *
     * @param  array<string, mixed>  $claims
     */
    public function resolveUser(array $claims): User
    {
        $subject = $claims['sub'] ?? null;
        $client = $claims['azp'] ?? $claims['client_id'] ?? null;
        $issued = $claims['iat'] ?? null;
        $expires = $claims['exp'] ?? null;
        $notBefore = $claims['nbf'] ?? null;
        $latest = time() + $this->configuration->leeway();

        if (! is_string($subject) || trim($subject) === '' || str_ends_with($subject, '@clients')
            || ($claims['gty'] ?? null) === 'client-credentials'
            || ($claims['gty'] ?? null) === 'client_credentials'
            || ! is_string($client) || ! in_array($client, $this->configuration->allowedClientIds(), true)
            || (isset($claims['azp'], $claims['client_id']) && $claims['azp'] !== $claims['client_id'])
            || ! is_int($issued) || $issued > $latest
            || ! is_int($expires) || $expires <= $issued
            || ($notBefore !== null && (! is_int($notBefore) || $notBefore > $latest))) {
            throw new AuthenticationException('Invalid mobile access token.', ['auth0-mobile']);
        }

        $scope = $claims['scope'] ?? '';
        $scopes = is_string($scope) ? preg_split('/\s+/', trim($scope)) : [];

        if (! in_array($this->configuration->requiredScope(), $scopes ?: [], true)) {
            throw new AuthorizationException('Mobile access is not permitted.');
        }

        $matches = $this->users->findBySubject($subject);
        $user = $matches->first();

        if ($matches->count() !== 1 || $user === null || $user->auth0 !== $subject
            || $user->statusid !== 'E' || $user->trashed()) {
            throw new AuthorizationException('Mobile access is not permitted.');
        }

        return $user;
    }

    /**
     * @return array{user: User, warehouse_access: Collection}
     */
    public function profile(User $user): array
    {
        return [
            'user' => $this->users->loadPermissions($user),
            'warehouse_access' => $user->username === null || trim($user->username) === ''
                ? new Collection
                : $this->users->warehouseAccess($user->username),
        ];
    }
}
