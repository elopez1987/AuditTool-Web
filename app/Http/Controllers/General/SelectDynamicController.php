<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\TablesDataBase\SystemColumn;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Security\Fuctions\SecurityFunction;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentContainer;
use App\Models\Shipments\StorageShipmentBilling;
use App\Models\Yard\StorageLabelTruck\StorageLabelTruck;
use App\Traits\Web\General\GetParameters;
use Carbon\Carbon;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SelectDynamicController extends Controller
{
    use GetParameters;

    public function __construct()
    {
        $this->middleware('auth');
    }

    //	---------------------------

    public function getDataTruck(Request $request, $barcodetruck)
    {
        if ($request->ajax()) {
            $dataTruck = StorageLabelTruck::whereBarcode($barcodetruck)
                ->select(['boxnumber', 'econumber'])
                ->whereTypeid('TR')
                ->get()->toArray();
        }

        return response()->json($dataTruck);
    }

    public function getDataSku($prtnum)
    {
        $sku = InventoryPartnumber::wherePrtnum($prtnum)
            ->select(['lngdsc', 'untpal', 'prtnum'])
            ->get()->toArray();

        return response()->json($sku);
    }

    public function getBillForShipment(Request $request, $shipmentid)
    {
        if ($request->ajax()) {
            $bills = StorageShipmentBilling::where('orderid', $shipmentid)
                ->select(['billingdocument', 'billingdocument'])->get()->toArray();
            array_unshift($bills, ['billingdocument' => '', 'billingdocument' => ' ']);

            return response()->json($bills);
        }
    }

    public function getIncidenceDetail(Request $request, $categoryincid)
    {
        if ($request->ajax()) {
            $details = StorageCategoryIncidenceDetail::where('categoryincid', $categoryincid)
                ->where('statusid', 'E')
                ->select(['categoryincdetailid as id', 'description as name'])->get()->toArray();
            array_unshift($details, ['id' => '', 'name' => 'Seleccione una opción']);
        }

        return response()->json($details);
    }

    public function getColumnForTable($table)
    {
        $columns = SystemColumn::where('tableid', $table)->select(['columnid'])->get()->toArray();
        array_unshift($columns, ['columnid' => '', 'columnid' => 'Seleccione una opción']);

        return response()->json($columns);
    }

    public function getPrinters()
    {
        $printers = SystemStatus::whereSchemaid('PRT')
            ->select(['statusid', 'name'])->get();

        return response()->json($printers);
    }

    public function getDataForShipment($shipmentid)
    {
        $shipment = OrderDeliverie::controlAccessUser()
            ->leftJoin('ordercustomers', 'orderdeliveries.customerid', '=', 'ordercustomers.id')
            ->where('orderdeliveries.id', $shipmentid)
            ->select(['orderdeliveries.downloaddate', 'ordercustomers.city', 'ordercustomers.name'])
            ->get()->toArray();

        return response()->json($shipment);
    }

    public function getLpnForShipment(Request $request, $shipmentid)
    {
        if ($request->ajax()) {
            $lpns = ShipmentContainer::controlAccessUser()
                ->where('orderid', $shipmentid)
                ->select(['lpnid'])->distinct()->get()->toArray();
            array_unshift($lpns, ['id' => '', 'lpnid' => 'Seleccione un lpn.']);
        }

        return response()->json($lpns);
    }

    public function getSkuForLpn(Request $request, $lpnid)
    {
        if ($request->ajax()) {
            $partnumbers = DB::table('shipmentstock')->select(
                'shipmentstock.partnumberid',
                DB::raw("CONCAT(i.prtnum, '-', i.lngdsc) as description"),
            )
                ->leftJoin('inventorypartnumbers as i', 'shipmentstock.partnumberid', '=', 'i.id')
                ->where('lpnid', $lpnid)
                ->groupBy('lpnid', 'i.prtnum', 'i.lngdsc', 'shipmentstock.partnumberid')
                ->get()->toArray();
            array_unshift($partnumbers, ['partnumberid' => '', 'description' => 'Seleccione un Sku.']);
        }

        return response()->json($partnumbers);
    }

    public function getTotalForSku(Request $request)
    {
        if ($request->ajax()) {
            $total = DB::table('shipmentstock')->select(
                'shipmentstock.lpnid',
                'shipmentstock.partnumberid',
                DB::raw("CONCAT(i.prtnum, '-', i.lngdsc) as description"),
                DB::raw('SUM(quantity) as total'),
            )
                ->leftJoin('inventorypartnumbers as i', 'shipmentstock.partnumberid', '=', 'i.id')
                ->where('lpnid', $request['lpnid'])
                ->where('shipmentstock.partnumberid', $request['partnumberid'])
                ->groupBy('lpnid', 'i.prtnum', 'i.lngdsc', 'shipmentstock.partnumberid')
                ->get()->toArray();
        }

        return response()->json($total);
    }

    public function getJsonResponse($message, $data, $code, $codeResponse, $servicename): JsonResponse
    {
        return response()->json([
            'ServiceName' => $servicename,
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Message' => $message,
            'CodeResponse' => $codeResponse,
            'Data' => $data,
        ], $code);
    }

    public function getFunctionForModule($moduleid)
    {
        return SecurityFunction::where('moduleid', $moduleid)
            ->select('description', 'functionid')->get();
    }
}
