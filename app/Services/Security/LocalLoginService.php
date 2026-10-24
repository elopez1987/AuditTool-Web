<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Exceptions\LocalAuthException;
use App\Models\Security\LocalAppUser;
use App\Repositories\Security\LocalLoginRepository;
use Illuminate\Database\DatabaseManager;

final class LocalLoginService
{
    public function __construct(
        private readonly LocalPassportTokenService $tokens,
        private readonly LocalLoginRepository $users,
        private readonly DatabaseManager $database,
    ) {}

    /**
     * @param  array{username: string, password: string, serialnumber?: ?string}  $data
     * @return array<string, mixed>
     */
    public function login(#[\SensitiveParameter] array $data): array
    {
        return $this->database->connection()->transaction(function () use ($data): array {
            $issued = $this->tokens->login($data['username'], $data['password']);

            return $this->profile($issued['user'], $data['serialnumber'] ?? null) + ['auth' => $issued['auth']];
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function refresh(#[\SensitiveParameter] string $refreshToken): array
    {
        return $this->database->connection()->transaction(function () use ($refreshToken): array {
            $issued = $this->tokens->refresh($refreshToken);

            return $this->profile($issued['user']) + ['auth' => $issued['auth']];
        });
    }

    /**
     * @return array<string, mixed>
     */
    public function profile(LocalAppUser $user, ?string $serialNumber = null): array
    {
        $access = $this->users->defaultAccess((string) $user->username);

        if ($access === null) {
            throw new LocalAuthException('El usuario no tiene un centro de distribución activo asignado.', 403);
        }

        $device = $serialNumber === null ? null : $this->users->registerDevice((int) $user->id, $serialNumber);

        return [
            'user' => $this->users->loadPermissions($user),
            'center' => $access->center,
            'warehouses' => $this->users->warehouses((string) $user->username, $access->centerid),
            'device' => $device,
        ];
    }
}
