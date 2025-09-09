<?php

namespace App\Traits\Web\Yard\ReceiptUnit\ArriveUnit;

use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageUnitTrace;
use App\Traits\ResponseApi;
use App\Traits\Web\General\Responser;

trait SaveArriveUnit
{
    use ResponseApi;
    use Responser;

    public function saveArriveUnit($receiptunit, $platform)
    {
        if ($platform == 'API') {
            if ($receiptunit == null) {
                return $this->getResponseWithErrors('registerArrive', 'No existen registros del codigo de barra ingresado.');
            }
            if ($receiptunit->statusid != 'QI') {
                return $this->getResponseWithErrors('registerArrive', 'La unidad se encuentra en estatus diferente ENTRADA AUTORIZADA.');
            }
            if ($receiptunit->deliverdate != null) {
                return $this->getResponseWithErrors('registerArrive', 'La unidad ya tiene registro de llegada.');
            }
        }
        if ($platform == 'WEB') {
            if ($receiptunit == null) {
                return $this->errorResponse('No existen registros del codigo de barra ingresado.');
            }
            if ($receiptunit->statusid != 'QI') {
                return $this->errorResponse('La unidad se encuentra en estatus diferente ENTRADA AUTORIZADA.');
            }
            if ($receiptunit->deliverdate != null) {
                return $this->errorResponse('La unidad ya cuenta con registro de Mesa de Control');
            }
        }
        $this->updateDataUnit($receiptunit);
        StorageUnitTrace::createTraceUnit($receiptunit, '10');
    }


    private function updateDataUnit(ReceiptUnit $receiptunit)
    {
        $receiptunit->deliverdate = now();
        $receiptunit->statusid = 'IN';
        $receiptunit->save();
    }


}
