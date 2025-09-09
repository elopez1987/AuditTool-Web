<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Evidences;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Shipments\Evidence\UploadImageRequest;
use App\Models\Yard\ReceiptUnit;
use App\Traits\Web\General\StorageWebPhotoTrait;

class EvidenceCheckInProcessController extends BaseController
{
    use StorageWebPhotoTrait;

//    TODO Validar si la foto se guarda en la tabla
    public function uploadphotocheckin(UploadImageRequest $request, $id)
    {
        $receiptunit = ReceiptUnit::findOrFail($id);
        $path = $this->saveFileToModel($request['file'], $receiptunit->centerid, 'Yard', 'ReceiptUnit', 'CheckIn');
        $this->createEvidencePhoto('ReceiptUnit', $receiptunit->id, $path, 'IN', $receiptunit);
    }


}
