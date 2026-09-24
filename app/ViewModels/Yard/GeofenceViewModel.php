<?php

namespace App\ViewModels\Yard;

use App\Models\Yard\Geofence;
use App\Services\Configuration\ServiceSystemCenterDistribution;
use App\Services\Yard\GeofenceService;

readonly class GeofenceViewModel
{
    public function __construct(
        private ServiceSystemCenterDistribution $centerDistribution,
        private GeofenceService $geofenceService,
    ) {}

    public function create(): array
    {
        return [
            'centers' => $this->centerDistribution->getCenters(),
        ];
    }

    /**
     * @return array{centers: mixed, selectedCenterId: string|null, geofence: Geofence|null, points: array<int, array{lat: float, lng: float}>}
     */
    public function index(?string $centerId): array
    {
        $geofence = $centerId ? $this->geofenceService->findForDistributionCenter($centerId) : null;

        return [
            'centers' => $this->centerDistribution->getCenters(),
            'selectedCenterId' => $centerId,
            'geofence' => $geofence,
            'points' => $this->geofenceService->pointsToArray($geofence),
        ];
    }
}
