<?php

namespace App\Models\SafetyTours\Incidents;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageSafetyResponsibleEmail extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;

    protected $table = 'storage_safety_responsible_emails';

    protected $fillable = [
        'storage_safety_responsible_area_id',
        'name',
        'email',
        'phone',
        'level',
        'attempts',
    ];

    public function storagesafetyresponsiblearea(): BelongsTo
    {
        return $this->belongsTo(StorageResponsibleArea::class, 'storage_safety_responsible_area_id');
    }
}
