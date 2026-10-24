<?php

declare(strict_types=1);

namespace App\Repositories\Security;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

final class MobileUserRepository
{
    /**
     * @return Collection<int, User>
     */
    public function findBySubject(string $subject): Collection
    {
        return User::withTrashed()
            ->where('auth0', $subject)
            ->limit(2)
            ->get(['id', 'uuiduser', 'username', 'name', 'email', 'auth0', 'statusid', 'deleted_at']);
    }

    public function loadPermissions(User $user): User
    {
        return $user->load([
            'roles' => fn (BelongsToMany $query): BelongsToMany => $query->where('guard_name', 'web'),
            'roles.permissions' => fn (BelongsToMany $query): BelongsToMany => $query->where('guard_name', 'web'),
            'permissions' => fn (BelongsToMany $query): BelongsToMany => $query->where('guard_name', 'web'),
        ]);
    }

    /**
     * @return Collection<int, SecurityAccessWarehouse>
     */
    public function warehouseAccess(string $username): Collection
    {
        return SecurityAccessWarehouse::query()
            ->where('username', $username)
            ->select(['centerid', 'warehouseid'])
            ->distinct()
            ->orderBy('centerid')
            ->orderBy('warehouseid')
            ->get();
    }
}
