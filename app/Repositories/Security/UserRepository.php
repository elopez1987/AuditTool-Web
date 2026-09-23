<?php

namespace App\Repositories\Security;

use App\User;
use Illuminate\Support\Collection;

class UserRepository
{
    public const string ACTIVE_STATUS = 'E';

    public function findById(int $Id): User
    {
        return User::where('id', $Id)->first();
    }

    public function all(): Collection
    {
        return User::where('statusid', '=', self::ACTIVE_STATUS)
            ->get();
    }
}
