<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\ReceiptUnitTicket;
use Illuminate\Http\Request;

class AssignResponsibleController extends BaseController
{
    public function index(Request $request, ReceiptUnit $receiptunit)
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
