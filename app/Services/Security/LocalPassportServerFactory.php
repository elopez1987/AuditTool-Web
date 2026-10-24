<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Exceptions\LocalAuthException;
use App\Repositories\Security\LocalPassportClientRepository;
use DateInterval;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Encryption\Encrypter;
use Illuminate\Contracts\Hashing\Hasher;
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
    ) {}

    public function make(AbstractGrant $grant): AuthorizationServer
    {
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
        $grant->setRefreshTokenTTL(new DateInterval('P30D'));
        $server->enableGrantType($grant, new DateInterval('PT24H'));

        return $server;
    }
}
