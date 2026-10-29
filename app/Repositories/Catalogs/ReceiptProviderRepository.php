<?php

namespace App\Repositories\Catalogs;

use App\Models\Receipt\ReceiptProvider;
use Illuminate\Support\Collection;

class ReceiptProviderRepository
{
    const string ACTIVE_STATUS = 'E';

    public function all(): Collection
    {
        return ReceiptProvider::controlAccessUser()
            ->where('statusid', self::ACTIVE_STATUS)
            ->select(['id', 'centerid', 'warehouseid', 'providerid', 'name'])->get();
    }
}
