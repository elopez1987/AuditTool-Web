<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\CheckListCheckIn;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Yard\Exception;
use App\Models\General\StorageComment;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageCheckListDetail;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\General\PrintFormat;
use App\Traits\Web\Izel\RegisterEdiShipment;
use App\Traits\Web\Yard\CheckList\CheckListTransportTrait;
use App\Traits\Web\Yard\ReceiptTruck\QueryReceiptTruck;
use App\Traits\Web\Yard\ReceiptUnit\DeclineUnit\DeclineUnitForReceipt;
use App\Traits\Web\Yard\ReceiptUnit\DeclineUnit\DeclineUnitForShipment;
use Auth;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use Redirect;

class AuthorizeTransportEntriesController extends BaseController
{
    use CheckListTransportTrait;
    use RegisterEdiShipment;
    use PrintFormat;
    use QueryReceiptTruck;
    use DeclineUnitForShipment;
    use DeclineUnitForReceipt;

    public function index()
    {
        $this->authorize('authorizetransportentry.index');
        return view('Yards.AuthorizeTransportEntry.index');
    }

    public function getDataTransportES(Request $request)
    {
        $data = ReceiptUnit::controlAccessUser()
            ->with(['unittype:id,description', 'warehouse:warehouseid,name',
                'carrier:id,name', 'reason:id,name',
                'center:centerid,description', 'status:statusid,name', 'unittype:id,description',
            ])
            ->Folio($request->get('folionumber'))
            ->BoxNumber($request->get('boxnumber'))
            ->Barcode($request->get('barcode'))
            ->where('receiptunits.statusid', 'ES')
            ->get();
        return $this->showDataToView($data, 'Yards.AuthorizeTransportEntry.partials.buttons');
    }

    public function edit($id)
    {
        $receiptunit = ReceiptUnit::findOrFail($id);
        $origen = $receiptunit->unittype->auxiliary01;
        $type = $receiptunit->razonstatusid;
        if ($receiptunit->centerid === 'HENKELTOL01') {
            if ($receiptunit->razonstatusid === '1') {
                $checklistdetails = StorageCheckListDetail::whereCenterid($receiptunit->centerid)
                    ->where('checklistid', '=', '3')
                    ->where('statusid', 'E')
                    ->get(['id as checklitsdetailid', 'description', 'checklistid']);
            } else {
                $checklistdetails = StorageCheckListDetail::whereCenterid($receiptunit->centerid)
                    ->where('checklistid', '=', '10018')
                    ->where('statusid', 'E')
                    ->get(['id as checklitsdetailid', 'description', 'checklistid']);
            }
        } else {
            $checklistdetails = StorageCheckListDetail::whereCenterid($receiptunit->centerid)
                ->whereHas('checklist', function ($q) use ($receiptunit, $origen, $type) {
                    $q->where('type', 'IN');
                    $q->where('statusid', 'E');
                    $q->where('auxiliary01', $origen);
                    $q->where('auxiliary03', $type);
                    $q->where('centerid', $receiptunit->centerid);
                    $q->where('warehouseid', $receiptunit->warehouseid);
                })
                ->where('statusid', 'E')
                ->get(['id as checklitsdetailid', 'description', 'checklistid']);
        }
        return view('Yards.AuthorizeTransportEntry.edit', compact('receiptunit', 'checklistdetails'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $receiptunit = ReceiptUnit::findOrFail($id);
            $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);
            $this->clearCheckListTransport($id, 'IN');
            $this->createCheckListToUnitV2($request, $receiptunit, 'IN');
            $this->validateCheckListCheckInTransport($receiptunit, 'WEB');
            $this->addNewEdiForIzel($receiptunit, $parameter, 'IN');
            $this->sendDataToRControl($receiptunit, $parameter, 'ENTRANCE');

            $receiptunit->statusid = 'QI';
            $receiptunit->entrydate = Carbon::now();
            $receiptunit->save();
            StorageUnitTrace::createTraceUnit($receiptunit, '7');
            if (! empty($request['comment'])) {
                StorageComment::createCommentary('ReceiptUnits', $receiptunit->id, $request);
            }
            DB::commit();
            return redirect()->route('receiptunits.index')->with(session()->flash('success', 'Datos agregados correctamente!!'));
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return redirect()->back()
                ->with(session()->flash('error', 'No se pudieron guardar los datos!!' . $error));
        }
    }

    public function declineUnitInCheckInProcess($id)
    {
        $this->authorize('registerout');
        DB::beginTransaction();
        try {
            $receiptunit = ReceiptUnit::controlAccessUser()->where('statusid', '=', 'ES')->where('id', $id)->first();
            if (empty($receiptunit)) {
                return $this->errorResponse('No existe el folio buscado.');
            }
            $receipttruck = ReceiptTruck::controlAccessUser()->where('id', $receiptunit->foliotruck)->first();
            if (! empty($receipttruck)) {
                $this->generateOutTruck($receipttruck, 'Salida por rechazo de unidad al entrar');
            }
            $receiptunit->statusid = 'TR';
            $receiptunit->outputdate = Carbon::now();
            $receiptunit->updateuser = Auth::id();
            $receiptunit->save();
            if ($receiptunit->razonstatusid === 1) {
                $this->declineUnitForShipment($receiptunit);
            }
            if ($receiptunit->razonstatusid === 2) {
                $this->declineUnitForReceipt($receiptunit->id);
            }
            StorageUnitTrace::createTraceUnit($receiptunit, '23');
            DB::commit();
            return redirect::back()->with(session()->flash('success', 'Datos agregados correctamente!!'));
        } catch (\Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return redirect()->back()->withInput()->with(session()->flash('error', 'La unidad no cumple con el check list : ' . $error));
        }
    }
}
