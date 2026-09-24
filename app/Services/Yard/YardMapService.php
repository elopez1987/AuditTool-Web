<?php

namespace App\Services\Yard;

use App\Models\Yard\ReceiptUnit;
use App\Repositories\Yard\GpsPositionRepository;
use App\Repositories\Yard\ReceiptUnitRepository;
use Illuminate\Support\Collection;

readonly class YardMapService
{
    public function __construct(
        private ReceiptUnitRepository $receiptUnitRepository,
        private GpsPositionRepository $gpsPositionRepository,
    ) {}

    /**
     * @return Collection<int, array{receiptunit_id: int, label: string, carrier: string, boxnumber: string|null, shipment: string|null, has_gps: bool, latitude: float|null, longitude: float|null, recorded_at: int|null}>
     */
    public function unitsWithPositions(): Collection
    {
        return $this->receiptUnitRepository->inYard()
            ->map(fn (ReceiptUnit $unit) => $this->buildUnitPosition($unit))
            ->values();
    }

    /**
     * @return array{receiptunit_id: int, label: string, carrier: string, boxnumber: string|null, shipment: string|null, has_gps: bool, latitude: float|null, longitude: float|null, recorded_at: int|null}
     */
    private function buildUnitPosition(ReceiptUnit $unit): array
    {
        $assignment = $unit->gpsDeviceAssignment;
        $position = $assignment ? $this->gpsPositionRepository->latestForDevice($assignment->gps_device_id) : null;

        return [
            'receiptunit_id' => $unit->id,
            'label' => $unit->barcode ?: "#{$unit->id}",
            'carrier' => $unit->carrier ? $unit->carrier->name : '',
            'boxnumber' => $unit->boxnumber,
            'shipment' => $unit->shipmentid,
            'has_gps' => (bool) $assignment,
            'latitude' => $position ? (float) $position->latitude : null,
            'longitude' => $position ? (float) $position->longitude : null,
            // El cast 'timestamp' del modelo GpsPosition devuelve un entero Unix,
            // no un Carbon; se deja así (int|null) y el ViewModel decide el formato.
            'recorded_at' => $position?->recorded_at,
        ];
    }
}
