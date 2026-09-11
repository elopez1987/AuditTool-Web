<?php

namespace App\Repositories\Eloquent;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Repositories\SystemWarehouseRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SystemWarehouseRepository implements SystemWarehouseRepositoryInterface
{
    public function search(?string $name): Builder
    {
        return SystemWarehouse::whereIn('systemwarehouses.warehouseid', $this->getAccessibleWarehouseIds())
            ->leftJoin('systemstatus', 'systemwarehouses.statusid', 'systemstatus.statusid')
            ->leftJoin('systemcenterdistributions', 'systemwarehouses.centerid', 'systemcenterdistributions.centerid')
            ->select([
                'systemcenterdistributions.name as centername',
                'systemwarehouses.warehouseid',
                'systemwarehouses.name',
                'systemwarehouses.center',
                'systemwarehouses.auxiliary01',
                'systemwarehouses.auxiliary02',
                'systemwarehouses.auxiliary03',
                'systemwarehouses.auxiliary04',
                'systemwarehouses.auxiliary05',
                'systemstatus.name as status',
            ])
            ->when(trim((string) $name) !== '', function (Builder $query) use ($name) {
                $query->where('systemwarehouses.name', 'like', "%{$name}%");
            });
    }

    public function getAccessibleWarehouseIds(): array
    {
        $userId = Auth::id();

        return Cache::remember(
            "security_warehouses_{$userId}",
            300,
            fn () => SecurityAccessWarehouse::Warehouses(),
        );
    }

    public function findOrFail(string $warehouseid): SystemWarehouse
    {
        return SystemWarehouse::findOrFail($warehouseid);
    }

    public function create(array $data): SystemWarehouse
    {
        return SystemWarehouse::create($data);
    }

    public function update(SystemWarehouse $systemwarehouse, array $data): SystemWarehouse
    {
        $systemwarehouse->update($data);

        return $systemwarehouse;
    }
}
