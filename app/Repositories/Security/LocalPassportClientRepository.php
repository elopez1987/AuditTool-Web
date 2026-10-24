<?php

declare(strict_types=1);

namespace App\Repositories\Security;

use App\Models\Security\LocalPassportClient;
use Illuminate\Contracts\Hashing\Hasher;
use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

final class LocalPassportClientRepository extends ClientRepository
{
    public const NAME = 'Audittool Local App v1';

    public function find(string|int $id): ?Client
    {
        if (! ctype_digit((string) $id)) {
            return null;
        }

        return LocalPassportClient::query()
            ->whereKey($id)
            ->where('provider', 'local-app-users')
            ->first();
    }

    public function prepare(#[\SensitiveParameter] string $secret, Hasher $hasher): LocalPassportClient
    {
        $secret = hash('sha256', $secret);
        $client = LocalPassportClient::query()
            ->where('name', self::NAME)
            ->where('provider', 'local-app-users')
            ->where('revoked', false)
            ->orderBy('id')
            ->first();

        if ($client === null) {
            $client = new LocalPassportClient;
            $client->forceFill([
                'name' => self::NAME,
                'provider' => 'local-app-users',
                'secret' => $secret,
                'redirect' => '',
                'personal_access_client' => false,
                'password_client' => true,
                'revoked' => false,
            ])->save();
        } elseif (! $hasher->check($secret, $client->secret)) {
            $client->secret = $secret;
            $client->save();
        }

        return $client;
    }
}
