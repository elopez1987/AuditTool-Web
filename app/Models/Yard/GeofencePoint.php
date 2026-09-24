<?php

namespace App\Models\Yard;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GeofencePoint extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $fillable = [
        'geofence_id',
        'sequence',
        'latitude',
        'longitude',
    ];

    protected $casts = [
        'sequence' => 'integer',
        'latitude' => 'decimal:7',
        'longitude' => 'decimal:7',
    ];

    public function geofence(): BelongsTo
    {
        return $this->belongsTo(Geofence::class, 'geofence_id', 'id');
    }
}
