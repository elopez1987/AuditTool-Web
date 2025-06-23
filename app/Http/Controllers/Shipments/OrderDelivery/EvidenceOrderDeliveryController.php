<?php

namespace App\Http\Controllers\Shipments\OrderDelivery;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\OrderDeliverie;
use App\Traits\Web\General\StorageWebPhotoTrait;
use Illuminate\Http\Request;

class EvidenceOrderDeliveryController extends BaseController
{
    use StorageWebPhotoTrait;

    public function index($id)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('auxiliary05', $id)->first();
        return view('Shipments.OrderDeliveries.EvidenceProcess.index', compact('orderDelivery'));
    }


    public function loadevidenceorder(Request $request, $id)
    {
        $orderDelivery = OrderDeliverie::controlAccessUser()->where('id', $id)->first();
        $path = $this->saveFileToModel($request->file('file'), $orderDelivery->centerid, 'Shipments', 'OrderDelivery', 'InProcess');
        $this->createEvidencePhoto('OrderDelivery',$orderDelivery->id, $path,'SH',$orderDelivery);
    }


}
