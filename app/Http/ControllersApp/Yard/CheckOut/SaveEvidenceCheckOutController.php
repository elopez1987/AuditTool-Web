<?php

namespace App\Http\ControllersApp\Yard\CheckOut;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\General\EvidencePhoto;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Api\General\EvidenceFileImage;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;

class SaveEvidenceCheckOutController extends Controller
{
    use ResponseApi;
    use EvidenceFileImage;

    public function index(Request $request)
    {
        $file = $request['file'] ?? '';
        $barcode = $request['barcode'] ?? '';
        if ($file === '' || $barcode === '') {
            return $this->getResponseWithErrors('saveEvidenceInOutUnit', 'No se ha adjuntado ningún archivo.');
        }
        $receiptUnit = ReceiptUnit::searchForBarcode($barcode);
        if (empty($receiptUnit)) {
            return $this->getResponseWithErrors('saveEvidenceInOutUnit', 'No existe el número de folio buscado.');
        }
        $totalFotosParameter = SystemDetailParameter::where('parameterid', 'PARAM-FOTOS')->where('value01', $receiptUnit->centerid)->first();
        if (empty($totalFotosParameter)) {
            return $this->getResponseWithErrors('saveEvidenceInOutUnit', 'El parametro PARAM-FOTOS no se encuentra configurado.');
        }
        $totalFotos = EvidencePhoto::where('model_type', '=', 'ReceiptUnit')->where('model_id', '=', $receiptUnit->id)->where('auxiliary01', 'OUT')->count('model_id');
        if ($totalFotosParameter->value02 == $totalFotos) {
            return $this->getResponseWithErrors('saveEvidenceInOutUnit', 'Ya se tomaron todas las fotos permitidas.');
        }
        $this->createNewEvidence($request['file'],$receiptUnit,'OUT','ReceiptUnit','Yard','CheckOut',$request);
        return $this->getResponseWithSuccess('saveEvidenceInOutUnit', 'Evidencia guardado con exito.');
    }
}
