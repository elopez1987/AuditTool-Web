<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\CheckListCheckIn;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Yard\Exception;
use App\Models\General\StorageComment;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageCheckList;
use App\Models\Yard\StorageCheckListDetail;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\Web\EDI\RegisterEdiShipment;
use App\Traits\Web\General\PrintFormat;
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
    use DeclineUnitForReceipt;
    use DeclineUnitForShipment;
    use PrintFormat;
    use QueryReceiptTruck;
    use RegisterEdiShipment;

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
                'center:centerid,description',
                'tracto:id,operatorname,phone,trucknumber,econumber',
                'status:statusid,name', 'unittype:id,description',
            ])
            ->select([
                'receiptunits.id',
                'receiptunits.centerid',
                'receiptunits.warehouseid',
                'receiptunits.barcode',
                'receiptunits.carrierid',
                'receiptunits.foliotruck',
                'receiptunits.boxnumber',
                'receiptunits.containerseal',
                'receiptunits.entrydate',
                'receiptunits.razonstatusid',
                'receiptunits.statusid',
                'receiptunits.unittypeid',
            ])
            ->Folio($request->input('folionumber'))
            ->BoxNumber($request->input('boxnumber'))
            ->Barcode($request->input('barcode'))
            ->where('receiptunits.statusid', 'ES');

        return $this->showDataToView($data, 'Yards.AuthorizeTransportEntry.partials.buttons');
    }

    public function edit($id)
    {
        $receiptunit = ReceiptUnit::controlAccessUser()->with('unittype')->findOrFail($id);
        $origen = $receiptunit->unittype->auxiliary01;
        $razonstatusid = $receiptunit->razonstatusid;

        $checkList = StorageCheckList::where('centerid', $receiptunit->centerid)
            ->where('warehouseid', $receiptunit->warehouseid)
            ->where('type', 'IN')
            ->where('statusid', 'E')
            ->where('auxiliary01', $origen)
            ->where('auxiliary03', $razonstatusid)
            ->select(['id', 'auxiliary02'])->first();

        if (! $checkList) {
            $checkList = StorageCheckList::where('centerid', $receiptunit->centerid)
                ->where('statusid', 'E')
                ->where('auxiliary04', 'S')
                ->select(['id', 'auxiliary02'])
                ->first();
        }

        if (! $checkList) {
            return $this->errorResponse('No existe el check list para la unidad, favor de validar el Origen y Concepto de Entrada');
        }
        $checklistdetails = StorageCheckListDetail::query()->where('checklistid', $checkList->id)
            ->where('statusid', 'E')->select(['checklistid', 'id as checklitsdetailid', 'description'])->get();

        return view('Yards.AuthorizeTransportEntry.edit', compact('receiptunit', 'checklistdetails'));
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $receiptunit = ReceiptUnit::findOrFail($id);
            $this->clearCheckListTransport($id, 'IN');
            $this->createCheckListToUnitV2($request, $receiptunit, 'IN');
            $this->validateCheckListCheckInTransport($receiptunit, 'WEB');

            $receiptunit->statusid = 'QI';
            $receiptunit->entrydate = Carbon::now();
            $receiptunit->save();
            StorageUnitTrace::createTraceUnit($receiptunit, '7');
            if (! empty($request['comment'])) {
                StorageComment::createCommentary('ReceiptUnits', $receiptunit->id, $request);
            }
            DB::commit();

            return $this->successResponse('receiptunits.index');
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();

            return $this->errorResponse('No se pudieron guardar los datos!!' . $error);
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

            $receiptunit->update([
                'statusid' => 'TR',
                'outputdate' => Carbon::now(),
                'updated_by' => Auth::user()->id,
            ]);

            if ((int) $receiptunit->razonstatusid === 1) {
                $this->declineUnitForShipment($receiptunit);
            }
            if ((int) $receiptunit->razonstatusid === 2) {
                $this->declineUnitForReceipt($receiptunit->id);
            }
            StorageUnitTrace::createTraceUnit($receiptunit, '23');
            DB::commit();

            return Redirect::back()->with(session()->flash('success', 'Datos agregados correctamente!!'));
        } catch (\Exception $e) {
            $error = $e->getMessage();
            DB::rollback();

            return redirect()->back()->withInput()->with(session()->flash('error', 'La unidad no cumple con el check list : ' . $error));
        }
    }
}
