<?php

namespace App\Http\Controllers\Configuration\Labels;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Labels\InventoryLabel;
use App\Models\Configuration\Labels\InventoryLabelDetail;
use Illuminate\Http\Request;

class InventoryLabelDetailsController extends BaseController
{
    public function addDetailLabel($id)
    {
        $this->authorize('inventorylabels.addDetailLabel');
        $label = InventoryLabel::findOrFail($id);
        $labelDetail = InventoryLabelDetail::where('labelid', '=', $id)
            ->select(['id', 'labelid', 'description'])->get();
        return view('Configuration.InventoryLabels.createdetails',
            compact('label', 'labelDetail'));
    }

    public function storeLabelDetail($id, Request $request)
    {
        $this->deleteDetailLabel($id);
        $descriptions = $request->input('description');
        if (!empty($descriptions)) {
            foreach ($descriptions as $description) {
                if (!empty($description)) {
                    InventoryLabelDetail::create([
                        'labelid' => $id,
                        'description' => $description
                    ]);
                }
            }
        }
        return $this->redirectBackSuccessMessage('Detalle agregado con exito');
    }

    /**
     * @param $id
     * @return void
     */
    public function deleteDetailLabel($id): void
    {
        $labelDetails = InventoryLabelDetail::where('labelid', '=', $id)->pluck('id')->toArray();
        InventoryLabelDetail::destroy($labelDetails);
    }
}
