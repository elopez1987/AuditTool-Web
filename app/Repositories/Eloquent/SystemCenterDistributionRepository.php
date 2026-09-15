<?php

namespace App\Repositories\Eloquent;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Repositories\SystemCenterDistributionRepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class SystemCenterDistributionRepository implements SystemCenterDistributionRepositoryInterface
{
    public const string STATUS_AVAILABLE = 'E';

    public function search(?string $name): Builder
    {
        return SystemCenterDistribution::whereIn('systemcenterdistributions.centerid', $this->getAccessibleCenterIds())
            ->with(['status:statusid,name'])
            ->where('statusid', self::STATUS_AVAILABLE)
            ->when(trim((string) $name) !== '', function (Builder $query) use ($name) {
                $query->where('systemcenterdistributions.name', 'like', "%{$name}%");
            });
    }

    public function searchAll()
    {
        return SystemCenterDistribution::select(['centerid', 'name', 'description'])
            ->where('statusid', self::STATUS_AVAILABLE)
            ->get();
    }

    public function getAccessibleCenterIds(): array
    {
        $userId = Auth::id();

        $access = Cache::remember(
            "security_centers_{$userId}",
            300,
            fn () => SecurityAccessWarehouse::AccessScope(),
        );

        return $access['centers'];
    }

    public function findOrFail(string $centerid): SystemCenterDistribution
    {
        return SystemCenterDistribution::findOrFail($centerid);
    }

    public function create(array $data): SystemCenterDistribution
    {
        return SystemCenterDistribution::create($data);
    }

    public function update(SystemCenterDistribution $systemcenterdistribution, array $data): SystemCenterDistribution
    {
        $systemcenterdistribution->update($data);

        return $systemcenterdistribution;
    }
}
