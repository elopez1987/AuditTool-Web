<?php

namespace App\Services\Catalogs;

use App\Repositories\Catalogs\CategoriesRepository;
use Illuminate\Support\Collection;

readonly class CategoriesService
{
    public function __construct(
        private CategoriesRepository $categoriesRepository,
    ) {}

    public function getAll(): Collection
    {
        return $this->categoriesRepository->getAll();
    }
}
