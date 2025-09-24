<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $centerid
 * @property string $warehouseid
 * @property int $id
 * @property string|null $warehouse
 * @property string|null $shipmentid
 * @property string|null $boxnumber
 * @property string|null $econumber
 * @property string|null $trucknumber
 * @property string|null $operatorname
 * @property string|null $phone
 * @property string|null $drivernumber
 * @property string|null $ecotruck
 * @property string|null $barcode
 * @property string|null $auxiliary02
 * @property string|null $auxiliary03
 * @property string $statusid
 * @property string $entrydate
 * @property int|null $carrierid
 * @property string|null $carrier
 * @property string|null $zone
 * @property string|null $location
 * @property string|null $unitstatus
 * @property string|null $status
 * @property string|null $truckcategory
 * @property string|null $truck
 * @property int $rasonid
 * @property string $reason
 * @property string|null $origen
 * @property string|null $ticket
 * @property string|null $weightin
 * @property string|null $weightout
 * @property int|null $foliotruck
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock barcode($barcode)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock boxNumber($boxnumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock carrier($carrierid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock ecoNumber($econumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock folio($folionumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock forStatus($statusid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock location($location)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock rason($rasonid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock shipment($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock truckNumber($trucknumber)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock warehouse($warehouseid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereAuxiliary02($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereAuxiliary03($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereBarcode($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereBoxnumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereCarrier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereDrivernumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereEconumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereEcotruck($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereEntrydate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereFoliotruck($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereLocation($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereOperatorname($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereOrigen($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereRasonid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereTicket($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereTruck($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereTruckcategory($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereTrucknumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereUnitstatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereWarehouse($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereWarehouseid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereWeightin($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereWeightout($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|ReceiptUnitStock whereZone($value)
 * @mixin \Eloquent
 */
class ReceiptUnitStock extends Model
{
    public $timestamps = false;
    protected $table = 'vw_storagestockunits';

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ReceiptUnitStock::whereIn('vw_storagestockunits.warehouseid', $warehouses)
            ->whereIn('vw_storagestockunits.centerid', $centers);
    }

    public function scopeFolio($query, $folionumber)
    {
        if (trim($folionumber) !== '') {
            $query->where('vw_storagestockunits.id', '=', $folionumber);
        }
    }

    public function scopeBarcode($query, $barcode)
    {
        if (trim($barcode) !== '') {
            $query->where('vw_storagestockunits.barcode', 'like', "%{$barcode}%");
        }
    }

    public function scopeLocation($query, $location)
    {
        if (trim($location) !== '') {
            $query->where('vw_storagestockunits.location', 'like', "%{$location}%");
        }
    }

    public function scopeBoxNumber($query, $boxnumber)
    {
        if (trim($boxnumber) !== '') {
            $query->where('vw_storagestockunits.boxnumber', 'like', "%{$boxnumber}%");
        }
    }

    public function scopeEcoNumber($query, $econumber)
    {
        if (trim($econumber) !== '') {
            $query->where('vw_storagestockunits.econumber', 'like', "%{$econumber}%");
        }
    }

    public function scopeTruckNumber($query, $trucknumber)
    {
        if (trim($trucknumber) !== '') {
            $query->where('vw_storagestockunits.trucknumber', 'like', "%{$trucknumber}%");
        }
    }

    public function scopeShipment($query, $shipmentid)
    {
        if (trim($shipmentid) !== '') {
            $query->where('vw_storagestockunits.shipmentid', 'like', "%{$shipmentid}%");
        }
    }

    public function scopeRason($query, $rasonid)
    {
        if (trim($rasonid) !== '') {
            $query->where('vw_storagestockunits.rasonid', '=', $rasonid);
        }
    }

    public function scopeCarrier($query, $carrierid)
    {
        if (trim($carrierid) !== '') {
            $query->where('vw_storagestockunits.carrierid', '=', $carrierid);
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if (trim($warehouseid) !== '') {
            $query->where('vw_storagestockunits.warehouseid', '=', $warehouseid);
        }
    }

    public function scopeForStatus($query, $statusid)
    {
        if (trim($statusid) !== '') {
            $query->where('vw_storagestockunits.statusid', '=', $statusid);
        }
    }
}
