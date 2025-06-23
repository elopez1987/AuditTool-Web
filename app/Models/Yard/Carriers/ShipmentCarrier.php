<?php

namespace App\Models\Yard\Carriers;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

/**
 * @method static whereStatusid(string $string)
 */
class ShipmentCarrier extends Model
{
    use LogsActivity;
    protected $table = 'shipmentcarriers';
    protected $dateFormat = 'Y-m-d H:i:s';
    use softDeletes;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $fillable = [
        'centerid',
        'name',
        'carrierid',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'statusid'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'statusid' => 'E',
        'schemaid' => 'STS',
    ];

    protected $casts = [
        'configurationflag' => 'array',
    ];


    public function getNameAttribute($value)
    {
        return strtoupper($value);
    }


    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return ShipmentCarrier::whereIn('shipmentcarriers.centerid', $centers);
    }


//    ------------------------ Relaciones entre modelos -------------------------------
    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

//    ------------------------ Relaciones entre modelos -------------------------------


//    ------------------------ Filtro de las consultas -------------------------------
    public function scopeName($query, $name)
    {
        if (trim($name) != "") {
            $query->where('shipmentcarriers.name', 'like', "%$name%");
        }
    }

    public function scopeCarrier($query, $carrierid)
    {
        if (trim($carrierid) != "") {
            $query->where('shipmentcarriers.carrierid', '=', $carrierid);
        }
    }

    public function scopeStatus($query, $statusid)
    {
        if (trim($statusid) != '') {
            $query->where('shipmentcarriers.statusid', $statusid);
        }
    }

    public function scopeCarriers($query, $carries)
    {
        if (!empty($carries)) {
            $query->whereIn('shipmentcarriers.id', $carries);
        }
    }

//    ------------------------ Filtro de las consultas -------------------------------

}
