<?php

namespace App\Models\Yard;

use App\Models\Security\Users\SecurityAccessWarehouse;
use Illuminate\Database\Eloquent\Model;

class ReceiptUnitStock extends Model
{
    protected $table = 'vw_storagestockunits';
    public $timestamps = false;

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return ReceiptUnitStock::whereIn('vw_storagestockunits.warehouseid', $warehouses)
            ->whereIn('vw_storagestockunits.centerid', $centers);
    }

    public function scopeFolio($query, $folionumber)
    {
        if (trim($folionumber) != "") {
            $query->where('vw_storagestockunits.id', '=', $folionumber);
        }
    }

    public function scopeBarcode($query, $barcode)
    {
        if (trim($barcode) != "") {
            $query->where('vw_storagestockunits.barcode', 'like', "%$barcode%");
        }
    }

    public function scopeLocation($query, $location)
    {
        if (trim($location) != "") {
            $query->where('vw_storagestockunits.location', 'like', "%$location%");
        }
    }

    public function scopeBoxNumber($query, $boxnumber)
    {
        if (trim($boxnumber) != '') {
            $query->where('vw_storagestockunits.boxnumber', 'like', "%$boxnumber%");
        }
    }

    public function scopeEcoNumber($query, $econumber)
    {
        if (trim($econumber) != '') {
            $query->where('vw_storagestockunits.econumber', 'like', "%$econumber%");
        }
    }

    public function scopeTruckNumber($query, $trucknumber)
    {
        if (trim($trucknumber) != '') {
            $query->where('vw_storagestockunits.trucknumber', 'like', "%$trucknumber%");
        }
    }

    public function scopeShipment($query, $shipmentid)
    {
        if (trim($shipmentid) != '') {
            $query->where('vw_storagestockunits.shipmentid', 'like', "%$shipmentid%");
        }
    }

    public function scopeRason($query, $rasonid)
    {
        if (trim($rasonid) != "") {
            $query->where('vw_storagestockunits.rasonid', '=', $rasonid);
        }
    }

    public function scopeCarrier($query, $carrierid)
    {
        if (trim($carrierid) != "") {
            $query->where('vw_storagestockunits.carrierid', '=', $carrierid);
        }
    }

    public function scopeWarehouse($query, $warehouseid)
    {
        if (trim($warehouseid) != "") {
            $query->where('vw_storagestockunits.warehouseid', '=', $warehouseid);
        }
    }

    public function scopeForStatus($query, $statusid)
    {
        if (trim($statusid) != '') {
            $query->where('vw_storagestockunits.statusid', '=', $statusid);
        }
    }

}
