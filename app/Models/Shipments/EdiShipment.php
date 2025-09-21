<?php

namespace App\Models\Shipments;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class EdiShipment extends Model
{
    use LogsActivity;

    protected $table = 'edishipments';
    protected $primaryKey = 'id';

    protected $fillable = [
        'centerid',
        'warehouseid',
        'shipmentid',
        'auxiliary01',
        'auxiliary02',
        'auxiliary03',
        'auxiliary04',
        'auxiliary05',
        'type',
        'message',
        'quantity',
        'client',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()->logFillable();
    }

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        return EdiShipment::whereIn('edishipments.centerid', $centers);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function shipment()
    {
        return $this->belongsTo(OrderDeliverie::class, 'shipmentid', 'shipmentid');
    }

//    Crea el registro de envío de plan de carga del módulo de embarques
    public static function createEDIForPlanToShipment($shipment)
    {
        EdiShipment::updateOrCreate([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'shipmentid' => $shipment->shipmentid,
            'type' => 'PLAN',
            'client' => 'RCONTROL',
            'auxiliary01' => 'PLAN',
        ]);
    }

//    Crea el registro de envío del evento del embarque (Entrada/Inicio y fin de proceso, facturacion y salida)
    public static function createEDIForEventToShipment($shipment, $event)
    {
        EdiShipment::updateOrCreate([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'shipmentid' => $shipment->shipmentid,
            'type' => 'EVENT',
            'auxiliary01' => $event,
            'client' => 'RCONTROL',
        ]);
    }
}
