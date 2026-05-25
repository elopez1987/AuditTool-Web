<?php

namespace App\Traits\Web\Inventory;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Inventory\InventoryFiscal\InventoryAisle;
use App\Models\Inventory\InventoryFiscal\InventoryArea;

trait InventoryFiscalTrait
{
    public function getStatusSTS()
    {
        return SystemStatus::where('schemaid', 'STS')
            ->select(['statusid', 'name', 'comment'])
            ->whereIN('statusid', ['D', 'E'])
            ->get();
    }

    public function getAisles()
    {
        return InventoryAisle::controlAccessUser()
            ->where('statusid', 'E')
            ->select(['id', 'name'])
            ->get();
    }

    public function getAreas()
    {
        return InventoryArea::controlAccessUser()
            ->where('statusid', 'E')
            ->select(['id', 'name'])
            ->get();
    }
}
