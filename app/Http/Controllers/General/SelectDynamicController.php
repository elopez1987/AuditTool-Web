<?php

namespace App\Http\Controllers\General;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Configuration\TablesDataBase\SystemColumn;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\ReceiptProvider;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Security\Fuctions\SecurityFunction;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentContainer;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Models\Shipments\StorageShipmentBilling;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\GenerateAccessWarehouse;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitStock;
use App\Models\Yard\ShipmentTruck\ShipmentTruck;
use App\Models\Yard\StorageLabelTruck\StorageLabelTruck;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageOperator;
use App\Models\Yard\StorageReason;
use App\Models\Yard\StorageUnitStatus;
use App\Models\Yard\StorageZone\StorageZone;
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

    public function getWarehouseForCenter(Request $request, $centerid)
    {
        if ($request->ajax()) {
            $warehouses = SystemWarehouse::where('centerid', $centerid)
                ->whereStatusid('E')
                ->select(['warehouseid', 'name'])->get();
        }
        return response()->json($warehouses);
    }

    public function getZoneForCenter(Request $request, $centerid)
    {
        if ($request->ajax()) {
            $zones = StorageZone::controlAccessUser()
                ->whereStatusid('E')
                ->where('centerid', $centerid)
                ->select(['id', 'name'])->get();
        }
        return response()->json($zones);
    }

    public function getZoneForWarehouse(Request $request, $warehouseid)
    {
        if ($request->ajax()) {
            $zones = StorageZone::controlAccessUser()
                ->whereStatusid('E')
                ->where('warehouseid', $warehouseid)
                ->select(['id', 'name'])->get();
        }
       return $zones;
    }


    public function getCarriersForCenter(Request $request, $centerid)
    {
        if ($request->ajax()) {
            $carriers = ShipmentCarrier::controlAccessUser()
                ->whereStatusid('E')
                ->where('centerid', $centerid)
                ->select(['id', 'name'])->get();
        }
        return response()->json($carriers);
    }

    public function getStatusBoxUnit(Request $request, $razonstatusid)
    {
        if ($request->ajax()) {
            $statusunit = StorageUnitStatus::where('auxiliary01', $razonstatusid)
                ->whereStatusid('E')
                ->select(['id', 'name'])->get();
        }
        return response()->json($statusunit);
    }

    public function getStatusBoxUnitAll(Request $request)
    {
        if ($request->ajax()) {
            $statusunit = StorageUnitStatus::select(['id', 'name'])
                ->whereStatusid('E')->get();
        }
        return response()->json($statusunit);
    }

    public function getTrucksForCenter(Request $request, $centerid)
    {
        if ($request->ajax()) {
            $trucks = ShipmentTruck::controlAccessUser()
                ->whereStatusid('E')
                ->where('centerid', $centerid)
                ->select(['id', 'name'])->get();
        }
        return response()->json($trucks);
    }

    public function getRasonsForCenter(Request $request)
    {
        if ($request->ajax()) {
            $rasons = StorageReason::whereType('IN')
                ->select(['id', 'name'])->get();
        }
        return response()->json($rasons);
    }

    public function getUnitStatusForCenter(Request $request)
    {
        if ($request->ajax()) {
            $unitsstatus = StorageUnitStatus::select(['id', 'name'])
                ->whereStatusid('E')->get();
        }
        return response()->json($unitsstatus);
    }

    public function getShipmentTruck($truckid)
    {
        $truck = ShipmentTruck::controlAccessUser()->where('id', $truckid)
            ->select(['id', 'name', 'auxiliary01'])->first();
        return response()->json($truck);
    }

    public function getLocationForZone(Request $request, $zoneid)
    {
        if ($request->ajax()) {
            $locations = StorageLocation::controlAccessUser()
                ->where('zoneid', $zoneid)
                ->where('statusid', 'E')
                ->whereNotIn('name', ['DUMMY'])
                ->select(['id', 'name'])->get();
        }
        return response()->json($locations);
    }

    public function getLocationForZoneUN(Request $request, $zoneid)
    {
        if ($request->ajax()) {
            $locations = StorageLocation::controlAccessUser()
                ->where('zoneid', $zoneid)
                ->whereNotIn('statusid', ['B'])
                ->whereNotIn('name', ['DUMMY'])
                ->select(['id', 'name'])->get();
        }
        return response()->json($locations);
    }

    public function getDataOperator(Request $request, $barcode)
    {
        if ($request->ajax()) {
            $data = StorageOperator::whereBarcode($barcode)
                ->whereStatusid('E')
                ->select(['name', 'drivernumber', 'phone'])->get();
        }
        return response()->json($data);
    }

    public function getDataProviders(Request $request, $warehouseid)
    {
        if ($request->ajax()) {
            $data = ReceiptProvider::controlAccessUser()
                ->whereWarehouseid($warehouseid)
                ->whereStatusid('E')
                ->select(['id', 'name'])->get();
        }
        return response()->json($data);
    }

    public function getDataInvoice(Request $request, $barcode)
    {
        if ($request->ajax()) {
            $dataUnit = ReceiptUnit::controlAccessUser()
                ->where('receiptunits.barcode', $barcode)
                ->join('storagezones', 'storagezones.id', '=', 'receiptunits.zoneid')
                ->join('storagelocations', 'storagelocations.id', '=', 'receiptunits.locationid')
                ->join('shipmentcarriers', 'shipmentcarriers.id', '=', 'receiptunits.carrierid')
                ->join('systemstatus', 'receiptunits.statusid', '=', 'systemstatus.statusid')
                ->join('storagereasons', 'storagereasons.id', '=', 'receiptunits.razonstatusid')
                ->join('storageunitstatus', 'receiptunits.unitstatusid', '=', 'storageunitstatus.id')
                ->select(['storagelocations.name as ubicacion', 'storagezones.name as zona', 'receiptunits.econumber',
                    'receiptunits.boxnumber', 'receiptunits.containerseal', 'receiptunits.id as folio', 'receiptunits.trucknumber',
                    'receiptunits.operatorname', 'receiptunits.phone', 'receiptunits.shipmentid', 'receiptunits.auxiliary01 as barcodetruck',
                    'shipmentcarriers.name as linea', 'systemstatus.name as status', 'storagereasons.name as razonid',
                    'receiptunits.operatorname', 'storageunitstatus.name as statusunit'
                ])
                ->whereNotIn('receiptunits.statusid', ['TR'])
                ->get()->toArray();
        }
        return response()->json($dataUnit);
    }

    public function getDataInvoiceForFolio(Request $request, $folionumber)
    {
        if ($request->ajax()) {
                $dataUnit = ReceiptUnitStock::controlAccessUser()->where('vw_storagestockunits.id', $folionumber)
                ->select(['vw_storagestockunits.location as ubicacion', 'vw_storagestockunits.zone as zona', 'vw_storagestockunits.econumber',
                    'vw_storagestockunits.boxnumber', 'vw_storagestockunits.id as folio', 'vw_storagestockunits.trucknumber',
                    'vw_storagestockunits.operatorname', 'vw_storagestockunits.phone', 'vw_storagestockunits.shipmentid',
                    'vw_storagestockunits.carrier as linea', 'vw_storagestockunits.status as status', 'vw_storagestockunits.reason as razonid',
                         'vw_storagestockunits.unitstatus as statusunit','vw_storagestockunits.barcode','vw_storagestockunits.foliotruck'
                ])->get()->toArray();
        }
        return response()->json($dataUnit);
    }



    public function getDataInvoiceForBox(Request $request, $boxnumber)
    {
        if ($request->ajax()) {
            $dataUnitBox = ReceiptUnit::controlAccessUser()
                ->where('receiptunits.boxnumber', $boxnumber)
                ->join('storagezones', 'storagezones.id', '=', 'receiptunits.zoneid')
                ->join('storagelocations', 'storagelocations.id', '=', 'receiptunits.locationid')
                ->join('shipmentcarriers', 'shipmentcarriers.id', '=', 'receiptunits.carrierid')
                ->select(['storagelocations.name as ubicacion', 'storagezones.name as zona',
                    'receiptunits.boxnumber', 'receiptunits.containerseal', 'receiptunits.id as folio',
                    'receiptunits.operatorname', 'receiptunits.phone', 'receiptunits.shipmentid', 'receiptunits.econumber',
                    'receiptunits.barcode', 'shipmentcarriers.name as linea'])
                ->whereNotIn('receiptunits.statusid', ['TR'])
                ->get()->toArray();
        }
        return response()->json($dataUnitBox);
    }

    public function getDataBox(Request $request, $barcodebox)
    {
        if ($request->ajax()) {
            $dataBox = StorageLabelTruck::whereBarcode($barcodebox)
                ->select(['boxnumber', 'econumber'])
                ->whereTypeid('BX')
                ->get()->toArray();
        }
        return response()->json($dataBox);
    }

    public function getDataBoxNumber(Request $request, $boxnumber)
    {
        if ($request->ajax()) {
            $dataBox = StorageLabelTruck::whereBoxnumber($boxnumber)
                ->select(['barcode', 'econumber'])
                ->whereTypeid('BX')
                ->get()->toArray();
        }
        return response()->json($dataBox);
    }

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

    public function getDataTruckNumber(Request $request, $trucknumber)
    {
        if ($request->ajax()) {
            $dataTruck = StorageLabelTruck::whereBoxnumber($trucknumber)
                ->select(['barcode as barcodetruck', 'econumber'])
                ->whereTypeid('TR')
                ->get()->toArray();
        }
        return response()->json($dataTruck);
    }

    public function getCarrierForBox(Request $request, $barcodebox)
    {
        if ($request->ajax()) {
            $carrierForBox = StorageLabelTruck::whereBarcode($barcodebox)
                ->join('shipmentcarriers', 'storagelabeltrucks.carrierid', '=', 'shipmentcarriers.id')
                ->select(['shipmentcarriers.id', 'shipmentcarriers.name'])
                ->get()->toArray();
        }
        return response()->json($carrierForBox);
    }

    public function getCarrierForBoxNumber(Request $request, $boxnumber)
    {
        if ($request->ajax()) {
            $carrierForBox = StorageLabelTruck::whereBoxnumber($boxnumber)
                ->join('shipmentcarriers', 'storagelabeltrucks.carrierid', '=', 'shipmentcarriers.id')
                ->select(['shipmentcarriers.id', 'shipmentcarriers.name'])
                ->get()->toArray();
        }
        return response()->json($carrierForBox);
    }

    public function getTruckForBox(Request $request, $barcodebox)
    {
        if ($request->ajax()) {
            $truckForBox = StorageLabelTruck::whereBarcode($barcodebox)
                ->join('shipmenttrucks', 'storagelabeltrucks.truckid', '=', 'shipmenttrucks.id')
                ->select(['shipmenttrucks.id', 'shipmenttrucks.name'])
                ->get()->toArray();
        }
        return response()->json($truckForBox);
    }

    public function getTruckForBoxNumber(Request $request, $boxnumber)
    {
        if ($request->ajax()) {
            $truckForBox = StorageLabelTruck::whereBoxnumber($boxnumber)
                ->join('shipmenttrucks', 'storagelabeltrucks.truckid', '=', 'shipmenttrucks.id')
                ->select(['shipmenttrucks.id', 'shipmenttrucks.name'])
                ->get()->toArray();
        }
        return response()->json($truckForBox);
    }

    public function getTypeUnitForBox(Request $request, $barcodebox)
    {
        if ($request->ajax()) {
            $typeUnitForBox = StorageLabelTruck::whereBarcode($barcodebox)
                ->join('storageunittypes', 'storagelabeltrucks.unittypeid', '=', 'storageunittypes.id')
                ->select(['storageunittypes.id', 'storageunittypes.description'])
                ->get()->toArray();
        }
        return response()->json($typeUnitForBox);
    }

    public function getTypeUnitForBoxNumber(Request $request, $boxnumber)
    {
        if ($request->ajax()) {
            $typeUnitForBox = StorageLabelTruck::whereBoxnumber($boxnumber)
                ->join('storageunittypes', 'storagelabeltrucks.unittypeid', '=', 'storageunittypes.id')
                ->select(['storageunittypes.id', 'storageunittypes.description'])
                ->get()->toArray();
        }
        return response()->json($typeUnitForBox);
    }

    public function getDataSku($prtnum)
    {
        $sku = InventoryPartnumber::wherePrtnum($prtnum)
            ->select(['lngdsc', 'untpal', 'prtnum'])
            ->get()->toArray();
        return response()->json($sku);
    }


    public function getDataShipment(Request $request, $shipmentid)
    {
        if ($request->ajax()) {
            $shipment = OrderDeliverie::whereShipmentid($shipmentid)
                ->join('ordercustomers', 'orderdeliveries.customerid', '=', 'ordercustomers.id')
                ->join('shipmenttransport', 'orderdeliveries.id', '=', 'shipmenttransport.orderid')
                ->join('shipmentcarriers', 'shipmenttransport.carrierid', 'shipmentcarriers.id')
                ->join('systemwarehouses', 'systemwarehouses.warehouseid', '=', 'orderdeliveries.warehouseid')
                ->select(['shipmenttransport.operatorname', 'shipmenttransport.auxiliary01',
                    'shipmentcarriers.name as carriername', 'systemwarehouses.name as warehousename'])
                ->get()->toArray();
        }
        return response()->json($shipment);
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


    public function getstageforshipment($shipmentid)
    {
        $shipment01 = OrderDeliverie::whereShipmentid($shipmentid)
            ->leftJoin('shipmentstages', 'orderdeliveries.id', '=', 'shipmentstages.orderid')
            ->leftJoin('storagelocations as s1', 'shipmentstages.value01', '=', 's1.id')
            ->where('s1.statusid', '=', 'E')
            ->select(['s1.name as cortina', 's1.id as locationid'])
            ->first();
        if ($shipment01 != null) {
            $value = $shipment01;
        } else {
            $shipment02 = OrderDeliverie::whereShipmentid($shipmentid)
                ->leftJoin('shipmentstages', 'orderdeliveries.id', '=', 'shipmentstages.orderid')
                ->leftJoin('storagelocations as s1', 'shipmentstages.value02', '=', 's1.id')
                ->where('s1.statusid', '=', 'E')
                ->select(['s1.name as cortina', 's1.id as locationid'])
                ->first();
            if ($shipment02 != null) {
                $value = $shipment02;
            } else {
                $shipment03 = OrderDeliverie::whereShipmentid($shipmentid)
                    ->leftJoin('shipmentstages', 'orderdeliveries.id', '=', 'shipmentstages.orderid')
                    ->leftJoin('storagelocations as s1', 'shipmentstages.value03', '=', 's1.id')
                    ->where('s1.statusid', '=', 'E')
                    ->select(['s1.name as cortina', 's1.id as locationid'])
                    ->first();
                if ($shipment03 != null) {
                    $value = $shipment03;
                } else {
                    $value = ['cortina' => 'SIN ASIGNAR', "locationid" => "0"];
                }
            }
        }
        return response()->json($value);
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
                DB::raw('SUM(quantity) as total')
            )
                ->leftJoin('inventorypartnumbers as i', 'shipmentstock.partnumberid', '=', 'i.id')
                ->where('lpnid', $request['lpnid'])
                ->where('shipmentstock.partnumberid', $request['partnumberid'])
                ->groupBy('lpnid', 'i.prtnum', 'i.lngdsc', 'shipmentstock.partnumberid')
                ->get()->toArray();
        }
        return response()->json($total);
    }


    /**
     * @param $message
     * @param $data
     * @param $code
     * @return JsonResponse
     */
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


    public function getDataToShipment($barcodebox)
    {
        $data = explode(";", $barcodebox);
        if (count($data) < 2) {
            return $this->getJsonResponse('Ingrese un codigo valido.', [], '200', 'error', 'getDataToShipment');
        }
        $shipmentqr = $data[0];
        $code = $data[1];
        $shipment = OrderDeliverie::controlAccessUser()->where('shipmentid', $shipmentqr)
            ->where('auxiliary05', '=', $code)
            ->whereNotIn('statusid', ['DH'])->first();
        if (empty($shipment)) {
            return $this->getJsonResponse('No existe número de embarque.', [], '200', 'error', 'getDataToShipment');
        } else {
            $dataTransport = StorageShipmentTransport::where('orderid', $shipment->id)->first();
            if ($dataTransport->auxiliary01 == 1) {
                return $this->getJsonResponse('El embarque ya cuenta con transporte,favor de validar.', [], '200', 'error', 'getDataToShipment');
            } else {
                if ($dataTransport->boxnumber == null || $dataTransport->operatorname == null) {
                    return $this->getJsonResponse('El embarque no cuenta con datos,favor de validar.', [], '200', 'error', 'getDataToShipment');
                } else {
                    return $this->getJsonResponse('Datos encontrados :',
                        [
                            'operador' => $dataTransport->operatorname,
                            'boxnumber' => $dataTransport->boxnumber,
                            'trucknumber' => $dataTransport->trucknumber
                        ], '200', 'success', 'getDataToShipment');
                }
            }
        }
    }

    public function getDataReceiptQr($barcodebox)
    {
        $data = explode(",", $barcodebox);
        if (count($data) < 4) {
            return $this->getJsonResponse('Ingrese un codigo valido', [], '200', 'error', 'getDataReceiptQr');
        }
        $key = $data[0];
        $operator = $data[1];
        $box = $data[2];
        $truck = $data[3];
        $keyValue = GenerateAccessWarehouse::where('id', $key)->first();

        if (empty($keyValue)) {
            return $this->getJsonResponse('No existe el código de acceso, favor de validar', [], '200', 'error', 'getDataReceiptQr');
        }
        if ($keyValue->statusid == 'BK') {
            return $this->getJsonResponse('El códgio de acceso ya se encuentra caduco', [], '200', 'error', 'getDataReceiptQr');
        }

        $boxstatus = $this->getStatusBoxForBarcode($box);
        if (!empty($boxstatus)) {
            return $this->getJsonResponse('La caja se encuentra en patio', [], '200', 'error', 'getDataReceiptQr');
        }
        $truckStatus = $this->getStatusTruckForBarcode($truck);
        if (!empty($truckStatus)) {
            return $this->getJsonResponse('El tracto se encuentra en patio', [], '200', 'error', 'getDataReceiptQr');
        }

        $newOperador = $this->getOperator($operator);
        $newBox = $this->getBox($box);
        $newTruck = $this->getTruck($truck);
        return $this->getJsonResponse('Datos existentes', ['operador' => $newOperador->name,
            'boxnumber' => $newBox->boxnumber, 'trucknumber' => $newTruck->boxnumber], '200', 'success', 'getDataReceiptQr');

    }

    public function getFunctionForModule($moduleid)
    {
        $functions = SecurityFunction::where('moduleid', $moduleid)
            ->select('description', 'functionid')->get();
        return $functions;
    }



}
