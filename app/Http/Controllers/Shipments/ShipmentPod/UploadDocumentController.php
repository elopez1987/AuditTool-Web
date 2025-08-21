<?php

namespace App\Http\Controllers\Shipments\ShipmentPod;

use App\Http\Controllers\Controller;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\ShipmentPod\ShipmentPod;
use App\Models\Shipments\StorageShipmentsPods;
use App\Traits\Web\General\EvidencePhotoTrait;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Pion\Laravel\ChunkUpload\Handler\HandlerFactory;
use Pion\Laravel\ChunkUpload\Receiver\FileReceiver;
use Storage;

class UploadDocumentController extends Controller
{
    use EvidencePhotoTrait;

    public function index(Request $request, $id)
    {
        $receiver = new FileReceiver("file", $request, HandlerFactory::classFromRequest($request));
        if ($receiver->isUploaded() === false) {
            throw new UploadMissingFileException();
        }
        $save = $receiver->receive();
        if ($save->isFinished()) {
            return $this->saveFile($save->getFile(), $id);
        }
        /** @var AbstractHandler $handler */
        $handler = $save->handler();

        return response()->json([
            "done" => $handler->getPercentageDone(),
            'status' => true
        ]);

    }


    protected function saveFile($file, $id)
    {
        $year = Carbon::now()->year;
        $month = Carbon::now()->month;
        $shipment = OrderDeliverie::findOrFail($id);
        $nameOriginal = str_replace(" ", "_", $file->getClientOriginalName());
        $nombre = Str::random(10) . $nameOriginal;
        $pathDirectory = 'public/' . $shipment->centerid . '/' . $year . '/' . $month . '/EvidencePod/';
        if (!Storage::disk('local')->exists($pathDirectory)) {
            Storage::disk('local')->makeDirectory($pathDirectory);
        }
        $pathbase = Storage::disk('local')->put($pathDirectory . $nombre, File::get($file));
        if ($pathbase) {
            $path = $pathDirectory . $nombre;
            $this->createNewRegister($shipment, $path);
        }
        $this->updateShipmentPodStatus($id);
    }


    static function createNewRegister($shipment, $file)
    {
        StorageShipmentsPods::create([
            'centerid' => $shipment->centerid,
            'warehouseid' => $shipment->warehouseid,
            'orderid' => $shipment->id,
            'pathfile' => $file,
            'createuser' => Auth::user()->username,
        ]);
    }

    /**
     * @param $id
     * @return void
     */
    public function updateShipmentPodStatus($id): void
    {
        $shipmentpod = ShipmentPod::controlAccessUser()->where('orderid', $id)->first();
        $shipmentpod->statusid = 'CON';
        $shipmentpod->save();
    }

}
