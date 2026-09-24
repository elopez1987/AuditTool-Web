<?php

namespace App\Services\Yard;

use App\Models\Yard\GpsDevice;
use App\Repositories\Yard\GpsDeviceRepository;
use Illuminate\Database\Eloquent\Builder;

readonly class GpsDeviceService
{
    public function __construct(
        private GpsDeviceRepository $repository,
    ) {}

    public function findById(int $Id)
    {
        return $this->repository->findById($Id);
    }

    public function all(array $data): Builder
    {
        return $this->repository->all($data);
    }

    public function store(array $data)
    {
        return $this->repository->store($data);
    }

    public function update(array $data, GpsDevice $gpsDevice)
    {
        return $this->repository->update($data, $gpsDevice);
    }
}
