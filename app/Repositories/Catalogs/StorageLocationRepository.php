<?php

namespace App\Repositories\Catalogs;

use App\Models\Yard\StorageLocation;
use Illuminate\Support\Collection;

class StorageLocationRepository
{
    const string ACTIVE_STATUS = 'E';

    const string ZONE_COURTAIN = 'CO';

    public function all(): Collection
    {
        return StorageLocation::where('storagelocations.statusid', self::ACTIVE_STATUS)
            ->join('storagezones', 'storagelocations.zoneid', 'storagezones.id')
            ->where('storagezones.zoneid', self::ZONE_COURTAIN)
            ->where('storagezones.statusid', self::ACTIVE_STATUS)
            ->select('storagelocations.id', 'storagelocations.name', 'storagelocations.locationid', 'storagelocations.barcode')
            ->get();
    }
}
