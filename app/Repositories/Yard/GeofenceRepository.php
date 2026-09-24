<?php

namespace App\Repositories\Yard;

use App\Models\Yard\Geofence;
use App\Models\Yard\GeofencePoint;
use Illuminate\Support\Facades\DB;

class GeofenceRepository
{
    public const string ACTIVE_STATUS = 'E';

    public function findByDistributionCenter(string $centerId): ?Geofence
    {
        return Geofence::with('points')
            ->where('centerid', $centerId)
            ->where('statusid', self::ACTIVE_STATUS)
            ->first();
    }

    /**
     * @param  array{centerid: string, name: string, type?: string|null, points: array<int, array{lat: float, lng: float}>}  $data
     */
    public function create(array $data): Geofence
    {
        return DB::transaction(function () use ($data) {
            $geofence = Geofence::create([
                'centerid' => $data['centerid'],
                'name' => $data['name'],
            ]);

            $this->insertPoints($geofence, $data['points']);

            return $geofence->load('points');
        });
    }

    /**
     * @param  array<int, array{lat: float, lng: float}>  $points
     */
    public function replacePoints(Geofence $geofence, array $points): Geofence
    {
        return DB::transaction(function () use ($geofence, $points) {
            $geofence->points()->delete();

            $this->insertPoints($geofence, $points);

            return $geofence->load('points');
        });
    }

    /**
     * @param  array<int, array{lat: float, lng: float}>  $points
     */
    private function insertPoints(Geofence $geofence, array $points): void
    {
        $rows = collect($points)->map(fn (array $point, int $sequence) => [
            'geofence_id' => $geofence->id,
            'sequence' => $sequence,
            'latitude' => $point['lat'],
            'longitude' => $point['lng'],
            'created_at' => now(),
            'updated_at' => now(),
        ])->all();

        GeofencePoint::insert($rows); // insert masivo, sin necesidad de upsert aquí
    }
}
