<?php

declare(strict_types=1);

namespace App\Services\Security;

use Auth0\Laravel\Bridges\CacheBridge;
use Auth0\SDK\Configuration\SdkConfiguration;
use Auth0\SDK\Exception\ConfigurationException;
use Illuminate\Contracts\Config\Repository;

final class MobileAuthConfiguration
{
    public function __construct(
        private readonly Repository $config,
        private readonly CacheBridge $cache,
    ) {}

    public function isConfigured(): bool
    {
        $domain = $this->config->get('mobile_auth.domain');
        $audience = $this->config->get('mobile_auth.audience');
        $clients = $this->allowedClientIds();

        return $this->config->get('mobile_auth.enabled') === true
            && is_string($domain)
            && str_contains($domain, '.')
            && filter_var($domain, FILTER_VALIDATE_DOMAIN, FILTER_FLAG_HOSTNAME) !== false
            && is_string($audience)
            && trim($audience) !== ''
            && ! str_contains($audience, '/api/v2')
            && $clients !== []
            && ! in_array($audience, $clients, true);
    }

    /**
     * @return list<string>
     */
    public function allowedClientIds(): array
    {
        $clients = $this->config->get('mobile_auth.allowed_client_ids', []);

        if (! is_array($clients)) {
            return [];
        }

        return array_values(array_filter(
            $clients,
            static fn (mixed $client): bool => is_string($client) && trim($client) !== ''
        ));
    }

    public function requiredScope(): string
    {
        return 'mobile:access';
    }

    public function leeway(): int
    {
        return 30;
    }

    /**
     * @return array<string, mixed>
     */
    public function sdkOptions(): array
    {
        if (! $this->isConfigured()) {
            throw new ConfigurationException('Mobile authentication is not configured.');
        }

        return [
            'strategy' => SdkConfiguration::STRATEGY_API,
            'domain' => $this->config->get('mobile_auth.domain'),
            'audience' => [$this->config->get('mobile_auth.audience')],
            'tokenAlgorithm' => 'RS256',
            'tokenJwksUri' => 'https://'.$this->config->get('mobile_auth.domain').'/.well-known/jwks.json',
            'tokenLeeway' => $this->leeway(),
            'tokenCache' => $this->cache,
            'tokenCacheTtl' => 600,
            'httpMaxRetries' => 0,
        ];
    }
}
