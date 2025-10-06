<?php

namespace App\Traits\Web\Shipments;

use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Security\Users\AccessCarrier;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageIncidenceJourney;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Collection;

trait QuerysIncidencesJourneys
{
    //    Consulta para buscar embarque que pueden registrar incidencias
    public function getShipmentsForCreateNewIncidence()
    {
        $centers = SecurityAccessWarehouse::Centers();
        $carrier = $this->getCarriers();
        $days = $this->getDays($centers);

        return OrderDeliverie::controlAccessUser()
            ->select(['id', 'shipmentid'])
            ->CarrierIn($carrier)
            ->where('statusid', '=', 'DH')
            ->where('downloaddate', '>', Carbon::now()->subDays(intval($days))->format('Y-m-d'))
            ->whereNotIn('id', (function ($query) {
                $query->from('storageincidencejourneys')
                    ->select('orderid')
                    ->where('storageincidencejourneys.statusid', '=', 'CD');
            }))->select(['id', 'shipmentid'])->get();
    }

    public function getAllIncidenceJourneys()
    {
        $carrier = $this->getCarriers();

        return StorageIncidenceJourney::controlAccessUser()
            ->leftJoin('orderdeliveries as o', function ($join) {
                $join->on('storageincidencejourneys.orderid', '=', 'o.id')
                    ->on('storageincidencejourneys.centerid', '=', 'o.centerid')
                    ->on('storageincidencejourneys.warehouseid', '=', 'o.warehouseid');
            })
            ->leftJoin('ordercustomers as c', function ($join) {
                $join->on('o.customerid', '=', 'c.id')
                    ->on('o.centerid', '=', 'c.centerid');
            })
            ->leftJoin('shipmentcarriers as sc', function ($join) {
                $join->on('o.carrierid', '=', 'sc.id')
                    ->on('o.centerid', '=', 'sc.centerid');
            })
            ->where('storageincidencejourneys.statusid', 'AB')
            ->where('o.statusid', 'DH')
            ->CarrierIn($carrier)
            ->select(['storageincidencejourneys.folioid',
                'storageincidencejourneys.statusid',
                'o.id as orderid', 'o.shipmentid',
                'c.name as customername', 'sc.name as carriername',
                'c.city', 'o.downloaddate', 'o.loadingdate',
                'storageincidencejourneys.created_at',
            ])->get();
    }

    public function getCarriers(): array
    {
        return AccessCarrier::where('username', Auth::user()->username)
            ->select('carrierid')->get()->toArray();
    }

    /**
     * @return mixed|null
     */
    public function getDays(array $centers)
    {
        return SystemDetailParameter::where('parameterid', '=', 'PARAM-DAY')
            ->whereIn('value01', $centers)
            ->value('value02');
    }

    public function getCategoriesIncidencesDetail()
    {
        return StorageCategoryIncidenceDetail::controlAccessUser()
            ->where('auxiliary01', '=', 'SHP')
            ->where('statusid', 'E')
            ->select(['description', 'categoryincdetailid'])->get();
    }

    public function getDetailIncidence($request)
    {
        return DB::table('storageincidencejourneydetails')
            ->select(
                'storageincidencejourneydetails.delivery',
                'storageincidencejourneydetails.partnumberid',
                'inventorypartnumbers.lngdsc as description',
                'storageincidencejourneydetails.quantity',
                'storageincidencejourneydetails.downloaddate',
                'storageincidencejourneydetails.unitmeasure',
                'storagecategoryincidencedetails.description as motivo'
            )
            ->leftJoin('orderdeliveries', 'orderdeliveries.id', '=', 'storageincidencejourneydetails.orderid')
            ->leftJoin('inventorypartnumbers', function ($join) {
                $join->on('storageincidencejourneydetails.partnumberid', '=', 'inventorypartnumbers.prtnum')
                    ->on('storageincidencejourneydetails.centerid', '=', 'inventorypartnumbers.centerid')
                    ->on('storageincidencejourneydetails.warehouseid', '=', 'inventorypartnumbers.warehouseid');
            })
            ->leftJoin('storagecategoryincidencedetails', function ($join) {
                $join->on('storageincidencejourneydetails.razoncode', '=', 'storagecategoryincidencedetails.categoryincdetailid')
                    ->on('storageincidencejourneydetails.centerid', '=', 'storagecategoryincidencedetails.centerid');
            })
            ->where('storageincidencejourneydetails.orderid', '=', $request->get('shipmentid'))
            ->get();
    }

    public function getDetailsForGeneratePDF($incidence): Collection
    {
        return DB::table('storageincidencejourneys')
            ->select(
                'storageincidencejourneys.centerid',
                'storageincidencejourneys.warehouseid',
                'storageincidencejourneys.folioid',
                'storageincidencejourneydetails.delivery',
                'storageincidencejourneydetails.partnumberid',
                'inventorypartnumbers.lngdsc as description',
                'storageincidencejourneydetails.quantity',
                'storageincidencejourneydetails.unitmeasure',
                'storagecategoryincidencedetails.description as code',
                'storagecategoryincidencedetails.auxiliary02 as type',
                'storageincidencejourneydetails.partnumberiderror'
            )
            ->leftJoin('storageincidencejourneydetails', function ($join) {
                $join->on('storageincidencejourneys.orderid', '=', 'storageincidencejourneydetails.orderid')
                    ->on('storageincidencejourneys.centerid', '=', 'storageincidencejourneydetails.centerid');
            })
            ->leftJoin('storagecategoryincidencedetails', function ($join) {
                $join->on('storageincidencejourneydetails.razoncode', '=', 'storagecategoryincidencedetails.categoryincdetailid')
                    ->on('storageincidencejourneydetails.centerid', '=', 'storagecategoryincidencedetails.centerid');
            })
            ->leftJoin('inventorypartnumbers', function ($join) {
                $join->on('storageincidencejourneydetails.partnumberid', '=', 'inventorypartnumbers.prtnum')
                    ->on('storageincidencejourneydetails.centerid', '=', 'inventorypartnumbers.centerid');
            })
            ->where('storageincidencejourneys.orderid', '=', $incidence->orderid)
            ->get();
    }
}
