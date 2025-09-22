<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\BaseController;
use App\Models\General\StorageComment;
use App\Models\Yard\ReceiptUnit;
use Illuminate\Http\Request;

class ReceiptUnitCommentController extends BaseController
{
//    --Agregar comentarios al folio desde la existencia
    public function addcomments($id)
    {
        $receiptunit = ReceiptUnit::searchForId($id);
        $comments = StorageComment::getCommentForModel($receiptunit->id, 'ReceiptUnits');
        return view('Yards.ReceiptUnits.comment', compact('receiptunit', 'comments'));
    }

    public function receiptunitscommentssave(Request $request, $id)
    {
        StorageComment::createCommentary('ReceiptUnits', $id, $request);
        return $this->redirectBackSuccessMessage('Registro agregado con exito.');
    }
}
