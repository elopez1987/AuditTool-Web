<?php

namespace App\ViewModels\Yard;

use App\Models\Yard\Geofence;
use App\Services\Configuration\ServiceSystemCenterDistribution;
use App\Services\Yard\GeofenceService;
use App\Services\Yard\YardMapService;
use Carbon\Carbon;

readonly class GeofenceViewModel
{
    public function __construct(
        private ServiceSystemCenterDistribution $centerDistribution,
        private GeofenceService $geofenceService,
        private YardMapService $yardMapService,
    ) {}

    public function create(): array
    {
        return [
            'centers' => $this->centerDistribution->getCenters(),
        ];
    }

    /**
     * @return array{centers: mixed, selectedCenterId: string|null, geofence: Geofence|null, points: array<int, array{lat: float, lng: float}>, unitsInYard: array<int, array{id: int, label: string, boxnumber: string|null, carrier: string, shipment: string|null, lat: float, lng: float, recorded_at: string|null}>}
     */
    public function index(?string $centerId): array
    {
        $geofence = $centerId ? $this->geofenceService->findForDistributionCenter($centerId) : null;

        return [
            'centers' => $this->centerDistribution->getCenters(),
            'selectedCenterId' => $centerId,
            'geofence' => $geofence,
            'points' => $this->geofenceService->pointsToArray($geofence),
            'unitsInYard' => $this->unitsInYardForMap(),
        ];
    }

    /**
     * Unidades en patio con posición GPS válida, listas para pintarse como
     * marcador en el mapa. Se descartan las unidades sin GPS asignado o sin
     * posición registrada aún, ya que la vista actual no tiene una lista
     * separada donde mostrarlas.
     *
     * @return array<int, array{id: int, label: string, boxnumber: string|null, carrier: string, shipment: string|null, lat: float, lng: float, recorded_at: string|null}>
     */
    private function unitsInYardForMap(): array
    {
        return $this->yardMapService->unitsWithPositions()
            ->filter(fn (array $unit) => $unit['latitude'] !== null && $unit['longitude'] !== null)
            ->map(fn (array $unit) => [
                'id' => $unit['receiptunit_id'],
                'label' => $unit['label'],
                'boxnumber' => $unit['boxnumber'],
                'carrier' => $unit['carrier'],
                'shipment' => $unit['shipment'],
                'lat' => $unit['latitude'],
                'lng' => $unit['longitude'],
                'recorded_at' => $unit['recorded_at'] ? Carbon::createFromTimestamp($unit['recorded_at'])->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s') : null,
            ])
            ->values()
            ->all();
    }
}
