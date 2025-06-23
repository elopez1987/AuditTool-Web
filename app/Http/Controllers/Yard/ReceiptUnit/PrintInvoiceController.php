<?php

namespace App\Http\Controllers\Yard\ReceiptUnit;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptUnit;
use App\Traits\GenerateLabel;
use App\Traits\PrintFormat;

class PrintInvoiceController extends Controller
{
    use GenerateLabel;
    use PrintFormat;

    public function generateInvoiceTransport(ReceiptUnit $receiptunit)
    {
        return $this->printFormatInvoice($receiptunit, 'web');
    }

    public function generatelabel($id)
    {
        $receiptunit = ReceiptUnit::findOrFail($id);
        return $this->generateLabelTruck($receiptunit, 'web');
    }


}
