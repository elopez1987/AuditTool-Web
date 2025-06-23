<?php

namespace App\Http\ControllersApp\Inverse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Inverse\SaveEvidenceInverseRequest;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\Api\General\EvidenceFileImage;
use App\Traits\ResponseApi;

class SaveEvidenceProcessController extends Controller
{

    use ResponseApi;
    use EvidenceFileImage;

    public function saveEvidenceInReceiptInverse(SaveEvidenceInverseRequest $request)
    {
        $shipment = $this->getDeliverie($request['shipmentid']);
        if (empty($shipment)) {
            return $this->getResponseWithErrors('saveEvidenceInReceiptInverse', 'El embarque no se encuentra registrado en la base de datos.');
        }
        $this->createNewEvidence($request['file'], $shipment, 'DEV', 'StorageScheduleInverse', 'Shipments', 'InProcess', $request);
        return $this->getResponseSuccess('saveEvidenceInReceiptInverse', []);
    }

    public function getDeliverie($shipmentid)
    {
        return OrderDeliverie::controlAccessUser()
            ->where('statusid', 'DH')->where('shipmentid', '=', $shipmentid)->first();
    }

}
