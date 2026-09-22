<?php

namespace App\Repositories\Configuration;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Repositories\SystemStatusRepositoryInterface;
use Illuminate\Support\Collection;

class SystemStatusRepository implements SystemStatusRepositoryInterface
{
    public function findStatusForSchema(string $schemaid): Collection
    {
        return SystemStatus::where('schemaid', $schemaid)
            ->select(['statusid', 'name', 'comment'])
            ->orderBy('statusid')
            ->get();
    }
}
