<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentTransport\StorageShipmentTransport;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitTicket;
use App\Models\Yard\StorageUnitTrace;
use DB;
use Exception;
use Illuminate\Http\Request;
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
        $data = $this->getData();
        return DataTables::of($data)
            ->addColumn('shipmentbutton', 'Yards.ReceiptUnits.partials.shipment')
            ->addColumn('addresponsible', 'Yards.AssignShipment.partials.responsable')
            ->rawColumns(['shipmentbutton', 'addresponsible'])->toJson();
    }

    public function updateShipmentToUnit(Request $request, ReceiptUnit $receiptunit)
    {
        DB::beginTransaction();
        try {
            if (empty($request['shipmentid'])) {
                return $this->errorResponse('El número de embarque no puede estar vacio');
            }
            $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);
            if (in_array('SWH-26', (array) $parameter)) {
                $folioToshipment = OrderDeliverie::searchForUnit($receiptunit->id);
                if (! empty($folioToshipment)) {
                    return $this->errorResponse('El folio ya se encuentra asignado a un embarque');
                }
                $shipment = OrderDeliverie::searchForShipment($request['shipmentid']);
                if (empty($shipment)) {
                    return $this->errorResponse('El número de embarque no existe, favor de validar');
                }
                $shipmenttransport = StorageShipmentTransport::controlAccessUser()->where('orderid', $shipment->id)->first();
                if (! empty($shipmenttransport) && $shipmenttransport->auxiliary01 === '1') {
                    return $this->errorResponse('El embarque  ya cuenta con transporte, favor de validar');
                }
                self::updateOrderForAssingShipmentToUnit($shipment, $receiptunit);
                self::updateTransportForAssignShipmentToUnit($shipmenttransport, $receiptunit);
                self::updateUnitForAssignShipment($receiptunit, $request);
            } else {
                self::updateUnitForAssignShipment($receiptunit, $request);
            }
            StorageUnitTrace::createTraceUnit($receiptunit, '6');
            DB::commit();
            return $this->redirectBackSuccessMessage('Embarque asignado con exito!!');
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return $this->errorResponse('Error al procesar la solicitud' . $error);
        }
    }

    public static function updateUnitForAssignShipment($receiptunit, $request)
    {
        $receiptunit->shipmentid = $request['shipmentid'];
        $receiptunit->save();
    }

    public static function updateTransportForAssignShipmentToUnit($shipmenttransport, $unit)
    {
        $shipmenttransport->operatorname = $unit->operatorname;
        $shipmenttransport->boxnumber = $unit->boxnumber;
        $shipmenttransport->auxiliary01 = '1';
        $shipmenttransport->auxiliary02 = '1';
        $shipmenttransport->save();
    }

    public static function updateOrderForAssingShipmentToUnit($shipment, $receiptunit)
    {
        $shipment->folionumber = $receiptunit->id;
        $shipment->save();
    }

    public function getData()
    {
        return ReceiptUnit::controlAccessUser()
            ->leftJoin('shipmentcarriers', 'receiptunits.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('storagereceiptunitticket', 'receiptunits.id', '=', 'storagereceiptunitticket.folionumber')
            ->leftJoin('users', 'storagereceiptunitticket.responsibleid', '=', 'users.id') // Cambiado a LEFT JOIN
            ->leftJoin('storageareas', 'storagereceiptunitticket.areaid', '=', 'storageareas.id') // Cambiado a LEFT JOIN
            ->whereNotIn('receiptunits.statusid', ['TR', 'OU'])
            ->select([
                'receiptunits.id',
                'receiptunits.barcode',
                'receiptunits.entrydate',
                'receiptunits.carrierid',
                'receiptunits.boxnumber',
                'receiptunits.econumber',
                'receiptunits.operatorname',
                'receiptunits.shipmentid',
                'users.name as responsable', // Puede ser NULL si no hay relación
                'storageareas.name as areaname', // Puede ser NULL si no hay relación
                'shipmentcarriers.name as carriername',
            ]);
    }

    public function saveresponsibletounit(Request $request, ReceiptUnit $receiptunit)
    {
        $existingTicketRecord = ReceiptUnitTicket::where('folionumber', $receiptunit->id)->first();
        ReceiptUnitTicket::updateOrCreate([
            'folionumber' => $receiptunit->id,
            'ticket' => $existingTicketRecord->ticket ?? '0',
        ], [
            'responsibleid' => $request['responsibleid'],
            'areaid' => $request['areaid'],
        ]);
        return $this->redirectBackSuccessMessage('Responsable asignado con exito!!');
    }
}
