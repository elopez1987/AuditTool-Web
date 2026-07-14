<?php

namespace App\Traits\Web\Shipments;

use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Security\Users\AccessCarrier;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Shipments\OrderDeliverie;
use Auth;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

trait QuerysIncidencesJourneys
{
    //    Consulta para buscar embarque que pueden registrar incidencias
    public function getShipmentsForCreateNewIncidence()
    {
        $carrier = $this->getCarriers();

        return OrderDeliverie::controlAccessUser()
            ->select(['id', 'shipmentid'])
            ->CarrierIn($carrier)
            ->where('statusid', '=', 'DH')
            ->whereNotIn('id', (function ($query) {
                $query->from('storageincidencejourneys')
                    ->select('orderid')
                    ->where('storageincidencejourneys.statusid', '=', 'CD');
            }))->select(['id', 'shipmentid'])->get();
    }

    public function getAllIncidenceJourneys(Request $request)
    {
        $carriers = $this->getCarriers();
        $centers = SecurityAccessWarehouse::Centers();
        $warehouses = SecurityAccessWarehouse::Warehouses();

        return DB::table('storageincidencejourneys')
            ->select([
                'storageincidencejourneys.folioid',
                'storageincidencejourneys.statusid',
                'orderdeliveries.id as orderid',
                'orderdeliveries.shipmentid',
                'c.name as customername',
                'sc.name as carriername',
                'c.city',
                'orderdeliveries.downloaddate',
                'orderdeliveries.loadingdate',
                'storageincidencejourneys.created_at',
            ])
            ->join('orderdeliveries', function ($join) {
                $join->on('storageincidencejourneys.orderid', '=', 'orderdeliveries.id')
                    ->on('storageincidencejourneys.centerid', '=', 'orderdeliveries.centerid')
                    ->on('storageincidencejourneys.warehouseid', '=', 'orderdeliveries.warehouseid');
            })
            ->leftJoin('ordercustomers as c', function ($join) {
                $join->on('orderdeliveries.customerid', '=', 'c.id')
                    ->on('orderdeliveries.centerid', '=', 'c.centerid');
            })
            ->leftJoin('shipmentcarriers as sc', function ($join) {
                $join->on('orderdeliveries.carrierid', '=', 'sc.id')
                    ->on('orderdeliveries.centerid', '=', 'sc.centerid');
            })
            ->whereIn('orderdeliveries.centerid', $centers)
            ->whereIn('orderdeliveries.warehouseid', $warehouses)
            ->where('storageincidencejourneys.statusid', 'AB')
            ->where('orderdeliveries.statusid', 'DH')
            ->when(! empty($carriers), function ($q) {
                $q->whereIn('orderdeliveries.carrierid', $this->getCarriers());
            })
            ->when($request->filled('shipmentid'), function ($q) use ($request) {
                $q->where('orderdeliveries.shipmentid', $request->input('shipmentid'));
            });
    }

    public function getCarriers(): array
    {
        return AccessCarrier::where('username', Auth::user()->username)
            ->pluck('securityaccesscarriers.carrierid')
            ->toArray();
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
                'storagecategoryincidencedetails.description as motivo',
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
                'storageincidencejourneydetails.partnumberiderror',
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
