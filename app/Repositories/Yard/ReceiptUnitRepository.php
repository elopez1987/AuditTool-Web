<?php

namespace App\Repositories\Yard;

use App\Models\Yard\ReceiptUnit;
use Illuminate\Support\Collection;

class ReceiptUnitRepository
{
    /**
     * Unidades que siguen en existencia dentro del patio (no han salido).
     * Precarga la vinculación activa de GPS y su dispositivo para evitar N+1.
     */
    public function inYard(): Collection
    {
        return ReceiptUnit::controlAccessUser()
            ->whereNot('statusid', 'TR')
            ->with(['gpsDeviceAssignment.device'])
            ->get();
    }
}
