<?php

namespace App\Http\Controllers\Shipments\OrderDelivery\Events;

use App\Http\Controllers\BaseController;
use App\Models\General\EvidencePhoto;
use App\Models\Shipments\OrderDeliverie;

class DownLoadEvidenceForShipmentController extends BaseController
{
    public function index($id)
    {
        $order = OrderDeliverie::findOrFail($id);

        $files = EvidencePhoto::where('model_type', 'OrderDelivery')
            ->where('auxiliary01', 'SH')
            ->where('model_id', $order->id)
            ->get();

        if ($files->isEmpty()) {
            return $this->errorResponse('El embarque no cuenta con evidencias para descargar.');
        }

        if (! file_exists(storage_path('app/tmp'))) {
            mkdir(storage_path('app/tmp'), 0755, true);
        }

        $zipFileName = $order->shipmentid . '_' . uniqid() . '.zip';
        $zipPath = storage_path('app/tmp/' . $zipFileName);

        $zip = new \ZipArchive;

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) !== true) {
            return $this->errorResponse('No se pudo crear el archivo ZIP.');
        }

        $addedAny = false;

        foreach ($files as $file) {
            $filepath = storage_path('app/' . $file->path);

            if (! file_exists($filepath)) {
                continue;
            }

            $zip->addFile($filepath, basename($file->path));
            $addedAny = true;
        }

        $zip->close();

        if (! $addedAny || ! file_exists($zipPath)) {
            return $this->errorResponse('El embarque no cuenta con evidencias válidas para descargar.');
        }

        return response()->download($zipPath, $order->shipmentid . '.zip')
            ->deleteFileAfterSend(true);
    }
}
