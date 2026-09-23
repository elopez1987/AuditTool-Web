<?php

namespace App\Repositories\Configuration;

use App\Models\Configuration\ActivityLog\ActivityLog;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class ActivityLogRepository
{
    public function findById(int $Id)
    {
        return ActivityLog::findOrFail($Id);
    }

    public function findByDescription(string $filter): Collection
    {
        return ActivityLog::select($filter, $filter)
            ->distinct()->get();
    }

    public function all(array $data): Builder
    {
        return ActivityLog::with('user:id,name')
            ->when(
                $data['userid'] ?? null,
                fn (Builder $query, $value) => $query->where('causer_id', $value),
            )
            ->when(
                $data['model'] ?? null,
                fn (Builder $query, $value) => $query->where('subject_type', $value),
            )
            ->when(
                $data['subject_id'] ?? null,
                fn (Builder $query, $value) => $query->where('subject_id', $value),
            );
    }
}
