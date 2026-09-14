<?php

namespace App\ViewModels\Yard\AccessOriginUser;

use App\User;
use Illuminate\Support\Collection;

class AccessOriginViewModel
{
    public function forIndex(Collection $origins, Collection $originsAssigned, User $user): array
    {
        return compact('origins', 'originsAssigned', 'user');
    }
}
