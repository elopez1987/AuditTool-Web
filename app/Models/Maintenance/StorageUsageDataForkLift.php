<?php

namespace App\Models\Maintenance;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageUsageDataForkLift extends Model
{

    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storageusagedataforklift';

    protected $fillable = [
        'centerid',
        'serialnumber',
        'horometro',
        'distance',
        'stationary',
        'userid',
        'value01',
        'value02',
        'value03',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageUsageDataForkLift $model) {
                $model->userid = Auth::id();
                $model->created_at = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageUsageDataForkLift::whereIn('storageusagedataforklift.centerid', $centers);
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid', 'centerid');
    }

    public function serialnumber(): BelongsTo
    {
        return $this->belongsTo(StorageLiftTruck::class, 'serialnumber');
    }


}
