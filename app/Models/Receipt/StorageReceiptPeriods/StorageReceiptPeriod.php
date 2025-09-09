<?php

namespace App\Models\Receipt\StorageReceiptPeriods;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemSchema;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageReceiptPeriod extends Model
{
    use SoftDeletes;
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storagereceiptperiods';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'name',
        'startdate',
        'finishdate',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'useid',
        'schemaid',
        'statusid',
    ];

    protected $casts = [

    ];


    protected $attributes = [
        'statusid' => 'EPR-01',
        'schemaid' => 'EPR',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageReceiptPeriod $model) {
                $model->userid = \Auth::id();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageReceiptPeriod::whereIn('storagereceiptperiods.warehouseid', $warehouses)
            ->whereIn('storagereceiptperiods.centerid', $centers);
    }


    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function schema(): BelongsTo
    {
        return $this->belongsTo(SystemSchema::class, 'schemaid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'useid', 'id');
    }

    public function scopeName($query, $name)
    {
        if (trim($name) != "") {
            return $query->where('name', 'LIKE', "%$name%");
        }
    }



}
