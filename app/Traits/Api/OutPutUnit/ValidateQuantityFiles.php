<?php

namespace App\Traits\Api\OutPutUnit;

use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\General\EvidencePhoto;
use App\Models\Yard\ReceiptUnit;

trait ValidateQuantityFiles
{
    public function validateQuantityFiles($receiptunit,$parameterApp,)
    {
        // Obtiene el parámetro de fotos en función del centro
        $paramFhoto = SystemDetailParameter::where('parameterid', '=', 'PARAM-FOTOS')->where('value01', $receiptunit->centerid)->first();
        if (empty($paramFhoto)) {
            return $this->getResponseWithErrors('registeroutunit', 'No se encuentra configurado el número de fotos para la salida de la caja.');
        }
        if (!empty($parameterApp) && in_array('APP-03', (array)$parameterApp)) {
            $totalEvidences = $this->getTotalEvidences($receiptunit);
            if ($receiptunit->razonstatusid == 1 && $receiptunit->unitstatusid == 2 && $totalEvidences < $paramFhoto->value03) {
                return $this->getResponseWithErrors('registeroutunit', 'Son necesarias ' . $paramFhoto->value03 . ' fotografías de evidencia de salida.');
            }
            if ($receiptunit->unitstatusid == 1 && $totalEvidences == 0) {
                return $this->getResponseWithErrors('registeroutunit', 'Se requiere por lo menos la foto de la caja vacía.');
            }
        }
    }

    public function getTotalEvidences(ReceiptUnit $receiptunit)
    {
        $totalEvidences = EvidencePhoto::where('model_type', '=', 'ReceiptUnit')->where('model_id', '=', $receiptunit->id)
            ->where('auxiliary01', '=', 'OUT')->count();
        return $totalEvidences;
    }
}
