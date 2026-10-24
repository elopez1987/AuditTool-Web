<?php

declare(strict_types=1);

namespace App\Services\Security;

use App\Exceptions\LocalAuthException;
use App\Models\Security\LocalAppUser;
use App\Repositories\Security\LocalLoginRepository;
use Illuminate\Contracts\Hashing\Hasher;
use RuntimeException;

final class LocalAccountService
{
    private static ?string $dummyHash = null;

    public function __construct(
        private readonly LocalLoginRepository $users,
        private readonly Hasher $hasher,
    ) {}

    public function authenticate(string $username, #[\SensitiveParameter] string $password): LocalAppUser
    {
        $matches = $this->users->findByUsername($username);
        $user = $matches->count() === 1 ? $matches->first() : null;
        self::$dummyHash ??= $this->hasher->make(bin2hex(random_bytes(32)));
        $hash = is_string($user?->password) && $user->password !== '' ? $user->password : self::$dummyHash;

        try {
            $valid = $this->hasher->check($password, $hash);
        } catch (RuntimeException) {
            $this->hasher->check($password, self::$dummyHash);
            $valid = false;
        }

        if (! $valid || $user === null || $user->statusid !== 'E' || $user->trashed()) {
            throw new LocalAuthException('Credenciales inválidas o usuario no habilitado.');
        }

        return $user;
    }

    public function fromId(string|int $id): LocalAppUser
    {
        $user = $this->users->findById($id);

        if ($user === null || $user->statusid !== 'E' || $user->trashed()) {
            throw new LocalAuthException('Credenciales inválidas o usuario no habilitado.');
        }

        return $user;
    }
}
