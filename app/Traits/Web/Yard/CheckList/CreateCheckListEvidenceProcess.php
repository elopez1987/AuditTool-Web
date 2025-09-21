<?php

namespace App\Traits\Web\Yard\CheckList;

use App\Models\Yard\CheckListEvidenceProcessUnit;

trait CreateCheckListEvidenceProcess
{
    public function createNewCheckList($folionumber, $request)
    {
        CheckListEvidenceProcessUnit::create([
            'centerid' => $folionumber->centerid,
            'modelid' => $folionumber->folionumber,
            'modeltype' => 'ReceiptUnits',
            'description' => 'Caja vacía',
        ]);

        for ($i = 1; $i <= $request['ntiros']; $i++) {
            CheckListEvidenceProcessUnit::create([
                'centerid' => $folionumber->centerid,
                'modelid' => $folionumber->folionumber,
                'modeltype' => 'ReceiptUnits',
                'description' => 'Tiro_' . $i,
            ]);
        }
        CheckListEvidenceProcessUnit::create([
            'centerid' => $folionumber->centerid,
            'modelid' => $folionumber->folionumber,
            'modeltype' => 'ReceiptUnits',
            'description' => 'Fin de carga',
        ]);
        CheckListEvidenceProcessUnit::create([
            'centerid' => $folionumber->centerid,
            'modelid' => $folionumber->folionumber,
            'modeltype' => 'ReceiptUnits',
            'description' => 'Sellos',
        ]);
        CheckListEvidenceProcessUnit::create([
            'centerid' => $folionumber->centerid,
            'modelid' => $folionumber->folionumber,
            'modeltype' => 'ReceiptUnits',
            'description' => 'Caja cerrada',
        ]);
    }
}
