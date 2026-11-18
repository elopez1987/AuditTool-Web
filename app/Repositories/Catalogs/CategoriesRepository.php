<?php

namespace App\Repositories\Catalogs;

use App\Models\Receipt\StorageCategoryIncidence;
use Illuminate\Support\Collection;

class CategoriesRepository
{
    const string ACTIVE_STATUS = 'E';

    public function getAll(): Collection
    {
        return StorageCategoryIncidence::controlAccessUser()
            ->where('statusid', self::ACTIVE_STATUS)->get();
    }
}
