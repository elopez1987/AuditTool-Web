<?php

namespace App\Http\ControllersApp\Configuration\Printers;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Printers\SystemPrinter;
use App\Traits\ResponseApi;

class PrintersController extends Controller
{
    use ResponseApi;
    public function getPrinterForCenter()
    {
        $printers = SystemPrinter::controlAccessUser()
            ->select(['printerid','description','ip','auxiliary01 as port'])->get();
        return $this->getResponseSuccess('getPrinterForCenter',$printers);
    }
}
