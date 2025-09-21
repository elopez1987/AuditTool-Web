<?php

namespace App\Models\Shipments;

use App\Models\Security\Users\AccessCarrier;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;

class MonitorJourneysTransport extends Model
{
    public $timestamps = false;
    protected $table = 'vw_monitorincidencejourneys';

    public static function controlAccessUser()
    {
        $warehouses = SecurityAccessWarehouse::Warehouses();
        $centers = SecurityAccessWarehouse::Centers();
        return MonitorJourneysTransport::whereIn('vw_monitorincidencejourneys.warehouseid', $warehouses)
            ->whereIn('vw_monitorincidencejourneys.centerid', $centers);
    }

    public function scopeForShipment($query, $shipmentid)
    {
        if (trim($shipmentid) !== '') {
            $query->where('vw_monitorincidencejourneys.shipmentid', '=', $shipmentid);
        }
    }

    public function scopeForCarriers($query)
    {
        $carriers = $this->getCarriers();
        if (! empty($carriers)) {
            $query->whereIn('carrierid', $carriers);
        }
    }

    public function getCarriers(): array
    {
        return AccessCarrier::where('username', Auth::user()->username)
            ->select('carrierid')->get()->toArray();
    }
}
