<?php

namespace App\Http\ControllersApp\Shipment\OrderDelivery\Events;

use App\Http\Controllers\Controller;
use App\Models\General\EvidencePhoto;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\Api\General\EvidenceFileImage;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaveEvidenceInProcessLoadController extends Controller
{
    use ResponseApi;
    use EvidenceFileImage;

    public function index(Request $request)
    {
        DB::beginTransaction();
        try {
            if ($request['file'] == null || $request['shipmentid'] == null) {
                return $this->getResponseWithErrors('uploadEvidenceShipment', 'La foto y el embarque son valores obligatorios.');
            }
            $orderDelivery = OrderDeliverie::searchForShipment($request['shipmentid']);
            $this->createNewEvidence($request['file'], $orderDelivery, 'SH', 'OrderDelivery', 'Shipments', 'InProcess', $request);
            $totalphotos = EvidencePhoto::where('model_type', '=', 'OrderDelivery')->where('model_id', '=', $orderDelivery->id)->count('id');
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $error = $e->getMessage();
            DB::rollback();
        }
        if ($success) {
            return $this->getResponseSuccess('uploadEvidenceShipment', ['totalphotos' => $totalphotos]);
        } else {
            return $this->getResponseWithErrors('uploadEvidenceShipment', 'Error al procesar la solicitud,favor de intentarlo nuevamente');
        }
    }
}
