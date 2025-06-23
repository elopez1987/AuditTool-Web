<?php

namespace App\Models\Shipments;

use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\User;
use Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShipmentSeal extends Model
{
    protected $table = 'shipmentseals';

    protected $dateFormat = 'Y-m-d H:i:s';


    protected $fillable = [
        'centerid',
        'warehouseid',
        'orderid',
        'containerseal01',
        'containerseal02',
        'containerseal03',
        'assignuser',
        'receivername',
        'statusid',
        'schemaorderid',
        'statusorderid'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    protected $attributes = [
        'statusid' => 'PE',
        'schemaid' => 'SAL',
        'schemaorderid' => 'KLE',
        'statusorderid' => 'PA',
    ];

    public static function controlAccessUser()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();
        return ShipmentSeal::whereIn('centerid', $centers)
            ->whereIn('warehouseid', $warehouses);
    }

    public function center()
    {
        return $this->belongsTo(SystemCenterDistribution::class, 'centerid');
    }

    public function warehouse()
    {
        return $this->belongsTo(SystemWarehouse::class, 'warehouseid');
    }

    public function shipment(): BelongsTo
    {
        return $this->belongsTo(OrderDeliverie::class, 'orderid', 'id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assignuser', 'id');
    }

    public function scopeForShipment($query, $shipmentid)
    {
        if (trim($shipmentid) != "") {
            $query->where('shipmentid', '=', $shipmentid);
        }
    }



}
