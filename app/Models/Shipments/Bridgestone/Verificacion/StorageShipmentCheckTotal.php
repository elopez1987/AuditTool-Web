<?php

namespace App\Models\Shipments\Bridgestone\Verificacion;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\StorageLocation;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageShipmentCheckTotal extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storageshipmentchecktotal';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'locationid',
        'quantity',
        'userid',
        'value01',
        'value02',
        'value03',
        'keyoperator',
        'keyguard',
        'keysupervisor',
        'name',
        'team',
        'tunrid',
        'supervisorname',
        'supervisorkey',
        'folioid',
    ];

    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageShipmentCheckTotal $model) {
                $model->userid = Auth::id();
                $model->created_at = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageShipmentCheckTotal::whereIn('storageshipmentchecktotal.warehouseid', $warehouses)
            ->whereIn('storageshipmentchecktotal.centerid', $centers);
    }


    public function center(): BelongsTo
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid');
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(StorageLocation::class, 'locationid');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'userid', 'id');
    }


}
