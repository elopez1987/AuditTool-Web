<?php

namespace App\Repositories\Eloquent;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Repositories\SystemStatusRepositoryInterface;
use Illuminate\Support\Collection;

class SystemStatusRepository implements SystemStatusRepositoryInterface
{
    public function findStatusForSchema(string $schemaid): Collection
    {
        return SystemStatus::whereSchemaid($schemaid)
            ->select(['statusid', 'name', 'comment'])
            ->orderBy('statusid')
            ->get();
    }
}
