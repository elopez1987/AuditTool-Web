<?php

namespace App\Services\Shipment;

use App\Models\Shipments\ShipmentContainer;
use App\Traits\Web\General\EvidencePhotoTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class ServiceShipmentContainer
{
    use EvidencePhotoTrait;

    public function updateContainerForLoad($container)
    {
        $container->scanuserload = Auth::user()->username;
        $container->scandateload = Carbon::now();
        $container->statusid = 'SC';
        $container->save();
    }

    public function getTotalContainerForStatusSC($orderid)
    {
        return ShipmentContainer::where('orderid', $orderid)
            ->where('statusid', '=', 'SC')->get()->count();
    }

    public function createNewFileEvidence($file, $shipment)
    {
        $image = $file;
        $image = str_replace('data:image/png;base64,', '', $image);
        $image = str_replace(' ', '+', $image);
        $imageName = $shipment->shipmentid . '-' . Str::random(15) . '.png';
        File::put(public_path('storage/public/' . $shipment->centerid . '/EvidenceShipment/' . $imageName), base64_decode($image));
        $path = 'public/' . $shipment->centerid . '/EvidenceShipment/' . $imageName;
        $this->createEvidencePhoto('OrderDelivery', $shipment->id, $path, 'SH', $shipment);
    }
}
