<?php

namespace App\Http\ControllersApp\Yard\ReceiptUnit\Events;

use App\Http\Controllers\Controller;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Shipments\EdiShipment;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\GetParameters;
use App\Traits\ResponseApi;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;

class StartProcessUnitController extends Controller
{
    use ResponseApi;
    use GetParameters;

    public function index(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request['barcodebox'] == null) {
                return $this->getResponseWithErrors('startUnitProcess', 'El codigo de barra de la caja es obligatorio.');
            }
            $folionumber = ReceiptUnit::controlAccessUser()
                ->where('barcode', '=', $request['barcodebox'])
                ->whereNotIn('statusid', ['TR', 'OU', 'FI'])->first();
            if (empty($folionumber)) {
                return $this->getResponseWithErrors('startUnitProcess', 'No existen registros del codigo de barra ingresado o se encuentra en un estatus no permitido.');
            }

            $parameter = $this->getParametersOfWarehouse($folionumber->warehouseid);
            if (!empty($parameter) && in_array('SWH-39', $parameter)) {
                if ($folionumber->ensnare == null) {
                    return $this->getResponseWithErrors('startUnitProcess', 'La unidad no cuenta con evento de enrampe, favor de validar.');
                }
            }

            if ($folionumber->zone->zoneid != 'CO') {
                return $this->getResponseWithErrors('startUnitProcess', 'La unidad no se encuentra en cortina,favor de enrampar.');
            }
            if ($folionumber->startdate != null) {
                return $this->getResponseWithErrors('startUnitProcess', 'El proceso ya fue iniciado,favor de validar.');
            }

            $this->updateReceiptUnit($folionumber);
            $this->updateReceiptAsn($folionumber);
            $this->updateOrderDelivery($folionumber);

            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            DB::rollback();
            Log::error($e->getMessage());
        }

        if ($success) {
            return $this->getResponseSuccess('startUnitProcess', []);
        } else {
            return $this->getResponseWithErrors('startUnitProcess', 'Error al procesar el movimiento.');
        }
    }


    /**
     * @param ReceiptUnit $folionumber
     * @return void
     */
    public function updateReceiptAsn(ReceiptUnit $folionumber): void
    {
        if ($folionumber->razonstatusid == 2) {
            ReceiptAsn::startProcessReceipt($folionumber->id);
        }
    }

    /**
     * @param ReceiptUnit $folionumber
     * @return void
     */
    public function updateOrderDelivery(ReceiptUnit $folionumber): void
    {
        if ($folionumber->razonstatusid == 1) {
            $shipment = OrderDeliverie::controlAccessUser()->where('folionumber', $folionumber->id)->first();
            if (!empty($shipment)) {
                OrderDeliverie::updateStatusForStartProcess($shipment);
                $parameter = $this->getParametersOfWarehouse($shipment->warehouseid);
                if (in_array('SWH-21', (array)$parameter)) {
                    EdiShipment::createEDIForEventToShipment($shipment, 'START_LOAD');
                }
            }
        }
    }

    /**
     * @param ReceiptUnit $folionumber
     * @return void
     */
    public function updateReceiptUnit(ReceiptUnit $folionumber): void
    {
        $folionumber->startdate = now();
        $folionumber->statusid = 'EP';
        $folionumber->save();
        StorageUnitTrace::createTraceUnit($folionumber, '14');
    }

}
