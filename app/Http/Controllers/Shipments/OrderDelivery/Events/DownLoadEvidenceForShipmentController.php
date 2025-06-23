<?php

namespace App\Http\Controllers\Shipments\OrderDelivery\Events;

use App\Http\Controllers\Controller;
use App\Models\General\EvidencePhoto;
use App\Models\Shipments\OrderDeliverie;

class DownLoadEvidenceForShipmentController extends Controller
{
    public function index($id)
    {
        $order = OrderDeliverie::findOrFail($id);
        $zip = new \ZipArchive();
        $zip->open($order->shipmentid . ".zip", \ZipArchive::CREATE);
        $files = EvidencePhoto::where('model_type', '=', 'OrderDelivery')
            ->where('auxiliary01', '=', 'SH')
            ->where('model_id', $order->id)->get();
        foreach ($files as $file) {
            $filepath = storage_path() . '/app/' . $file->path;
            $ultimo = strrpos($file->path, '/');
            $newPath = substr($file->path, $ultimo + 1);
            $zip->addFile($filepath, $newPath);
        }

        $zip->close();
        header("Content-type: application/octet-stream");
        header("Content-disposition: attachment; filename=$order->shipmentid.zip");
        readfile($order->shipmentid . '.zip');
        unlink($order->shipmentid . '.zip');
    }
}
