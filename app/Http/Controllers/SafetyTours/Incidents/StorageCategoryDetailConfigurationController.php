<?php

namespace App\Http\Controllers\SafetyTours\Incidents;

use App\Http\Controllers\BaseController;
use App\Models\SafetyTours\Incidents\StorageSafetyCategoryDetail;
use App\Models\SafetyTours\Incidents\StorageSafetyResponsibleConfiguration;
use Illuminate\Http\Request;

class StorageCategoryDetailConfigurationController extends BaseController
{
    public function update(Request $request, $id)
    {

        $detail = StorageSafetyCategoryDetail::create([
            'storage_category_safety_id' => $id,
            'name' => $request->name,
            'storage_safety_responsible_area_id' => $request->storage_safety_responsible_area_id,
        ]);

        StorageSafetyResponsibleConfiguration::create([
            'storage_safety_category_detail_id' => $detail->id,
            'level01' => $request->level01,
            'timeout01' => $request->timeout01,
            'attempts01' => $request->attempts01,
            'level02' => $request->level02,
            'timeout02' => $request->timeout02,
            'attempts02' => $request->attempts02,
            'level03' => $request->level03,
            'timeout03' => $request->timeout03,
            'attempts03' => $request->attempts03,
        ]);

        return $this->redirectBackSuccessMessage('Configuración actualizada');
    }
}
