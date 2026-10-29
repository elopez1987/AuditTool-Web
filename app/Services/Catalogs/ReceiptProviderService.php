<?php

namespace App\Services\Catalogs;

use App\Repositories\Catalogs\ReceiptProviderRepository;
use Illuminate\Support\Collection;

class ReceiptProviderService
{
    public function __construct(
        private readonly ReceiptProviderRepository $providerRepository,
    ) {}

    public function all(): Collection
    {
        return $this->providerRepository->all();
    }
}
