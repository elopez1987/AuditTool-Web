<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Carbon\Carbon;
use DB;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;


class StorageScheduleInverse extends Model
{
    use LogsActivity;

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    protected $table = 'storagescheduleinverse';
    protected $primaryKey = 'id';


    protected $fillable = [
        'centerid', 'warehouseid', 'orderid', 'adddate', 'appointment', 'requestzare',
        'zare', 'ne', 'requestne', 'statusid', 'folionumber'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'schemaid'
    ];

    protected $attributes = [
        'statusid' => 'AD',
        'schemaid' => 'LIV',
    ];


    protected static function boot()
    {
        parent::boot();
        if (!app()->runningInConsole()) {
            self::creating(function (StorageScheduleInverse $model) {
                $model->adddate = Carbon::now();
            });
        }
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageScheduleInverse::whereIn('storagescheduleinverse.warehouseid', $warehouses)
            ->whereIn('storagescheduleinverse.centerid', $centers);
    }

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function inventory()
    {
        return $this->belongsTo(StorageScheduleInverseInventory::class, 'orderid', 'orderid');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }


    public function scopeForStatus($query, $statusid)
    {
        if ($statusid != '') {
            $query->where('storagescheduleinverse.statusid', $statusid);
        }else{
            $query->where('storagescheduleinverse.statusid','=','AD');
        }
    }

    public function scopeForShipment($query, $shipmentid)
    {
        if ($shipmentid != '') {
            $query->where('orderdeliveries.shipmentid', $shipmentid);
        }
    }

    static function addNewSchedule($shipment)
    {
        StorageScheduleInverse::updateOrCreate([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'orderid' => $shipment->id,
        ]);

        ScheduleInverseLog::createNewLog(
            $shipment,
            'Creación de registro de incidencia'
        );
    }

    static function getDataInverse($storagescheduleinverse)
    {
        return DB::table('storagescheduleinverse')
            ->select('orderdeliveries.centerid', 'orderdeliveries.warehouseid', 'orderdeliveries.shipmentid',
                'storageincidencejourneydetails.delivery', 'storageincidencejourneydetails.partnumberid',
                'inventorypartnumbers.lngdsc as description', 'storageincidencejourneydetails.quantity',
                'storageincidencejourneydetails.unitmeasure', 'storagecategoryincidencedetails.auxiliary02 as type',
                'inventorypartnumbercategory01.description as div', 'storagescheduleinversetraffic.typereport',
                'storagescheduleinversetraffic.requestzare', 'storagescheduleinversetraffic.zare','storagescheduleinversetraffic.responsezare','storagescheduleinversetraffic.responsene',
                'storagescheduleinversetraffic.responsenc','storagescheduleinversetraffic.requestnc',
                'storagescheduleinversetraffic.requestne', 'storagescheduleinversetraffic.ne',
                'storagescheduleinversetraffic.adjust', 'storagescheduleinversetraffic.nc', 'storagescheduleinversetraffic.id as trafic')
            ->leftJoin('orderdeliveries', 'orderdeliveries.id', '=', 'storagescheduleinverse.orderid')
            ->leftJoin('storageincidencejourneydetails', 'orderdeliveries.id', '=', 'storageincidencejourneydetails.orderid')
            ->leftJoin('inventorypartnumbers', 'storageincidencejourneydetails.partnumberid', '=', 'inventorypartnumbers.prtnum')
            ->leftJoin('storagecategoryincidencedetails', 'storageincidencejourneydetails.razoncode', '=', 'storagecategoryincidencedetails.categoryincdetailid')
            ->leftJoin('inventorypartnumbercategory01', 'inventorypartnumbers.category01id', '=', 'inventorypartnumbercategory01.id')
            ->leftJoin('storagescheduleinversetraffic', function ($join) {
                $join->on('storagescheduleinverse.id', '=', 'storagescheduleinversetraffic.inverseid')
                    ->on('storagescheduleinverse.orderid', '=', 'storagescheduleinversetraffic.orderid')
                    ->on('storagescheduleinversetraffic.delivery', '=', 'storageincidencejourneydetails.delivery')
                    ->on('storagescheduleinversetraffic.partnumberid', '=', 'storageincidencejourneydetails.partnumberid');
            })
            ->where('storagescheduleinverse.orderid', '=', $storagescheduleinverse->orderid)
            ->get();

    }

}
