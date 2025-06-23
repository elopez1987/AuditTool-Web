<?php

namespace App\Http\ControllersApp\SafetyTours;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Labels\InventoryLabel;
use App\Models\Configuration\Labels\InventoryLabelDetail;
use App\Models\SafetyTours\StorageTags;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class LabelSafetyController extends Controller
{
    use ResponseApi;

    public function index(Request $request)
    {
        if (empty($request['tagid'])) {
            return $this->getResponseWithErrors('printlabelsafetytout', 'El número de etiqueta es obligatorio');
        }
        $tag = StorageTags::controlAccessUser()->where('tagid', $request['tagid'])->first();
        if (empty($tag)) {
            return $this->getResponseWithErrors('printlabelsafetytout', 'La etiqueta no existe, favor de validar.');
        }
        $label = InventoryLabel::controlAccessUser()->where('auxiliary01', 'LABEL-TAG')->first();
        if (empty($label)) {
            return $this->getResponseWithErrors('printlabelsafetytout', 'La etiqueta no existe, favor de validar.');
        }
        $dataZpl = $this->generateLabels($tag);
        return $this->getResponseSuccess('printLabelForEntry', ['dataZpl' => $dataZpl]);
    }

    public function generateLabels($tag)
    {
        $nameFile = "Label.txt";
        $file = @fopen($nameFile, "w");
        $detailsLabel = InventoryLabelDetail::getDetailForLabel('LABEL-TAG');
        foreach ($detailsLabel as $item) {
            if (str_contains($item->description, '$tagid')) {
                $valor = str_replace('$tagid', $tag->tagid, $item->description);
            } elseif
            (str_contains($item->description, '$name')) {
                $valor = str_replace('$name', $tag->name, $item->description);
            } else {
                $valor = $item->description;
            }
            fwrite($file, $valor . "\n");
        }
        fclose($file);
        return file_get_contents($nameFile);
    }


}
