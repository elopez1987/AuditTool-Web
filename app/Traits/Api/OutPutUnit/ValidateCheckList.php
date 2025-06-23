<?php

namespace App\Traits\Api\OutPutUnit;

trait ValidateCheckList
{
    public function validateCheckList($receiptunit,$parameterApp,$request,$parameterWarehouse)
    {
        if (!empty($parameterApp) && in_array('APP-04', (array)$parameterApp)) {
            $this->clearCheckListTransport($receiptunit->id, 'OUT');
            $this->createCheckListToUnitV2($request, $receiptunit, 'OUT');
            if (in_array('SWH-10', (array)$parameterWarehouse)) {
                $difference = $this->validateCheckListV2($receiptunit, 'OUT');
                if ($difference > '0') {
                    return $this->getResponseWithErrors('registeroutunit', 'La unidad no cumple con el check list de salida.');
                }
            }
        }
    }
}
