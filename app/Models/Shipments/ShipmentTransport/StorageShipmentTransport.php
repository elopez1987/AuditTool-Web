<?php

namespace App\Models\Shipments\ShipmentTransport;

use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Yard\Carriers\ShipmentCarrier;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class StorageShipmentTransport extends Model
{
    use LogsActivity;
    public $timestamps = false;

//    Auxiliary01 == Ingreso de unidad en el moduo de patios (1 con transporte)
//    Auxiliary02 == Datos de transporte registrado en sistema por tráfico o registro de QR
//    Auxiliary03 == Número de economico de caja
//    Auxiliary04 == Número de economico de tracto

    protected $table = 'shipmenttransport';
    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'carrierid',
        'operatorname',
        'drivernumber',
        'phone',
        'boxnumber',
        'trucknumber',
        'createuser',
        'createdate',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public function carrier()
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id');
    }

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return StorageShipmentTransport::whereIn('shipmenttransport.warehouseid', $warehouses)
            ->whereIn('shipmenttransport.centerid', $centers);
    }
}
