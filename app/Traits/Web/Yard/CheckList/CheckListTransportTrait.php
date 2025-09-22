<?php

namespace App\Traits\Web\Yard\CheckList;

use App\Models\Yard\CheckListTransport;
use App\Models\Yard\ReceiptUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

trait CheckListTransportTrait
{
    public function clearCheckListTransport($id, $type)
    {
        DB::table('storagechecklisttransports')
            ->where('receiptunitid', '=', $id)
            ->where('type', '=', $type)
            ->delete();
    }

    public function createCheckListToUnitV2(Request $request, ReceiptUnit $receiptunit, $type): void
    {
        if ($request['checklitsdetailid'] !== '') {
            $checklistids = $request->get('checklitsdetailid');
            $responses = $request->get('response');
            $checkid = $request->get('checklistid');

            foreach ($checklistids as $i => $checklistDetailId) {
                $checkListTransport = new CheckListTransport();
                $checkListTransport->centerid = $receiptunit->centerid;
                $checkListTransport->checklistid = $checkid[$i];
                $checkListTransport->receiptunitid = $receiptunit->id;
                $checkListTransport->checklitsdetailid = $checklistDetailId;
                $checkListTransport->verified = $responses[$i];
                $checkListTransport->type = $type;
                $checkListTransport->save();
            }
        }
    }

    public function validateCheckListCheckInTransport($receiptunit, $platform)
    {
        $parameter = $this->getParametersOfWarehouse($receiptunit->warehouseid);
        if (in_array('SWH-04', (array) $parameter)) {
            $difference = $this->validateCheckListV2($receiptunit, 'IN');
            if ($difference > '0') {
                if ($platform === 'WEB') {
                    return $this->errorResponse('La unidad no cumple con el check list solicitado, favor de volver a validar. ');
                }
                if ($platform === 'API') {
                    return $this->getResponseWithErrors('saveNewUnitStandard', ',La unidad no cuemple con el checkllist de transporte, favor de validar.');
                }
            }
        }
    }

    public function validateCheckListV2($receiptunit, $type)
    {
        return CheckListTransport::whereReceiptunitid($receiptunit->id)
            ->whereType($type)
            ->select('checklitsdetailid')
            ->where('verified', 'NO')
            ->where('type', $type)
            ->get()->count();
    }
}
