<?php

namespace App\Repositories;

use Illuminate\Support\Collection;

interface SystemStatusRepositoryInterface
{
    /**
     * Active statuses for the given schema, ordered by statusid.
     */
    public function findStatusForSchema(string $schemaid): Collection;
}
