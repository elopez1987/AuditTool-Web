<?php

namespace App\ViewModels\Configuration;

use App\User;
use Illuminate\Support\Collection;

class SecurityAccessWarehouseViewModel
{
    /**
     * Data for Configuration/SetWarehouse/index.blade.php. Preserves the
     * exact variable names ("user", "accesos") already consumed there.
     *
     * @return array{user: User, accesos: Collection}
     */
    public function forIndex(User $user, Collection $accesos): array
    {
        return [
            'user' => $user,
            'accesos' => $accesos,
        ];
    }
}
