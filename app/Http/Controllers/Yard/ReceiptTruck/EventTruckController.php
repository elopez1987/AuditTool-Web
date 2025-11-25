<?php

namespace App\Http\Controllers\Yard\ReceiptTruck;

use App\Http\Controllers\BaseController;
use App\Models\Yard\ReceiptTruck;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Web\Yard\ReceiptTruck\QueryReceiptTruck;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\DB;

class EventTruckController extends BaseController
{
    use QueryReceiptTruck;

    public function generateinvoicetransporttruck(ReceiptTruck $receipttruck)
    {
        $pdf = PDF::loadView('Yards.ReceiptTrucks.InvoiceFormats.Invoice', compact('receipttruck'));

        return $pdf->download('Folio_De_Entrada_Tracto-' . $receipttruck->id . '.pdf');
    }

    public function registerOutPutTruck(ReceiptTruck $receipttruck): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $this->generateOutTruck($receipttruck, 'Salida de tracto');
            ReceiptUnit::controlAccessUser()->where('foliotruck', $receipttruck->id)
                ->update(['foliotruck' => null]);
            DB::commit();

            return $this->redirectBackSuccessMessage('Salida con exito de tracto!!');
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();

            return $this->errorResponse('Error al procesa la solicitud : ' . $error);
        }
    }
}
