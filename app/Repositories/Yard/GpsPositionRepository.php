<?php

namespace App\Repositories\Yard;

use App\Models\Yard\GpsPosition;

class GpsPositionRepository
{
    public function latestForDevice(int $gpsDeviceId): ?GpsPosition
    {
        return GpsPosition::where('gps_device_id', $gpsDeviceId)
            ->orderByDesc('recorded_at')
            ->first();
    }
}
