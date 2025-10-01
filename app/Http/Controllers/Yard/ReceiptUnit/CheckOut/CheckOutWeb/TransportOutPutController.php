<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\CheckOut\CheckOutWeb;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageCheckListDetail;
use App\Models\Yard\StorageLocation;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\Izel\RegisterEdiShipment;
use App\Traits\Web\Yard\CheckList\CheckListTransportTrait;
use App\Traits\Web\Yard\ReceiptTruck\QueryReceiptTruck;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;

class TransportOutPutController extends BaseController
{
    use CheckListTransportTrait;
    use RegisterEdiShipment;
    use QueryReceiptTruck;

    public function edit($id)
    {
        $receiptunit = ReceiptUnit::findOrFail($id);
        $checklistdetails = StorageCheckListDetail::whereCenterid($receiptunit->centerid)
            ->whereHas('checklist', function ($q) {
                $q->where('type', 'OUT');
            })
            ->where('statusid', 'E')
            ->select('id as checklitsdetailid', 'description', 'checklistid')
            ->get();
        $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);
        if (in_array('SWH-06', (array) $parameter)) {
            $checklist = 'on';
        } else {
            $checklist = 'off';
        }
        return view('Yards.TransportOutPut.edit', compact('receiptunit', 'checklistdetails', 'checklist'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $receiptunit = ReceiptUnit::searchForId($id);
            $tracto = ReceiptTruck::searchTruckForBarcode($request['barcodetruck']);
            if (empty($tracto)) {
                return $this->errorResponse('El número de tracto no existe, favor de validar :' . $request['barcodetruck']);
            }
            $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);
            $this->updateDataOfReceiptUnitClose($tracto, $receiptunit, $request);
            //          Realiza la validacion del check list
            if (! empty($parameter) && in_array('SWH-06', (array) $parameter)) {
                $this->clearCheckListTransport($id, 'OUT');
                $this->createCheckListToUnitV2($request, $receiptunit, 'OUT');
                if (! empty($parameter) && in_array('SWH-10', (array) $parameter)) {
                    $difference = $this->validateCheckListV2($receiptunit, 'OUT');
                    if ($difference > '0') {
                        return redirect()->back()->withInput()->with(session()
                            ->flash('error', 'La unidad no cumple con el check list de salida.'));
                    }
                }
            }
            //          Creamos el registro en el kardex
            StorageUnitTrace::createTraceUnit($receiptunit, '8');
            //          Actualizamos la ubicacion a disponible
            StorageLocation::updateLocation($receiptunit->locationid, 'E', '');
            //          Generamos la salida del tracto
            $this->generateOutTruck($tracto, 'Salida de unidad desde Web');
            //          Enviamos los datos a WMS Izel
            $this->addNewEdiForIzel($receiptunit, $parameter, 'OUT');

            DB::commit();
            return redirect('/registercheckout')->with(session()->flash('success', 'Registro actualizado correctamente!!'));
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return redirect()->back()->with(session()->flash('error', 'No se pudieron guardar los datos!!' . $error));
        }
    }

    /**
     * @param ReceiptTruck $tracto
     * @param $receiptunit
     * @param Request $request
     *
     * @return void
     */
    public function updateDataOfReceiptUnitClose(ReceiptTruck $tracto, $receiptunit, Request $request): void
    {
        $receiptunit->operatorname = $tracto->operatorname;
        $receiptunit->trucknumber = $tracto->trucknumber;
        $receiptunit->phone = $tracto->phone;
        $receiptunit->drivernumber = $tracto->drivernumber;
        $receiptunit->auxiliary01 = $tracto->barcode;
        $receiptunit->containerseal = $request['containerseal'];
        $receiptunit->shipmentid = $request['shipmentid'];
        $receiptunit->outputdate = Carbon::now();
        $receiptunit->updateuser = Auth::user()->id;
        $receiptunit->statusid = 'TR';
        $receiptunit->save();
    }
}
