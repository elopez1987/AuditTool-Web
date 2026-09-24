<?php

namespace App\Services\Yard;

use App\Exceptions\InvalidGeofenceException;
use App\Models\Yard\Geofence;
use App\Models\Yard\GeofencePoint;
use App\Repositories\Yard\GeofenceRepository;

readonly class GeofenceService
{
    public function __construct(
        private GeofenceRepository $repository,
    ) {}

    /**
     * @param  array{centerid: string, name: string, type?: string|null, points: array<int, array{lat: float, lng: float}>}  $validatedData
     */
    public function saveForDistributionCenter(array $validatedData): Geofence
    {
        $this->assertValidPolygon($validatedData['points']);

        $existing = $this->repository->findByDistributionCenter($validatedData['centerid']);

        if ($existing) {
            return $this->repository->replacePoints($existing, $validatedData['points']);
        }

        return $this->repository->create($validatedData);
    }

    public function findForDistributionCenter(string $centerId): ?Geofence
    {
        return $this->repository->findByDistributionCenter($centerId);
    }

    /**
     * @return array<int, array{lat: float, lng: float}>
     */
    public function pointsToArray(?Geofence $geofence): array
    {
        if (! $geofence) {
            return [];
        }

        return $geofence->points
            ->map(fn (GeofencePoint $point) => [
                'lat' => (float) $point->latitude,
                'lng' => (float) $point->longitude,
            ])
            ->all();
    }

    /**
     * @param  array<int, array{lat: float, lng: float}>  $points
     */
    private function assertValidPolygon(array $points): void
    {
        if (count($points) < 3) {
            throw new InvalidGeofenceException('Un polígono requiere al menos 3 puntos.');
        }
    }
}
