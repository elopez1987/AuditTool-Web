<?php

declare(strict_types=1);

namespace App\Repositories\Security;

use Laravel\Passport\Client;
use Laravel\Passport\ClientRepository;

final class ExternalPassportClientRepository extends ClientRepository
{
    public function find(string|int $id): ?Client
    {
        $client = parent::find($id);

        return $client?->provider === 'local-app-users' ? null : $client;
    }
}
