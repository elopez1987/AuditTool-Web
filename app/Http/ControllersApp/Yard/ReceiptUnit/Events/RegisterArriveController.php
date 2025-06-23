<?php

namespace App\Http\ControllersApp\Yard\ReceiptUnit\Events;

use App\Http\Controllers\Controller;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptStage;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentStage;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\GetParameters;
use App\Traits\ResponseApi;
use App\Traits\Web\Yard\ReceiptUnit\ArriveUnit\SaveArriveUnit;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Validator;

class RegisterArriveController extends Controller
{
    use ResponseApi;
    use GetParameters;
    use SaveArriveUnit;


//    Registro de entrega de llaves del operador en mesa de control
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barcodebox' => 'required|min:4|max:15',
        ]);
        if ($validator->fails()) {
            return $this->getResponseWithErrors('saveArriveUnitToCenter', 'El código de barra de la caja es obligatorio.');
        }
        DB::beginTransaction();
        try {
            $receiptunit = ReceiptUnit::searchForBarcode($request['barcodebox']);
            if (empty($receiptunit)) {
                return $this->getResponseWithErrors('saveReceiptKeyForOperator', 'No existen registros del codigo de barra ingresado.');
            }
            $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);

//            Registra la llegada a mesa control
            if ($receiptunit->deliverdate == null && $receiptunit->keydate == null) {
                $receiptunit->deliverdate = Carbon::now();
                $receiptunit->keydate = Carbon::now();
                $receiptunit->statusid = 'IN';
                $receiptunit->save();
                StorageUnitTrace::createTraceUnit($receiptunit, '28');
                //Entrada a descargar
                if ($receiptunit->razonstatusid == '2') {
                    if ($receiptunit->zone->zoneid == 'CO') {
                        $receiptAsn = ReceiptAsn::where('shipmentid', $receiptunit->shipmentid)->first();
                        if (!empty($receiptAsn)) {
                            $door = ReceiptStage::where('receiptid', $receiptAsn->id)->first();
                            if (!empty($door)) {
                                $locationStatus = StorageLocation::controlAccessUser()->where('id', $door->locationid)->first();
                                if (!empty($locationStatus)) {
                                    if ($locationStatus->statusid == 'E') {
                                        $receiptunit->ensnare = Carbon::now();
                                        $receiptunit->statusid = 'EN';
                                        $receiptunit->save();
                                        StorageLocation::updateLocation($locationStatus->id, 'D', $receiptunit->boxnumber);
                                        StorageUnitTrace::createTraceUnit($receiptunit, '13');
                                    }
                                }
                            }
                        }
                    }
                }
                // Proceso de enrampe para embarques
                if ($receiptunit->razonstatusid == '1') {
                    if ($receiptunit->zone->zoneid == 'CO') {
                        $shipment = OrderDeliverie::controlAccessUser()->where('folionumber', $receiptunit->id)->first();
                        if (!empty($shipment)) {
                            $door = ShipmentStage::where('orderid', $shipment->id)->first();
                            if (!empty($door)) {
                                $location = StorageLocation::controlAccessUser()->where('id', $door->value01)->first();
                                if (!empty($location)) {
                                    $receiptunit->ensnare = Carbon::now();
                                    $receiptunit->statusid = 'EN';
                                    $receiptunit->save();
                                    StorageLocation::updateLocation($location->id, 'D', $receiptunit->boxnumber);
                                    StorageUnitTrace::createTraceUnit($receiptunit, '13');
                                }
                            }
                        }
                    }
                }
            }

//            Registro de autorizacion de salida
            if ($receiptunit->finishdate != null) {
                StorageLocation::updateLocation($receiptunit->locationid, 'E', null);
                $location = $this->getLocationDummy($receiptunit->centerid);

                if ($receiptunit->razonstatusid == 1) {
                    $shipment = OrderDeliverie::searchForUnit($receiptunit->id);
                    if (!empty($shipment)) {
                        if (!empty($parameter) && in_array('SWH-19', (array)$parameter)) {
                            if ($shipment->statusid != 'EJ') {
                                return $this->getResponseWithErrors('autorizeExit', 'El número de embarque no se encuentra facturado');
                            } else {
                                $shipment->statusid = 'DT';
                                $shipment->save();
                            }
                        } else {
                            $shipment->statusid = 'DH';
                            $shipment->save();
                        }
                    }
                }
                if ($receiptunit->razonstatusid == 2) {
                    if (in_array('SWH-31', (array)$parameter)) {
                        $shipmentReceipt = ReceiptAsn::searchReceiptForUnit($receiptunit->id);
                        if (!empty($shipmentReceipt) && $shipmentReceipt->unloadfinishdate === null) {
                            return $this->getResponseWithErrors('autorizeExit', 'La descarga relacionada no ha finalizado');
                        }
                    }
                }
                $receiptunit->toreceivedate = now();
                $receiptunit->autorizaoutputdate = now();
                $receiptunit->statusid = 'OU';
                $receiptunit->zoneid = $location->zoneid;
                $receiptunit->locationid = $location->id;
                $receiptunit->save();
                StorageUnitTrace::createTraceUnit($receiptunit, '11');
            }

            DB::commit();
            $success = true;
        } catch (Exception) {
            $success = false;
            DB::rollback();
        }
        if ($success) {
            return $this->getResponseSuccess('saveReceiptKeyForOperator', []);
        } else {
            return $this->getResponseWithErrors('saveReceiptKeyForOperator', 'Error al procesar el movimiento');
        }
    }
}
