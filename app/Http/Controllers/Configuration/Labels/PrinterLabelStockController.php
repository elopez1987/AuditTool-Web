<?php

namespace App\Http\Controllers\Configuration\Labels;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Folios\SystemFolio;
use App\Models\Configuration\Labels\InventoryLabelDetail;
use App\Models\Configuration\Schemas\SystemStatus;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PrinterLabelStockController extends BaseController
{
    public function index()
    {
        $this->authorize('printerlabel');
        $centers = $this->getCenterForSelect();
        $printers = SystemStatus::whereSchemaid('PRT')->select('name', 'statusid')->get();

        return view('Yards.PrintLabelStock.index', compact('centers', 'printers'));
    }

    public function printlabel(Request $request)
    {
        $center = $request['centerid'];
        $quantity = $request['quantity'];
        if ($request['printerid'] === 'ZB') {
            return $this->generateLabelBoxZebra411($center, $quantity, $request['typeid']);
        }
        if ($request['printerid'] === 'NS') {
            return $this->generateLabelZebraNaos($center, $quantity, $request['typeid']);
        }
    }

    public function generateLabelBoxZebra411($centerid, $quantity, $type)
    {
        $nameFile = 'Label.txt';
        $file = fopen($nameFile, 'w');
        $folio = SystemFolio::where('schemaid', '=', 'FOL')
            ->where('statusid', '=', 'YMS')
            ->where('centerid', $centerid)
            ->first(['id', 'folionumber']);
        $centerName = SystemCenterDistribution::where('centerid', $centerid)->first();
        $date = Carbon::now()->format('d/m/Y');
        $detailsLabel = InventoryLabelDetail::getDetailForLabel('LABEL-STK');
        for ($i = 1; $i <= $quantity; $i++) {
            foreach ($detailsLabel as $item) {
                if (str_contains($item->description, '(storagelabel.center)')) {
                    $valor = str_replace('(storagelabel.center)', $centerName->description, $item->description);
                } elseif (str_contains($item->description, '(storagelabel.barcode)')) {
                    $valor = str_replace('(storagelabel.barcode)', $folio->folionumber, $item->description);
                } elseif (str_contains($item->description, '(date)')) {
                    $valor = str_replace('(date)', $date, $item->description);
                } elseif (str_contains($item->description, '(type)')) {
                    $valor = str_replace('(type)', $type, $item->description);
                } else {
                    $valor = $item->description;
                }
                fwrite($file, $valor."\n");
            }
            $folio->folionumber += 1;
        }
        $folio->save();
        fclose($file);

        return response()->download($nameFile)->deleteFileAfterSend();
    }

    public function generateLabelZebraNaos($centerid, $quantity, $type)
    {
        $nameFile = 'Label.txt';
        $file = fopen($nameFile, 'w');
        $folio = SystemFolio::where('schemaid', '=', 'FOL')
            ->where('statusid', '=', 'YMS')->where('centerid', $centerid)->first(['id', 'folionumber']);
        for ($i = 1; $i <= $quantity; $i++) {
            $var01 = '^XA'."\n";
            $var02 = '^RS,,,3,N'."\n";
            $var03 = '^RR3'."\n";
            $var04 = '^XZ'."\n";
            $var05 = '^XA'."\n";
            $var06 = '^SZ2^JMA'."\n";
            $var07 = '^MCY^PMN'."\n";
            $var08 = '^PW1196'."\n";
            $var09 = '~JSN'."\n";
            $var10 = '^JZY'."\n";
            $var11 = '^LH0,0^LRN'."\n";
            $var12 = '^XZ'."\n";
            $var13 = '^XA'."\n";
            $var14 = '^FT44,284'."\n";
            $var15 = '^CI0'."\n";
            $var16 = "^A0N,54,76^FD{$type}^FS"."\n";
            $var17 = '^FO871,37'."\n";
            $var18 = "^BQN,2,12^FDLA,{$folio->folionumber}^FS"."\n";
            $var19 = '^FO44,309'."\n";
            $var20 = "^BY6^BCN,209,N,N^FD>:{$folio->folionumber}^FS"."\n";
            $var21 = '^FT301,557'."\n";
            $var22 = "^A0N,42,57^FD{$folio->folionumber}^FS"."\n";
            $var23 = '^PQ2,0,1,Y'."\n";
            $var24 = '^XZ'."\n";
            fwrite(
                $file,
                $var01.$var02.$var03.$var04.$var05.$var06.$var07.$var08.$var09.$var10
                .$var11.$var12.$var13.$var14.$var15.$var16.$var17.$var18.$var19.$var20.$var21.$var22.$var23.$var24
            );
            $folio->folionumber += 1;
        }
        $folio->save();
        fclose($file);

        return response()->download($nameFile)->deleteFileAfterSend();
    }
}
