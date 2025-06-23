<?php

namespace App\Http\Controllers\Services\Yard;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Yard\ReceiptUnit\SaveSealToBoxRequest;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLoto\StorageTraceLoto;
use App\Models\Yard\StorageSealLoto;
use App\Traits\ResponseApi;

class StorageSealLotoController extends Controller
{
    use ResponseApi;

//        1-ASIGNAR
//        2-DESASIGNAR

    public function getSealActive()
    {
        $data = StorageSealLoto::where('statusid', 'E')->get();
        return $this->getResponseSuccess('getSealActive', $data);
    }
    
    public function saveSealToBox(SaveSealToBoxRequest $request)
    {
        $box = ReceiptUnit::searchForBarcode($request['barcode']);
        if (empty($box)) {
            return $this->getResponseWithErrors('saveSalToBox', 'No existe la caja buscada.');
        }
        $seal = StorageSealLoto::find($request->sealid);
        if ($request['typeid'] == 1) {
            $seal->boxnumber = $box->boxnumber;
            $seal->statusid = 'D';
            $seal->save();

            StorageTraceLoto::createNewEventToLoto($box, $seal, '34');
            return $this->getResponseWithSuccess('saveSalToBox', 'Sello asignado con exito.');
        }
        if ($request['typeid'] == 2) {
            $seal->boxnumber = '';
            $seal->statusid = 'E';
            $seal->save();

            StorageTraceLoto::createNewEventToLoto($box, $seal, '35');
            return $this->getResponseWithSuccess('saveSalToBox', 'Sello liberado con exito.');
        }
    }


}
