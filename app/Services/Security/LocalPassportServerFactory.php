<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Exceptions\LocalAuthException;
use App\Repositories\Security\LocalPassportClientRepository;
use DateInterval;
use DateTimeImmutable;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Hashing\Hasher;
use Illuminate\Database\DatabaseManager;
use Laravel\Passport\Bridge\AccessTokenRepository;
use Laravel\Passport\Bridge\ClientRepository;
use Laravel\Passport\Bridge\ScopeRepository;
use Laravel\Passport\Passport;
use League\OAuth2\Server\AuthorizationServer;
use League\OAuth2\Server\CryptKey;
use League\OAuth2\Server\Grant\AbstractGrant;

final class LocalPassportServerFactory
{
    public function __construct(
        private readonly LocalPassportClientRepository $clients,
        private readonly AccessTokenRepository $tokens,
        private readonly ScopeRepository $scopes,
        private readonly Repository $config,
        private readonly Encrypter $encrypter,
        private readonly Hasher $hasher,
        private readonly DatabaseManager $database,
    ) {}

    public function make(AbstractGrant $grant, ?DateTimeImmutable $sessionExpiresAt = null): AuthorizationServer
    {
        if ($this->database->connection($this->config->get('passport.connection')) !== $this->database->connection()) {
            throw new LocalAuthException('La autenticación local requiere una única conexión transaccional.', 503);
        }
        $remaining = $sessionExpiresAt === null
            ? max(1, (int) $this->config->get('local_auth.absolute_session_days', 30)) * 86400
            : $sessionExpiresAt->getTimestamp() - time();
        if ($remaining <= 0) {
            throw new LocalAuthException('Se requiere iniciar sesión nuevamente.');
        }
        $key = str_replace('\\n', "\n", (string) $this->config->get('passport.private_key', ''));

        if ($key === '') {
            $path = Passport::keyPath('oauth-private.key');
            if (! is_file($path)) {
                throw new LocalAuthException('El servicio de autenticación local no está configurado.', 503);
            }
            $key = 'file://'.$path;
        }

        $server = new AuthorizationServer(
            new ClientRepository($this->clients, $this->hasher),
            $this->tokens,
            $this->scopes,
            new CryptKey($key, null, Passport::$validateKeyPermissions),
            Passport::tokenEncryptionKey($this->encrypter)
        );
        $refreshSeconds = min($remaining, max(1, (int) $this->config->get('local_auth.refresh_idle_days', 7)) * 86400);
        $accessSeconds = min($remaining, max(1, (int) $this->config->get('local_auth.access_token_minutes', 15)) * 60);
        $grant->setRefreshTokenTTL(new DateInterval('PT'.$refreshSeconds.'S'));
        $server->enableGrantType($grant, new DateInterval('PT'.$accessSeconds.'S'));

        return $server;
    }
}
