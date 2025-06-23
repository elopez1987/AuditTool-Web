<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Evidences;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptUnit;
use App\Traits\General\Responser;
use App\Traits\Web\General\StorageWebPhotoTrait;
use Illuminate\Http\Request;

class SaveEvidenceFromStockWebController extends Controller
{
    use Responser;
    use StorageWebPhotoTrait;

    public function index(Request $request,$id)
    {
        $receiptunit = ReceiptUnit::controlAccessUser()->where('id', $id)->first();
        $path = $this->saveFileToModel($request['file'], $receiptunit->centerid,'Yard','ReceiptUnit','InProcess');
        $this->createEvidencePhoto('ReceiptUnit',$receiptunit->id,$path,'IP',$receiptunit);
    }


}
