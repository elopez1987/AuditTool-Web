<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Auth\LocalPassportUserRepository;
use App\Auth\LocalPasswordGrant;
use App\Auth\LocalRefreshTokenGrant;
use App\Exceptions\LocalAuthException;
use App\Models\Security\LocalAppUser;
use App\Repositories\Security\LocalPassportClientRepository;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Psr7\ServerRequest;
use Illuminate\Contracts\Config\Repository;
use Laravel\Passport\Bridge\RefreshTokenRepository;
use League\OAuth2\Server\Grant\AbstractGrant;

final class LocalPassportTokenService
{
    public function __construct(
        private readonly LocalPassportServerFactory $servers,
        private readonly LocalPassportClientRepository $clients,
        private readonly LocalAccountService $accounts,
        private readonly RefreshTokenRepository $refreshTokens,
        private readonly Repository $config,
    ) {}

    /**
     * @return array{user: LocalAppUser, auth: array<string, mixed>}
     */
    public function login(string $username, #[\SensitiveParameter] string $password): array
    {
        $users = new LocalPassportUserRepository($this->accounts);
        $auth = $this->exchange(new LocalPasswordGrant($users, $this->refreshTokens), [
            'grant_type' => 'password',
            'username' => $username,
            'password' => $password,
            'scope' => '',
        ]);

        return ['user' => $users->user, 'auth' => $auth];
    }

    /**
     * @return array{user: LocalAppUser, auth: array<string, mixed>}
     */
    public function refresh(#[\SensitiveParameter] string $refreshToken): array
    {
        $grant = new LocalRefreshTokenGrant($this->refreshTokens, $this->accounts);
        $auth = $this->exchange($grant, [
            'grant_type' => 'refresh_token',
            'refresh_token' => $refreshToken,
        ]);

        return ['user' => $grant->user, 'auth' => $auth];
    }

    /**
     * @param  array<string, string>  $parameters
     * @return array<string, mixed>
     */
    private function exchange(AbstractGrant $grant, #[\SensitiveParameter] array $parameters): array
    {
        $id = $this->config->get('local_auth.client_id');
        $client = is_string($id) || is_int($id) ? $this->clients->findActive($id) : null;

        if ($client === null || ! $client->password_client || $client->provider !== 'local-app-users') {
            throw new LocalAuthException('El servicio de autenticación local no está configurado.', 503);
        }

        $request = (new ServerRequest('POST', 'https://localhost/internal/local-auth'))->withParsedBody($parameters + [
            'client_id' => (string) $client->id,
            'client_secret' => hash('sha256', (string) $this->config->get('local_auth.token_app')),
        ]);
        $response = $this->servers->make($grant)->respondToAccessTokenRequest($request, new Response);

        return json_decode((string) $response->getBody(), true, 512, JSON_THROW_ON_ERROR);
    }
}
