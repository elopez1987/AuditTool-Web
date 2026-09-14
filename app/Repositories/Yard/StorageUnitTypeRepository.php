<?php

namespace App\Repositories\Yard;

use App\Models\Yard\StorageUnitType;
use Illuminate\Support\Collection;

class StorageUnitTypeRepository
{
    public function searchOriginAssigned(string $username): Collection
    {
        return StorageUnitType::query()
            ->whereIn('centerid', function ($query) use ($username) {
                $query->select('centerid')
                    ->from('securityaccesswarehouses')
                    ->where('username', $username);
            })
            ->whereNotIn('id', function ($query) use ($username) {
                $query->select('storageunittype_id')
                    ->from('securityaccessorigins')
                    ->where('username', $username);
            })
            ->select(['id', 'description'])
            ->get();
    }
}
