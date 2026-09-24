<?php

namespace App\Repositories\Yard;

use App\Models\Yard\GpsDevice;
use Illuminate\Database\Eloquent\Builder;

class GpsDeviceRepository
{
    public function findById(int $Id): GpsDevice
    {
        return GpsDevice::controlAccessUser()
            ->where('id', $Id)->first();
    }

    public function all(array $data): Builder
    {
        return GpsDevice::controlAccessUser()
            ->with(['center:centerid,description,name', 'status:statusid,name'])
            ->when(! empty($data['imei']), fn ($query) => $query->where('imei', $data['imei']))
            ->when(! empty($data['sim_number']), fn ($query) => $query->where('sim_number', $data['sim_number']));
    }

    public function store(array $data)
    {
        return GpsDevice::create($data);
    }

    public function update(array $data, GpsDevice $gpsDevice)
    {
        return $gpsDevice->update($data);
    }
}
