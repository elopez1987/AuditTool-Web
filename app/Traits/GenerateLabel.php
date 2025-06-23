<?php


namespace App\Traits;


use App\Models\Configuration\Labels\InventoryLabelDetail;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLabelTruck\StorageLabelTruck;
use App\Models\Yard\StorageOperator;
use Carbon\Carbon;

trait GenerateLabel
{

    /**
     * @param $type
     * @return string
     */
    public function getTypeRegister($type): string
    {
        if ($type == 'BX') {
            $type = 'CAJA';
        } else {
            $type = 'TRACTO';
        }
        return $type;
    }

    private function generateLabelTruck(ReceiptUnit $receiptunit, $type)
    {

        $nameFile = "Label.txt";
        $file = @fopen($nameFile, "w");
        $date = Carbon::now()->format('d/m/Y');
        $unitBox = self::getDataUnitForLabel($receiptunit->id);
        $detailsLabel = InventoryLabelDetail::getDetailForLabel('LABEL-BOX');

        foreach ($detailsLabel as $item) {
            if (str_contains($item->description, '$shipment')) {
                $valor = str_replace('$shipment', $unitBox->shipment, $item->description);
            } elseif
            (str_contains($item->description, '$etiqueta')) {
                $valor = str_replace('$etiqueta', $unitBox->code, $item->description);
            } elseif
            (str_contains($item->description, '$folio')) {
                $valor = str_replace('$folio', $unitBox->folionumber, $item->description);
            } elseif
            (str_contains($item->description, '$fecha')) {
                $valor = str_replace('$fecha', $date, $item->description);
            } elseif
            (str_contains($item->description, '$avior')) {
                $valor = str_replace('$avior', $unitBox->avior, $item->description);
            } else {
                $valor = $item->description;
            }
            fwrite($file, $valor . "\n");
        }
        fclose($file);

        if ($type == 'web') {
//            $printerIp = '192.168.100.90';
//            $port = 9100;
//            $fp = fsockopen($printerIp, $port, $errno, $errstr, 10);
//            if (!$fp) {
//                return back()->with('error', "Error de conexión: $errstr ($errno)");
//            }
//            $content = file_get_contents($nameFile);
//            fwrite($fp, $content);
//            fclose($fp);
//            return back()->with('success', '✅ Etiqueta enviada a imprimir.')->with('zpl', $nameFile);
            return response()->download($nameFile)->deleteFileAfterSend();
        } else {
            return file_get_contents($nameFile);
        }

    }

    public function getDataUnitForLabel($id)
    {
        return ReceiptUnit::controlAccessUser()->where('id', '=', $id)
            ->leftJoin('systemcenterdistributions', 'receiptunits.centerid', '=', 'systemcenterdistributions.centerid')
            ->select([
                'receiptunits.id as folionumber',
                'receiptunits.barcode as code',
                'receiptunits.shipmentid as shipment',
                'systemcenterdistributions.description as avior',
            ])->first();
    }

    private function generateLabelOperator(StorageOperator $storageoperator)
    {
        $nameFile = "Label.txt";
        $date = Carbon::now()->format('d/m/Y');
        $file = @fopen($nameFile, "w");
        $detailsLabel = InventoryLabelDetail::getDetailForLabel('LABEL-OPR');
        foreach ($detailsLabel as $item) {
            if (str_contains($item->description, '(storageoperators.center)')) {
                $valor = str_replace('(storageoperators.center)', $storageoperator->center->description, $item->description);
            } elseif (str_contains($item->description, '(storageoperators.barcode)')) {
                $valor = str_replace('(storageoperators.barcode)', $storageoperator->barcode, $item->description);
            } elseif (str_contains($item->description, '(storageoperators.name)')) {
                $valor = str_replace('(storageoperators.name)', $storageoperator->name, $item->description);
            } elseif (str_contains($item->description, '(date)')) {
                $valor = str_replace('(date)', $date, $item->description);
            } else {
                $valor = $item->description;
            }
            fwrite($file, $valor . "\n");
        }
        fclose($file);
        return response()->download($nameFile)->deleteFileAfterSend();
    }

    private function generateLabelBox(StorageLabelTruck $storagelabeltruck)
    {
        $nameFile = "Label.txt";
        $date = Carbon::now()->format('d/m/Y');
        if ($storagelabeltruck->typeid == 'BX') {
            $type = 'CAJA';
        } else {
            $type = 'TRACTO';
        }
        $file = @fopen($nameFile, "w");
        $detailsLabel = InventoryLabelDetail::getDetailForLabel('LABEL-TRA');
        foreach ($detailsLabel as $item) {
            if (str_contains($item->description, '(storagelabeltruck.center)')) {
                $valor = str_replace('(storagelabeltruck.center)', $storagelabeltruck->center->description, $item->description);
            } elseif (str_contains($item->description, '(storagelabeltruck.barcode)')) {
                $valor = str_replace('(storagelabeltruck.barcode)', $storagelabeltruck->barcode, $item->description);
            } elseif (str_contains($item->description, '(storagelabeltruck.boxnumber)')) {
                $valor = str_replace('(storagelabeltruck.boxnumber)', $storagelabeltruck->boxnumber, $item->description);
            } elseif (str_contains($item->description, '(date)')) {
                $valor = str_replace('(date)', $date, $item->description);
            } elseif (str_contains($item->description, '(type)')) {
                $valor = str_replace('(type)', $type, $item->description);
            } else {
                $valor = $item->description;
            }
            fwrite($file, $valor . "\n");
        }
        fclose($file);
        return response()->download($nameFile)->deleteFileAfterSend();
    }




}
