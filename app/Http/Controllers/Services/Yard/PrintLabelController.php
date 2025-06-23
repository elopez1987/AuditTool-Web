<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptUnit;
use App\Traits\GenerateLabel;
use App\Traits\PrintFormat;
use App\Traits\ResponseApi;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class PrintLabelController extends Controller
{
    use GenerateLabel;
    use ResponseApi;
    use PrintFormat;


    public function printLabelForEntry(Request $request)
    {
        $receiptunit = $this->getFolio($request['folioid']);
        $dataZpl = $this->generateLabelTruck($receiptunit, 'app');
        return $this->getResponseSuccess('printLabelForEntry', ['dataZpl' => $dataZpl]);
    }

    public function generatePDFToFolio(Request $request)
    {
        $receiptunit = $this->getFolio($request['folioid']);
        return $this->printFormatInvoice($receiptunit,'api');
    }

    public function getFolio($folioid)
    {
        return ReceiptUnit::controlAccessUser()->where('id', '=', $folioid)
            ->whereNotIn('statusid', ['TR'])->first();
    }


}
