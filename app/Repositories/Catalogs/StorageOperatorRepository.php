<?php

namespace App\Repositories\Catalogs;

use App\Models\Yard\StorageOperator;
use Illuminate\Support\Collection;

class StorageOperatorRepository
{
    const string ACTIVE_STATUS = 'E';

    public function all(): Collection
    {
        return StorageOperator::controlAccessUser()
            ->where('statusid', self::ACTIVE_STATUS)
            ->select(['id', 'barcode', 'name'])->get();
    }
}
