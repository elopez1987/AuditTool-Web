<?php

namespace App\Models\Shipments;

use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\Turns\StorageTurn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\General\StorageComment;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\Customer\OrderCustomer;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $centerid
 * @property string $warehouseid
 * @property int $id
 * @property string $shipmentid
 * @property string $workday
 * @property OrderCustomer|null $customer
 * @property string|null $city
 * @property string $carga
 * @property string $descarga
 * @property string|null $inicioreal
 * @property string|null $iniciopro
 * @property string|null $arribopro
 * @property string|null $arriboreal
 * @property string|null $cargapro
 * @property string|null $cargareal
 * @property string|null $doctufacpro
 * @property string|null $doctofacreal
 * @property string|null $iniciofacpro
 * @property string|null $iniciofacreal
 * @property string|null $autorizepro
 * @property string|null $autorizereal
 * @property string|null $salidamax
 * @property SystemStatus|null $status
 * @property string $statusid
 * @property-read ShipmentCarrier|null $carrier
 * @property-read \Illuminate\Database\Eloquent\Collection<int, StorageComment> $comments
 * @property-read int|null $comments_count
 * @property-read \App\Models\Shipments\ShipmentHeader|null $header
 * @property-read \App\Models\Shipments\OrderMonitor|null $monitor
 * @property-read \App\Models\Shipments\ShipmentDataOperation|null $operation
 * @property-read \App\Models\Shipments\OrderDeliverie|null $order
 * @property-read StorageShipmentTransport|null $transport
 * @property-read StorageTurn|null $turn
 * @property-read SystemWarehouse|null $warehouse
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle forStatus($status)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle shipmentid($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereArribopro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereArriboreal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereAutorizepro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereAutorizereal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereCarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereCargapro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereCargareal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereDescarga($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereDoctofacreal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereDoctufacpro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereIniciofacpro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereIniciofacreal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereIniciopro($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereInicioreal($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereSalidamax($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|OrderMonitorSchedulle whereWorkday($value)
 * @mixin \Eloquent
 */
class OrderMonitorSchedulle extends Model
{
    protected $table = 'viewmonitorscheduelle';

    protected $casts = [
        'outputdate' => 'datetime:d/m/Y H:m',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return OrderMonitorSchedulle::whereIn('centerid', $centers)->whereIn('warehouseid', $warehouses);
    }

    public function transport()
    {
        return $this->belongsTo(StorageShipmentTransport::class, 'id', 'orderid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid', 'warehouseid')
            ->select('systemwarehouses.warehouseid', 'systemwarehouses.name');
    }

    public function turn()
    {
        return $this->belongsTo(StorageTurn::class, 'turnid', 'id')
            ->select(['storageturns.id', 'storageturns.name']);
    }

    public function carrier()
    {
        return $this->belongsTo(ShipmentCarrier::class, 'carrierid', 'id')
            ->select(['shipmentcarriers.id', 'shipmentcarriers.name', 'shipmentcarriers.carrierid']);
    }

    public function comments()
    {
        return $this->hasMany(StorageComment::class, 'modelid', 'id')
            ->where('modeltype', '=', 'orderdeliveries');
    }

    public function header()
    {
        return $this->belongsTo(ShipmentHeader::class, 'id', 'orderid');
    }

    public function monitor()
    {
        return $this->belongsTo(OrderMonitor::class, 'id', 'orderid');
    }

    public function operation()
    {
        return $this->belongsTo(ShipmentDataOperation::class, 'id', 'orderid');
    }

    public function order()
    {
        return $this->belongsTo(OrderDeliverie::class, 'id', 'id');
    }

    public function customer()
    {
        return $this->belongsTo(OrderCustomer::class, 'customerid', 'id')
            ->select('ordercustomers.id', 'ordercustomers.name', 'ordercustomers.city');
    }

    public function status()
    {
        return $this->belongsTo(SystemStatus::class, 'statusid', 'statusid')
            ->select('systemstatus.statusid', 'systemstatus.name');
    }

    public function scopeShipmentid($query, $shipmentid)
    {
        if (trim($shipmentid) !== '') {
            $query->where('viewmonitorscheduelle.shipmentid', '=', $shipmentid);
        }
    }

    public function scopeForStatus($query, $status)
    {
        if (trim($status) !== '') {
            $query->where('viewmonitorscheduelle.statusid', '=', $status);
        }
    }
}
