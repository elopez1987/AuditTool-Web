<?php

namespace App\Models\Shipments;

use App\Models\Security\Users\AccessCarrier;
use App\Models\Security\Users\SecurityAccessWarehouse;
use Auth;
use Illuminate\Database\Eloquent\Model;

/**
 * @property string $centerid
 * @property string $warehouseid
 * @property int $folioid
 * @property string|null $shipmentid
 * @property int $carrierid
 * @property string|null $carrier
 * @property string|null $customer
 * @property string|null $city
 * @property string|null $download
 * @property string|null $created
 * @property string|null $status
 * @property string $statuspod
 * @property string $statusid
 * @property string|null $statuskeyid
 * @property string|null $statuskeytrid
 * @property string|null $statusdevid
 * @property string|null $statusdev
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport forCarriers()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport forShipment($shipmentid)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereCarrier($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereCarrierid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereCenterid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereCity($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereCreated($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereCustomer($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereDownload($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereFolioid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereShipmentid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereStatusdev($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereStatusdevid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereStatusid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereStatuskeyid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereStatuskeytrid($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereStatuspod($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|MonitorJourneysTransport whereWarehouseid($value)
 *
 * @mixin \Eloquent
 */
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
