<?php

namespace App\Http\Controllers\Yard\Carriers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Carriers\CreateCarrierRequest;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Traits\Web\General\StorageWebPhotoTrait;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShipmentCarrierController extends BaseController
{
    use StorageWebPhotoTrait;

    public function index()
    {
        $this->authorize('shipmentcarriers.index');
        $status = $this->getStatusSTS();

        return view('Yards.ShipmentCarriers.index', compact('status'));
    }

    public function getDataCarriers(Request $request)
    {
        $data = ShipmentCarrier::controlAccessUser()
            ->with(['center:centerid,name,description', 'status:statusid,name', 'warehouse:warehouseid,name'])
            ->Name($request->get('name'))
            ->Carrier($request->get('carrierid'))
            ->Status($request->get('statusid'))->get();

        return DataTables::of($data)
            ->addColumn('btn', 'Yards.ShipmentCarriers.partials.buttons')
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function create()
    {
        $this->authorize('shipmentcarriers.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view('Yards.ShipmentCarriers.create', compact('centers', 'warehouses'));
    }

    public function store(CreateCarrierRequest $request): RedirectResponse
    {
        ShipmentCarrier::create($request->validated());

        return $this->successResponse('shipmentcarriers.index');
    }

    public function edit(ShipmentCarrier $shipmentcarrier)
    {
        $this->authorize('shipmentcarriers.edit');
        $status = $this->getStatusSTS();

        return view('Yards.ShipmentCarriers.edit', compact('shipmentcarrier', 'status'));
    }

    public function update(ShipmentCarrier $shipmentcarrier, Request $request): RedirectResponse
    {
        $shipmentcarrier->update($request->all());
        if ($request->hasFile('imgqr')) {
            $path = $this->saveFileToModel($request->file('imgqr'), $shipmentcarrier->centerid, 'Yard', 'ShipmentCarrier', 'QR');
            $shipmentcarrier->auxiliary04 = $path;
            $shipmentcarrier->save();
        }
        if ($request->hasFile('photologo')) {
            $path = $this->saveFileToModel($request->file('photologo'), $shipmentcarrier->centerid, 'Yard', 'ShipmentCarrier', 'LOGO');
            $shipmentcarrier->auxiliary05 = $path;
            $shipmentcarrier->save();
        }

        return $this->infoResponse('shipmentcarriers.index');
    }

    public function destroy(Request $request): JsonResponse
    {
        $this->authorize('shipmentcarriers.destroy');
        $shipmentcarrier = ShipmentCarrier::controlAccessUser()->findOrFail($request->id);
        $shipmentcarrier->statusid = 'D';
        $shipmentcarrier->delete();

        return $this->responseSuccessAjax('', Carbon::now());
    }
}
