<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Labels\InventoryLabelDetail;
use App\Models\Yard\StorageLocation;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class PrintLabelLocationController extends Controller
{
    use ResponseApi;

    public function printlabellocation(Request $request)
    {
        $nameFile = "LabelLocation.txt";
        $file = @fopen($nameFile, "w");
        $detailsLabel = InventoryLabelDetail::getDetailForLabel('LABEL-UBI');

        if ($request['type'] == 1) {
            $locations = StorageLocation::controlAccessUser()->where('zoneid', $request['zoneid'])->get();
            foreach ($locations as $location) {
                foreach ($detailsLabel as $item) {
                    if (str_contains($item->description, '(storagelocations.barcode)')) {
                        $valor = str_replace('(storagelocations.barcode)', $location->barcode, $item->description);
                    } elseif (str_contains($item->description, '(storagelocations.zone)')) {
                        $valor = str_replace('(storagelocations.zone)', $location->zone->name, $item->description);
                    } elseif (str_contains($item->description, '(storagelocations.name)')) {
                        $valor = str_replace('(storagelocations.name)', $location->name, $item->description);
                    } else {
                        $valor = $item->description;
                    }
                    fwrite($file, $valor . "\n");
                }
            }
        }
        if ($request['type'] == 2) {
            $location = StorageLocation::controlAccessUser()->where('id', $request['locationid'])->first();
            foreach ($detailsLabel as $item) {
                if (str_contains($item->description, '(storagelocations.barcode)')) {
                    $valor = str_replace('(storagelocations.barcode)', $location->barcode, $item->description);
                } elseif (str_contains($item->description, '(storagelocations.zone)')) {
                    $valor = str_replace('(storagelocations.zone)', $location->zone->name, $item->description);
                } elseif (str_contains($item->description, '(storagelocations.name)')) {
                    $valor = str_replace('(storagelocations.name)', $location->name, $item->description);
                } else {
                    $valor = $item->description;
                }
                fwrite($file, $valor . "\n");
            }
        }
        fclose($file);
        $dataZpl = file_get_contents($nameFile);
        return $this->getResponseSuccess('printlabellocation', ['dataZpl' => $dataZpl]);
    }


}
