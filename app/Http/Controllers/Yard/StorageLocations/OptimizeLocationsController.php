<?php

namespace App\Http\Controllers\Yard\StorageLocations;

use App\Http\Controllers\BaseController;
use App\Models\Yard\StorageLocation;

class OptimizeLocationsController extends BaseController
{
    public function optimizelocations()
    {
        $this->UpdateLocationToEnabled();

        return $this->redirectBackSuccess();
    }

    private static function UpdateLocationToEnabled(): void
    {
        StorageLocation::update(['statusid' => 'E', 'auxiliary01' => '']);
    }
}
