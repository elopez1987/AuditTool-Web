<?php

namespace App\Services\Catalogs;

use App\Repositories\Catalogs\StorageOperatorRepository;
use Illuminate\Support\Collection;

readonly class StorageOperatorService
{
    public function __construct(
        private StorageOperatorRepository $operatorRepository,
    ) {}

    public function all(): Collection
    {
        return $this->operatorRepository->all();
    }
}
