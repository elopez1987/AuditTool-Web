<?php

namespace App\Models\Shipments\ScheduleInverse;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class ScheduleInverse extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'scheduleinverse';

    protected $fillable = [
        'centerid', 'warehouseid', 'createuser',
        'name', 'startdate', 'finishdate', 'statusid'
    ];


    //INO = Inactivo
    //ACT = Activo
    protected $attributes = [
        'statusid' => 'INO',
        'schemaid' => 'KLD',
    ];


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (ScheduleInverse $model) {
                $model->createuser = Auth::id();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ScheduleInverse::whereIn('scheduleinverse.warehouseid', $warehouses)
            ->whereIn('scheduleinverse.centerid', $centers);
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }


    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }


    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }




}
