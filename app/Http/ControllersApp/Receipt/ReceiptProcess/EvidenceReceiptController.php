<?php

namespace App\Http\ControllersApp\Receipt\ReceiptProcess;

use App\Http\Controllers\Controller;
use App\Models\Receipt\ReceiptAsn;
use App\Traits\Api\General\EvidenceFileImage;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;


class EvidenceReceiptController extends Controller
{
    use ResponseApi;
    use EvidenceFileImage;

    public function saveEvidenceImageInReceipt(Request $request)
    {
        if (empty($request['shipmentid'])){
            return  $this->getResponseWithErrors('saveEvidenceImageInReceipt','El número de embarque es obligatorio.');
        }
        if (empty($request['file'])){
            return  $this->getResponseWithErrors('saveEvidenceImageInReceipt','La imagen es obligatoria.');       
        }

        $receipt = $this->getReceiptAsn($request);
        if (empty($receipt)) {
            return $this->getResponseWithErrors('saveEvidenceImageInReceipt',
                'El número de recibo ingresado no existe,favor de validar.');
        }
        $this->createNewEvidence($request['file'], $receipt, 'DES', 'ReceiptAsn', 'Receipt', 'InProcess',$request);
        return $this->getResponseSuccess('saveEvidenceImageInReceipt', []);
    }

    public function getReceiptAsn($request)
    {
        return ReceiptAsn::controlAccessUser()
            ->whereIn('statusid', ['DE', 'TE'])
            ->where('shipmentid', $request['shipmentid'])
            ->first();
    }


}
