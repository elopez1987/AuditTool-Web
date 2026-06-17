<?php

namespace App\Models\SafetyTours\Incidents;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageSafetyCategoryDetail extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storage_safety_category_details';

    protected $fillable = [
        'stotage_category_safety_id',
        'name',
        'storage_safety_responsible_area_id',
        'configurationflag',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'schamaid',
        'statusid',
    ];

    public function stotagecategorysafety(): BelongsTo
    {
        return $this->belongsTo(StorageCategorySafety::class);
    }

    public function storagesafetyresponsiblearea(): BelongsTo
    {
        return $this->belongsTo(StorageResponsibleArea::class, 'storage_safety_responsible_area_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }
}
