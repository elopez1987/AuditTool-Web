<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GpsPosition extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'gps_positions';

    protected $fillable = [
        'gps_device_id',
        'latitude',
        'longitude',
        'speed',
        'heading',
        'altitude',
        'satellites',
        'battery_level',
        'recorded_at',
    ];

    public function device(): BelongsTo
    {
        return $this->belongsTo(GpsDevice::class, 'gps_device_id', 'id');
    }

    protected function casts(): array
    {
        return [
            'recorded_at' => 'timestamp',
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }
}
