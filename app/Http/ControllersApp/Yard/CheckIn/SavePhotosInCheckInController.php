<?php

namespace App\Http\ControllersApp\Yard\CheckIn;

use App\Http\Controllers\Controller;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\General\EvidencePhoto;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Api\General\EvidenceFileImage;
use App\Traits\ResponseApi;
use DB;
use Illuminate\Http\Request;
use Log;

class SavePhotosInCheckInController extends Controller
{
    use ResponseApi;
    use EvidenceFileImage;


    public function index(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request['file'] == null || $request['folioid'] == null) {
                return $this->getResponseWithErrors('uploadEvidenceCheckInUnit', 'La foto y el número de folio son valores obligatorios.');
            }
            $receiptUnit = ReceiptUnit::searchForId($request['folioid']);
            if (empty($receiptUnit)) {
                return $this->getResponseWithErrors('uploadEvidenceCheckInUnit', 'No existe un registro con el folio ingresado.');
            }
            $totalFotosParameter = SystemDetailParameter::where('parameterid', 'PARAM-FOTOS')->where('value01', $receiptUnit->centerid)->first();
            if (empty($totalFotosParameter)) {
                return $this->getResponseWithErrors('uploadEvidenceCheckInUnit', 'El parametro PARAM-FOTOS no se encuentra configurado.');
            }
            $totalFotos = EvidencePhoto::where('model_type', '=', 'ReceiptUnit')->where('model_id', '=', $receiptUnit->id)->where('auxiliary01', 'IN')->count('model_id');
            if ($totalFotosParameter->value02 == $totalFotos) {
                return $this->getResponseWithErrors('uploadEvidenceCheckInUnit', 'Ya se tomaron todas las fotos permitidas.');
            }
            $this->createNewEvidence($request['file'],$receiptUnit,'IN','ReceiptUnit','Yard','CheckIn',$request);
            DB::commit();
            return $this->getResponseSuccess('uploadEvidenceCheckInUnit', $totalFotos);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();
            return $this->getResponseWithErrors('uploadEvidenceCheckInUnit', 'Error al procesar la solicitud,favor de intentarlo nuevamente');
        }
    }


}
