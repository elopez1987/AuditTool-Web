<?php

namespace App\Http\Controllers\Shipments\OrderDelivery\Events;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use Carbon\Carbon;
use File;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Storage;

class UploadDocumentToShipmentController extends BaseController
{
    public function uploadfile($id, Request $request)
    {
        $shipment = OrderDeliverie::findOrFail($id);
        $pathdb = $this->addFileToModel($request, $shipment);
        $shipment->evidencefile = $pathdb;
        $shipment->save();

        return $this->redirectBackSuccessMessage('Archivo cargado con exito!!');
    }

    private function addFileToModel(Request $request, $shipment): string
    {
        $year = Carbon::now()->year;
        $file = $request->file('file');
        $nombre = $shipment->shipmentid . '_' . Str::random(10) . '_' . $request->file('file')->getClientOriginalName();
        $directory = "public/{$shipment->centerid}/{$year}/OrderDeliverie/EvidenceShipment/";
        $filePath = $directory . $nombre;
        if (! Storage::exists($directory)) {
            Storage::makeDirectory($directory, 0755, true);
        }
        Storage::disk('local')->put($filePath, File::get($file));

        return $filePath;
    }
}
