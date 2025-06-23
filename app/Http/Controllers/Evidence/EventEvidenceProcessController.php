<?php

namespace App\Http\Controllers\Evidence;

use App\Http\Controllers\BaseController;
use App\Models\Evidence\EvidenceContainer;
use App\Models\Evidence\StorageEvidenceProcess;
use PDF;

class EventEvidenceProcessController extends BaseController
{
    public function updateStatusShipment($id)
    {
        $shipment = StorageEvidenceProcess::controlAccessUser()->findOrFail($id);
        $shipment->statusid = 'D';
        $shipment->save();
        return $this->redirectBackSuccessMessage('Embarque cerrado con exito.');
    }

    public function generatePDF($id)
    {
        $model = StorageEvidenceProcess::findOrFail($id);
        $files = EvidenceContainer::where('shipmentid', '=', $id)->get();
        $fortmat = 'Evidence.StorageEvidences.partials.pdf';
        $pdf = PDF::loadView($fortmat, compact('model', 'files'));
        return $pdf->download('KARDEX-' . $model->shipmentid . '.pdf');
    }

    public function generatemapload($id)
    {
        $model = StorageEvidenceProcess::findOrFail($id);
        $containers = EvidenceContainer::where('shipmentid', '=', $id)
            ->orderBy('adddateload')
            ->get();
        $fortmat = 'Evidence.StorageEvidences.partials.mapload';
        $pdf = PDF::loadView($fortmat, compact('model', 'containers'));
        return $pdf->download('MAPA DE CARGA -' . $model->shipmentid . '.pdf');
    }


}
