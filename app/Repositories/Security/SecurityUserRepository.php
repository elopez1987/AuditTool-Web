<?php

namespace App\Repositories\Security;

use App\User;

class SecurityUserRepository
{
    public function searchUserUuid(string $uuiduser)
    {
        return User::where('uuiduser', $uuiduser)->first();
    }
}
