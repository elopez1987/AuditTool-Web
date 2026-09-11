<?php

namespace App\Repositories;

use App\Models\Configuration\Warehouses\SystemWarehouse;
use Illuminate\Database\Eloquent\Builder;

interface SystemWarehouseRepositoryInterface
{
    /**
     * Query for warehouses visible to the current user, optionally filtered by name.
     */
    public function search(?string $name): Builder;

    /**
     * Warehouse IDs the current user is allowed to access, cached per user to
     * avoid re-querying SecurityAccessWarehouse on every call.
     *
     * @return array<int, string>
     */
    public function getAccessibleWarehouseIds(): array;

    public function findOrFail(string $warehouseid): SystemWarehouse;

    public function create(array $data): SystemWarehouse;

    public function update(SystemWarehouse $systemwarehouse, array $data): SystemWarehouse;
}
