<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageCheckListTruckEvidences extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public $timestamps = false;

    protected $table = 'storage_check_list_truck_evidences';

    protected $fillable = [
        'storagechecklistslifttruck_id',
        'name',
        'value01',
        'value02',
        'value03',
        'schemaid',
        'statusid',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    public function storagechecklistslifttruck(): BelongsTo
    {
        return $this->belongsTo(StorageCheckListLiftTrucks::class, 'storagechecklistslifttruck_id');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }
}
