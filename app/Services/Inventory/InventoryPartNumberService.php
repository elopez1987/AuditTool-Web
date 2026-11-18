<?php

namespace App\Services\Inventory;

use App\Exceptions\ApiEntityNotFoundException;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Repositories\Inventory\InventoryPartNumberRepository;
use Illuminate\Support\Collection;

readonly class InventoryPartNumberService
{
    public function __construct(
        private InventoryPartNumberRepository $numberRepository,
    ) {}

    public function all(): Collection
    {
        return $this->numberRepository->all();
    }

    public function findProduct(string $partNumberId): InventoryPartnumber
    {
        $product = $this->numberRepository->findProductByPrtnum($partNumberId);

        if ($product === null) {
            throw ApiEntityNotFoundException::partNumber($partNumberId);
        }

        return $product;

    }
}
