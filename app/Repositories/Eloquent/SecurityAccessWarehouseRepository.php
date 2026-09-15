<?php

namespace App\Repositories\Eloquent;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Repositories\SecurityAccessWarehouseRepositoryInterface;
use Illuminate\Support\Collection;

class SecurityAccessWarehouseRepository implements SecurityAccessWarehouseRepositoryInterface
{
    public function findForUser(string $username): Collection
    {
        return SecurityAccessWarehouse::where('username', $username)
            ->with(['center', 'warehouse'])
            ->get();
    }

    public function resetDefaultLevel(string $username): void
    {
        SecurityAccessWarehouse::where('username', $username)
            ->select(['id', 'defaultlevel'])
            ->get()
            ->each(function (SecurityAccessWarehouse $access) {
                $access->defaultlevel = '';
                $access->save();
            });
    }

    public function setDefaultLevel(int $id, string $username): void
    {
        $access = SecurityAccessWarehouse::where('id', $id)
            ->where('username', $username)
            ->firstOrFail();

        $access->defaultlevel = 'S';
        $access->save();
    }

    public function getCenterIdByUsername(string $username): ?string
    {
        return SecurityAccessWarehouse::query()
            ->where('username', $username)
            ->value('centerid');
    }
}
