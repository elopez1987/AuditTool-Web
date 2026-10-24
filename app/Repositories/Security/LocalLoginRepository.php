<?php

declare(strict_types=1);

namespace App\Repositories\Security;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\LocalAppUser;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Security\Users\StorageUserDevice;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class LocalLoginRepository
{
    /**
     * @return Collection<int, LocalAppUser>
     */
    public function findByUsername(string $username): Collection
    {
        return LocalAppUser::withTrashed()->where('username', $username)->limit(2)->lockForUpdate()->get();
    }

    public function findById(string|int $id): ?LocalAppUser
    {
        return LocalAppUser::withTrashed()->whereKey($id)->lockForUpdate()->first();
    }

    public function defaultAccess(string $username): ?SecurityAccessWarehouse
    {
        return SecurityAccessWarehouse::query()
            ->where('username', $username)
            ->whereHas('center', static fn (Builder $query): Builder => $query->where('statusid', 'E'))
            ->with('center')
            ->orderByRaw('CASE WHEN defaultlevel = ? THEN 0 ELSE 1 END', ['S'])
            ->orderBy('id')
            ->first();
    }

    public function loadPermissions(LocalAppUser $user): LocalAppUser
    {
        return $user->load([
            'roles' => static fn (BelongsToMany $query): BelongsToMany => $query->where('guard_name', 'web'),
            'roles.permissions' => static fn (BelongsToMany $query): BelongsToMany => $query->where('guard_name', 'web'),
            'permissions' => static fn (BelongsToMany $query): BelongsToMany => $query->where('guard_name', 'web'),
        ]);
    }

    /**
     * @return Collection<int, SystemWarehouse>
     */
    public function warehouses(string $username, string $centerId): Collection
    {
        return SystemWarehouse::query()->where('statusid', 'E')->where('centerid', $centerId)
            ->whereIn('warehouseid', SecurityAccessWarehouse::query()
                ->where('username', $username)->where('centerid', $centerId)->select('warehouseid'))
            ->orderBy('warehouseid')
            ->get(['warehouseid', 'configurationflag']);
    }

    public function registerDevice(int $userId, string $serialNumber): StorageUserDevice
    {
        return StorageUserDevice::query()->firstOrCreate(
            ['username' => $userId, 'serialnumber' => $serialNumber],
            ['created_at' => now()->toDateTimeString()]
        );
    }
}
