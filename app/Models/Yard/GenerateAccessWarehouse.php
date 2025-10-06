<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @property int $id
 * @property string $centerid
 * @property string $warehouseid
 * @property int $operatorid
 * @property int $boxid
 * @property int $truckid
 * @property string|null $shipmentid
 * @property string|null $confirmationnumber
 * @property \Illuminate\Support\Carbon $datestart
 * @property \Illuminate\Support\Carbon $datefinish
 * @property int $createuser
 * @property int $updateuser
 * @property string|null $auxiliary01
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string $schemaid
 * @property string $statusid
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \Spatie\Activitylog\Models\Activity> $activities
 * @property-read int|null $activities_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse forBox($boxnumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse forCarrierFront($carrierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse forCarriers($carriers)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse forName($name)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereAuxiliary01($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereBoxid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereConfirmationnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereCreateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereDatefinish($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereDatestart($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereOperatorid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereSchemaid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereTruckid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereUpdateuser($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|GenerateAccessWarehouse whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
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
