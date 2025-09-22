<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class GenerateAccessWarehouse extends Model
{
    use LogsActivity;
    protected $table = 'storageaccesswarehouse';
    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'operatorid',
        'boxid',
        'truckid',
        'shipmentid',
        'confirmationnumber',
        'datestart',
        'datefinish',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'createuser',
        'updateuser',
        'statusid',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [
        'schemaid' => 'SEW',
        'statusid' => 'AC',
    ];

    protected $casts = [
        'datestart' => 'datetime:d/m/Y H:i',
        'datefinish' => 'datetime:d/m/Y H:i',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return GenerateAccessWarehouse::whereIn('storageaccesswarehouse.warehouseid', $warehouses)
            ->whereIn('storageaccesswarehouse.centerid', $centers);
    }

    public function scopeForName($query, $name)
    {
        if (trim($name) !== '') {
            $query->where('so.name', 'like', "%{$name}%");
        }
    }

    public function scopeForBox($query, $boxnumber)
    {
        if (trim($boxnumber) !== '') {
            $query->where('sl.boxnumber', 'like', "%{$boxnumber}%");
        }
    }

    public function scopeForCarrierFront($query, $carrierid)
    {
        if (trim($carrierid) !== '') {
            $query->where('sl.carrierid', '=', $carrierid);
        }
    }

    public function scopeForCarriers($query, $carriers)
    {
        if (! empty($carriers)) {
            $query->whereIn('sl.carrierid', $carriers);
        }
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (GenerateAccessWarehouse $model) {
                $model->createuser = Auth::id();
                $model->updateuser = Auth::id();
            });
            self::updating(function (GenerateAccessWarehouse $model) {
                $model->updateuser = Auth::id();
            });
        }
    }
}
