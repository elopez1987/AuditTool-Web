<?php

namespace App\Repositories;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use Illuminate\Database\Eloquent\Builder;

interface SystemCenterDistributionRepositoryInterface
{
    /**
     * Query for centers visible to the current user, optionally filtered by name.
     */
    public function search(?string $name): Builder;

    /** Query all centers */
    public function searchAll();

    /**
     * Center IDs the current user is allowed to access, cached per user to
     * avoid re-querying SecurityAccessWarehouse on every call.
     *
     * @return array<int, string>
     */
    public function getAccessibleCenterIds(): array;

    public function findOrFail(string $centerid): SystemCenterDistribution;

    public function create(array $data): SystemCenterDistribution;

    public function update(SystemCenterDistribution $systemcenterdistribution, array $data): SystemCenterDistribution;
}
