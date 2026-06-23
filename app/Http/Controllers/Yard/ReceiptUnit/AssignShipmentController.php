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
use Illuminate\Support\Facades\Auth;
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
        $carriers = $this->getCarrierForUser();
        $data = ReceiptUnit::controlAccessUser()
            ->leftjoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('receipttrucks', 'receiptunits.foliotruck', '=', 'receipttrucks.id')
            ->Folio(request('folionumber'))
            ->Shipment(request('shipmentid'))
            ->Barcode(request('barcode'))
            ->BoxNumber(request('boxnumber'))
            ->whereNotIn('receiptunits.statusid', ['TR', 'OU'])
            ->when(! empty($carriers), function ($query) use ($carriers) {
                $query->whereIn('receiptunits.carrierid', $carriers);
            })
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
                $shipment = OrderDeliverie::controlAccessUser()->where('shipmentid', $request->shipmentid)
                    ->select('id', 'shipmentid', 'folionumber', 'carrierid', 'customerid')->first();
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

                if (optional($shipmenttransport)->has_registered == '1') {
                    return $this->errorResponse('El embarque ya cuenta con transporte, favor de validar');
                }

                // Si todo está correcto, actualiza todo lo relacionado al embarque
                $this->updateOrderForAssignShipmentToUnit($shipment, $receiptunit);
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
            Log::error('Error al procesar la solicitud', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
                'user_id' => Auth::id(),
                'trace' => $e->getTraceAsString(),
            ]);

            return $this->errorResponse('Error al procesar la solicitud: ' . $e->getMessage());
        }
    }

    private function updateUnitForAssignShipment($receiptunit, $request)
    {
        $receiptunit->update([
            'shipmentid' => $request['shipmentid'],
        ]);

    }

    private function updateTransportForAssignShipmentToUnit($shipmenttransport, $unit)
    {
        $shipmenttransport->update([
            'operatorname' => $unit->operatorname,
            'boxnumber' => $unit->boxnumber,
            'has_registered' => '1',
            'has_assigned' => '1',
        ]);
    }

    private function updateOrderForAssignShipmentToUnit($shipment, ReceiptUnit $receiptUnit): void
    {
        $shipment->update([
            'folionumber' => $receiptUnit->id,
        ]);

        $receiptData = ReceiptUnitData::where('folionumber', $receiptUnit->id)->first();

        if (is_null($receiptData)) {
            return;
        }

        $customer = OrderCustomer::find($shipment->customerid);

        if (is_null($customer)) {
            return;
        }

        $receiptData->update([
            'customername' => $customer->name,
        ]);
    }
}
