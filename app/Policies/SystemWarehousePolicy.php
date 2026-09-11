<?php

namespace App\Policies;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\User;

class SystemWarehousePolicy
{
    /**
     * These delegate to the exact permission strings already used by the
     * controllers and the Blade views (systemwarehouses.*), so existing
     * role/permission assignments keep working unchanged.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('systemwarehouses.index');
    }

    public function create(User $user): bool
    {
        return $user->can('systemwarehouses.create');
    }

    public function update(User $user, SystemWarehouse $systemwarehouse): bool
    {
        return $user->can('systemwarehouses.edit');
    }

    public function parametrize(User $user, SystemWarehouse $systemwarehouse): bool
    {
        return $user->can('systemwarehouses.parameters');
    }

    public function notify(User $user, SystemWarehouse $systemwarehouse): bool
    {
        return $user->can('systemwarehouses.notifications');
    }
}
