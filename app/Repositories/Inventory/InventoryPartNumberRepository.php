<?php

namespace App\Repositories\Inventory;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Support\Collection;

class InventoryPartNumberRepository
{
    const string ACTIVE_STATUS = 'E';

    public function all(): Collection
    {
        return InventoryPartnumber::controlAccessUser()
            ->where('statusid', self::ACTIVE_STATUS)->get();
    }

    public function findProductByPrtnum(string $partNumberId): ?InventoryPartnumber
    {
        return InventoryPartnumber::controlAccessUser()
            ->where('statusid', self::ACTIVE_STATUS)
            ->where('prtnum', $partNumberId)->first();
    }
}
