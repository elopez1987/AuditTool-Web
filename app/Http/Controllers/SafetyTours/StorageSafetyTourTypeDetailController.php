<?php

namespace App\Http\Controllers\SafetyTours;

use App\Http\Controllers\BaseController;
use App\Models\SafetyTours\StorageSafetyTourTypeDetail;
use App\Models\StorageSafetyTourType;
use Illuminate\Http\Request;

class StorageSafetyTourTypeDetailController extends BaseController
{
    public function store(Request $request, $typeuuid)
    {
        $this->authorize('storagesafetytourtype.detail');
        $storageSafetyTourType = StorageSafetyTourType::controlAccessUser()->where('typeuuid', '=', $typeuuid)->first();
        if (empty($storageSafetyTourType)) {
            return $this->errorResponse('Tipo de recorrido no encontrado');
        }
        if ($request->has('tagid') && is_array($request->tagid)) {
            foreach ($request->tagid as $tagId) {
                StorageSafetyTourTypeDetail::create([
                    'centerid' => $storageSafetyTourType->centerid,
                    'typeid' => $storageSafetyTourType->id,
                    'tagid' => $tagId,
                ]);
            }
        }
        return $this->redirectBackSuccessMessage('Registro guardado con exito.');
    }

    public function destroy($id)
    {
        $detail = StorageSafetyTourTypeDetail::findOrFail($id);
        $detail->delete();
        return $this->redirectBackSuccessMessage('Registro eliminado con exito.');
    }

    public function restore($id)
    {
        $detail = StorageSafetyTourTypeDetail::withTrashed()->findOrFail($id);
        $detail->restore();
        return $this->redirectBackSuccessMessage('Registro restaurado con exito.');
    }

}
