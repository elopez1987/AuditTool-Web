<?php

namespace App\ViewModels;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use Illuminate\Support\Collection;

class SystemWarehouseViewModel
{
    /**
     * Data for create.blade.php / partials/formCreate.blade.php.
     *
     * @return array{centers: Collection}
     */
    public function forCreate(Collection $centers): array
    {
        return compact('centers');
    }

    /**
     * Data for edit.blade.php / partials/formEdit.blade.php. Preserves the
     * exact variable names ("systemwarehouse", "status") already consumed there.
     *
     * @return array{systemwarehouse: SystemWarehouse, status: Collection}
     */
    public function forEdit(SystemWarehouse $systemwarehouse, Collection $status): array
    {
        return compact('systemwarehouse', 'status');
    }

    /**
     * Data for Parameters/create.blade.php (SWH schema).
     *
     * @return array{warehouse: SystemWarehouse, parameters: Collection}
     */
    public function forParameters(SystemWarehouse $warehouse, Collection $parameters): array
    {
        return compact('warehouse', 'parameters');
    }

    /**
     * Data for Notifications/create.blade.php (MWH schema).
     *
     * @return array{warehouse: SystemWarehouse, parameters: Collection}
     */
    public function forNotifications(SystemWarehouse $warehouse, Collection $parameters): array
    {
        return compact('warehouse', 'parameters');
    }
}
