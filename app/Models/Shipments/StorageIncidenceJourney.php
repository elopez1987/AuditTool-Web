<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class StorageIncidenceJourney extends Model
{
    protected $table = 'storageincidencejourneys';
    protected $primaryKey = 'folioid';

    protected $fillable = ['centerid', 'warehouseid', 'orderid',
        'createuser', 'carrierid', 'schemaid', 'statusid',
        'auxiliary01', 'auxiliary02', 'auxiliary03', 'auxiliary04',
        'auxiliary05', 'statuskeyid', 'statusid', 'statuskeytrid', 'statusdevid',
        'responsedate',

    ];

    protected $hidden = [
        'updated_at',
        'schemaid',
    ];

    protected $attributes = [

//        Estatus del reporte
        'statusid' => 'AB',
        'schemaid' => 'EIJ',

//        Estatus de firma de almacen
        'statuskeyid' => 'SF',
        'schemakeyid' => 'KEY',

//        Estatus de firma de transporte
        'statuskeytrid' => 'SF',
        'schemakeytrid' => 'KEY',

//        Estatus de cita de entrega
        'statusdevid' => 'SIC',
        'schemadevid' => 'DEC',

    ];

    protected $casts = [
//        'created_at' => 'datetime:Y-m-d H:s',
    ];

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid');
    }

    public function key()
    {
        return $this->belongsTo(StorageKeyIncidenceOperation::class, 'orderid', 'orderid');
    }

    public function keytransport()
    {
        return $this->belongsTo(StorageKeyIncidenceTransport::class, 'orderid', 'orderid');
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageIncidenceJourney::whereIn('storageincidencejourneys.warehouseid', $warehouses)
            ->whereIn('storageincidencejourneys.centerid', $centers);
    }

    public function scopeCarrier($query, $carrierid)
    {
        if (trim($carrierid) !== '') {
            $query->where('orderdeliveries.carrierid', $carrierid);
        }
    }

    public function scopeForShipment($query, $shipmentid)
    {
        if (trim($shipmentid) !== '') {
            $query->where('orderdeliveries.shipmentid', $shipmentid);
        }
    }

    public function scopeForDate($query, $datein, $dateout)
    {
        if (! empty($datein) && ! empty($dateout)) {
            $query->whereBetween('orderdeliveries.downloaddate', [$datein, $dateout]);
        } else {
            $query->whereDate('orderdeliveries.downloaddate', '>=', Carbon::now()->subDays(10));
        }
    }

    public function scopeStatusIncidence($query, $statusid)
    {
        if (! empty($statusid)) {
            $query->where('storageincidencejourneys.statusid', $statusid);
        }
    }

    public static function searchIncidence($folioid)
    {
        return StorageIncidenceJourney::controlAccessUser()
            ->where('folioid', $folioid)->first();
    }

    protected static function boot()
    {
        parent::boot();
        if (! app()->runningInConsole()) {
            self::creating(function (StorageIncidenceJourney $model) {
                $model->createuser = Auth::id();
            });
        }
    }
}
