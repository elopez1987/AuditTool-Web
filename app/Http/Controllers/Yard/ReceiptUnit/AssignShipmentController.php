<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Models\Yard\Customer\OrderCustomer;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitData;
use App\Models\Yard\StorageUnitTrace;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class AssignShipmentController extends BaseController
{
    public function index()
    {
        $this->authorize('assignshipment');

        return view('Yards.AssignShipment.index');
    }

    public function getDataTransport()
    {
        $data = ReceiptUnit::controlAccessUser()
            ->leftjoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('receipttrucks', 'receiptunits.foliotruck', '=', 'receipttrucks.id')
            ->Folio(request('folionumber'))
            ->Shipment(request('shipmentid'))
            ->Barcode(request('barcode'))
            ->BoxNumber(request('boxnumber'))
            ->whereNotIn('receiptunits.statusid', ['TR', 'OU'])
            ->select([
                'receiptunits.id',
                'receiptunits.barcode',
                'receiptunits.entrydate',
                'receiptunits.boxnumber',
                'receiptunits.econumber',
                'receiptunits.shipmentid',
                'shipmentcarriers.name as carriername',
                'receipttrucks.operatorname',
            ]);

        return DataTables::of($data)
            ->addColumn('shipmentbutton', 'Yards.AssignShipment.partials.shipment')
            ->rawColumns(['shipmentbutton'])->toJson();
    }

    public function updateShipmentToUnit(Request $request, ReceiptUnit $receiptunit)
    {
        if (! $request->filled('shipmentid')) {
            return $this->errorResponse('El número de embarque no puede estar vacío');
        }

        DB::beginTransaction();
        try {

            $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);

            // Si aplica la validación del parámetro SWH-26
            if (! empty($parameter) && in_array('SWH-26', (array) $parameter)) {

                // 1. Validar que la unidad no esté ya asignada a un embarque
                $folioToshipment = OrderDeliverie::searchForUnit($receiptunit->id);
                if (! empty($folioToshipment)) {
                    return $this->errorResponse('El folio ya se encuentra asignado a un embarque');
                }

                // 2. Validar existencia del embarque
                $shipment = OrderDeliverie::controlAccessUser()->where('shipmentid', $request->shipmentid)->first();
                if (! $shipment) {
                    return $this->errorResponse('El número de embarque no existe, favor de validar');
                }

                // 3. Validar transportista
                if ($receiptunit->carrierid != $shipment->carrierid) {
                    return $this->errorResponse('El embarque no pertenece al transportista asignado');
                }

                // 4. Validar que el embarque no tenga transporte asignado
                $shipmenttransport = StorageShipmentTransport::controlAccessUser()
                    ->where('orderid', $shipment->id)
                    ->first();

                if (optional($shipmenttransport)->auxiliary01 == '1') {
                    return $this->errorResponse('El embarque ya cuenta con transporte, favor de validar');
                }

                // Si todo está correcto, actualiza todo lo relacionado al embarque
                $this->updateOrderForAssingShipmentToUnit($shipment, $receiptunit);
                $this->updateTransportForAssignShipmentToUnit($shipmenttransport, $receiptunit);
            }

            // Siempre actualizar la unidad independientemente del SWH-26
            $this->updateUnitForAssignShipment($receiptunit, $request);

            // Crear bitácora / traza
            StorageUnitTrace::createTraceUnit($receiptunit, '6');

            DB::commit();

            return $this->redirectBackSuccessMessage('Embarque asignado con éxito!!');

        } catch (Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());

            return $this->errorResponse('Error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    private function updateUnitForAssignShipment($receiptunit, $request)
    {
        $receiptunit->shipmentid = $request['shipmentid'];
        $receiptunit->save();
    }

    private function updateTransportForAssignShipmentToUnit($shipmenttransport, $unit)
    {
        $shipmenttransport->operatorname = $unit->operatorname;
        $shipmenttransport->boxnumber = $unit->boxnumber;
        $shipmenttransport->auxiliary01 = '1';
        $shipmenttransport->auxiliary02 = '1';
        $shipmenttransport->save();
    }

    private function updateOrderForAssingShipmentToUnit($shipment, $receiptunit)
    {
        $shipment->folionumber = $receiptunit->id;
        $shipment->save();

        $receiptData = ReceiptUnitData::where('folionumber', $receiptunit->id)->first();
        if (! empty($receiptData)) {
            $customer = OrderCustomer::where('id', $shipment->customerid)->first();
            $receiptData->customername = $customer->name;
            $receiptData->save();
        }

    }
}
