<?php

namespace App\Http\Controllers\Yard\GpsDevice;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Yard\GpsDeviceRequest;
use App\Http\Requests\Yard\GpsDeviceSearchRequest;
use App\Http\Requests\Yard\GpsDeviceUpdateRequest;
use App\Models\Yard\GpsDevice;
use App\Services\Yard\GpsDeviceService;
use App\ViewModels\Yard\GpsDeviceViewModel;
use Yajra\DataTables\Facades\DataTables;

class GpsDeviceController extends BaseController
{
    public function __construct(
        private readonly GpsDeviceService $deviceService,
        private readonly GpsDeviceViewModel $deviceViewModel,
    ) {}

    public function index()
    {
        return view('Yards.GpsDevices.index');
    }

    public function getGpsDevices(GpsDeviceSearchRequest $request)
    {
        $data = $this->deviceService->all($request->validated());

        return DataTables::eloquent($data)
            ->addColumn('btn', 'Yards.GpsDevices.partials.buttons')
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function create()
    {
        return view('Yards.GpsDevices.create',
            $this->deviceViewModel->index(),
        );
    }

    public function store(GpsDeviceRequest $request)
    {
        $this->deviceService->store($request->validated());

        return $this->redirectBackSuccessMessage('Dispositivo agregado con exito.');
    }

    public function show(GpsDevice $gps_device) {}

    public function edit(GpsDevice $gps_device)
    {
        return view('Yards.GpsDevices.edit', compact('gps_device'));
    }

    public function update(GpsDeviceUpdateRequest $request, GpsDevice $gps_device)
    {
        $this->deviceService->update($request->validated(), $gps_device);

        return $this->redirectBackInfo();
    }

    public function destroy(GpsDevice $gps_device) {}
}
