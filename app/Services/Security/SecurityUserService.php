<?php

namespace App\Services\Security;

use App\Repositories\Security\SecurityUserRepository;

readonly class SecurityUserService
{
    public function __construct(
        private SecurityUserRepository $userRepository,
    ) {}

    public function findByUuid(string $uuiduser)
    {
        return $this->userRepository->searchUserUuid($uuiduser);
    }
}
