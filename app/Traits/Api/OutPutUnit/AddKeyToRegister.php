<?php

namespace App\Traits\Api\OutPutUnit;

use App\Models\Yard\ReceiptUnitKey;
use App\Traits\Api\General\SavePhotoInRegister;

trait AddKeyToRegister
{
    use SavePhotoInRegister;

    public function addKeyToRegister($receiptunit, $parameterApp, $request)
    {
        $unitKey = ReceiptUnitKey::controlAccessUser()
            ->where('folionumber', $receiptunit->id)->first();

        if (!$unitKey) {
            $unitKey->update([
                'containersealout' => $request['seal'] ?? '0',
                'nameoperator03' => $receiptunit->tracto ? $receiptunit->tracto->name : null,
                'nameguard03' => \Auth::user()->name,
            ]);
        }

        if (!empty($parameterApp) && in_array('APP-09', (array)$parameterApp)) {
            $validator = \Validator::make($request->all(), [
                'keyguard03' => 'required',
                'keyoperator03' => 'required',
            ]);
            if ($validator->fails()) {
                return $this->getResponseWithErrors('saveArriveUnitToCenter', 'La firmas son valores obligatorios.');
            }
            list($keyGuard03, $keyoperator03) = $this->saveKeysSupervisorAndGuard($request['keyguard03'], $request['keyoperator03'], $receiptunit);
            if (!empty($unitKey)) {
                $unitKey->keyguard03 = $keyGuard03;
                $unitKey->keyoperator03 = $keyoperator03;
                $unitKey->save();
            }
        }
    }

    public function saveKeysSupervisorAndGuard($keyGuard03, $keyoperator03, $folionumber): array
    {
        $image = $keyGuard03;
        $keyGuard03 = $this->createNewEvidenceForRegister($image, $folionumber, 'Yard', 'ReceiptUnit', 'Keys');
        $image2 = $keyoperator03;
        $keyoperator03 = $this->createNewEvidenceForRegister($image2, $folionumber, 'Yard', 'ReceiptUnit', 'Keys');
        return array($keyGuard03, $keyoperator03);
    }

}
