<?php

namespace App\Repositories\Configuration;

use App\Models\Configuration\Schemas\SystemSchema;
use Illuminate\Database\Eloquent\Builder;

class SystemSchemaRepository
{
    public function findById(string $schemId)
    {
        return SystemSchema::where('schemaid', $schemId)->first();
    }

    public function findAll(array $data): Builder
    {
        return SystemSchema::query()
            ->when($data['name'] ?? null, function (Builder $query, string $name) {
                $query->where('name', 'like', "%{$name}%");
            })
            ->when($data['schemaid'] ?? null, function (Builder $query, string $schemaid) {
                $query->where('schemaid', 'like', "%{$schemaid}%");
            });
    }

    public function storeSchema(array $data): SystemSchema
    {
        return SystemSchema::create($data);
    }
}
