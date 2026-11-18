<?php

namespace App\Services\Catalogs;

use App\Repositories\Catalogs\StorageLocationRepository;
use Illuminate\Support\Collection;

readonly class StorageLocationService
{
    public function __construct(
        private StorageLocationRepository $locationRepository,
    ) {}

    public function all(): Collection
    {
        return $this->locationRepository->all();
    }
}
