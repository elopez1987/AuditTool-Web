<?php

namespace App\Services\Yard;

use App\Repositories\Yard\StorageUnitTypeRepository;
use Illuminate\Support\Collection;

readonly class StorageUnitOriginService
{
    public function __construct(
        private StorageUnitTypeRepository $repository,
    ) {}

    public function searchTypeForUser(string $username): Collection
    {
        return $this->repository->searchOriginAssigned($username);
    }
}
