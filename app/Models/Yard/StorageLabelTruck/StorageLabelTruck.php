<?php

namespace App\Models\Yard\StorageLabelTruck;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\ShipmentTruck\ShipmentTruck;
use App\Models\Yard\StorageUnitType;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class StorageLabelTruck extends Model
{
    protected $table = 'storagelabeltrucks';
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $fillable = [
        'centerid',
        'warehouseid',
        'barcode',
        'carrierid',
        'truckid',
        'boxnumber',
        'econumber',
        'typeid',
        'statusid'
    ];


    protected $casts = [
        'entrydate' => 'datetime:Y-m-d H:i:s',
        'configurationflag' => 'array',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
        'schemaid' => 'STS',
        'statusid' => 'E',
    ];


//    Functions filter center for user to model
    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return StorageLabelTruck::whereIn('storagelabeltrucks.centerid', $centers);
    }
    
//    Relations with models
    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function status(): BelongsTo
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function carrier()
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid');
    }

    public function truck()
    {
        return $this->belongsTo(ShipmentTruck::class, 'truckid');
    }

    public function unittype()
    {
        return $this->belongsTo(StorageUnitType::class, 'unittypeid');
    }


//    Filters to model
    public function scopeLabel($query, $label)
    {
        if (trim($label) != "") {
            $query->where('storagelabeltrucks.barcode', 'like', "%$label%");
        }
    }

    public function scopeBoxNumber($query, $boxnumber)
    {
        if (trim($boxnumber) != "") {
            $query->where('storagelabeltrucks.boxnumber', 'like', "%$boxnumber%");
        }
    }

    public function scopeType($query, $typeid)
    {
        if (trim($typeid) != "") {
            $query->where('storagelabeltrucks.typeid', $typeid);
        }
    }

    public function scopeCarrier($query, $carrierid)
    {
        if ($carrierid != "") {
            $query->where('storagelabeltrucks.carrierid', $carrierid);
        }
    }

    public function scopeForCarrierOperator($q, $carrier)
    {
        if (!empty($carrier)) {
            $q->whereIn('storagelabeltrucks.carrierid', $carrier);
        }
    }



}
