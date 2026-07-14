<?php

namespace App\Models\SafetyTours\Incidents;

use App\Models\Receipt\StorageCategoryIncidenceDetail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageSafetyResponsibleConfiguration extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;

    protected $table = 'storage_safety_responsible_configurations';

    protected $fillable = [
        'storage_safety_category_detail_id',
        'level01',
        'timeout01',
        'attempts01',
        'level02',
        'timeout02',
        'attempts02',
        'level03',
        'timeout03',
        'attempts03',
    ];

    public function storageSafetyResponsibleArea(): BelongsTo
    {
        return $this->belongsTo(StorageCategoryIncidenceDetail::class, 'storage_safety_category_detail_id', 'id');
    }
}
