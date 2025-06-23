<?php

namespace App\Models\Shipments;

use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Auth;
use Illuminate\Database\Eloquent\Model;

class ShipmentIncidenceCheck extends Model
{

    protected $table = 'shipmentincidencechecks';

    protected $fillable = [
        'centerid', 'warehouseid', 'orderid',
        'partnumberid', 'quantity', 'lpnid',
        'createuser', 'validateuser',
        'value01', 'value02', 'value03',
    ];

    static function createNewIncidence(OrderDeliverie $shipment, $sku, $lpnid): void
    {
        ShipmentIncidenceCheck::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'orderid' => $shipment->id,
            'partnumberid' => $sku->id,
            'createuser' => Auth::user()->username,
            'lpnid' => $lpnid,
        ]);
    }

    public function partnumber()
    {
        return $this->belongsTo(InventoryPartnumber::class, 'partnumberid', 'id');
    }


}
