<?php

namespace App\Http\Controllers\Configuration\Warehouses;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\Warehouse\CreateWarehouseRequest;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Traits\Web\General\EvidencePhotoTrait;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SystemWarehouseController extends BaseController
{
    use EvidencePhotoTrait;

    public function index()
    {
        $this->authorize('systemwarehouses.index');
        return view("Configuration.SystemWarehouses.index");
    }

    public function getDataWarehouses(Request $request)
    {
        $data = $this->getAllWarehouses($request);
        return $this->showDataToView($data, "Configuration.SystemWarehouses.partials.buttons");
    }

    public function create()
    {
        $this->authorize('systemwarehouses.create');
        $centers = $this->getCenters();
        return view("Configuration.SystemWarehouses.create", compact('centers'));
    }

    public function store(CreateWarehouseRequest $request): RedirectResponse
    {
        $warehouse = new  SystemWarehouse($request->all());
        $warehouse->save();
        return $this->successResponse("systemwarehouses.index");
    }

    public function edit(SystemWarehouse $systemwarehouse)
    {
        $this->authorize('systemwarehouses.edit');
        $status = $this->getStatusForSchema('STS');
        return view("Configuration.SystemWarehouses.edit",
            compact('status', 'systemwarehouse'));
    }

    public function update(Request $request, SystemWarehouse $systemwarehouse): RedirectResponse
    {
        $systemwarehouse->update($request->except('centerid'));
        if ($request->hasFile('file')) {
            if (Storage::exists('storage/' . $systemwarehouse->auxiliary09)) {
                Storage::delete('storage/' . $systemwarehouse->auxiliary09);
            }
            $path = $this->addMediaToModel($request, 'ImageProfile');
            $systemwarehouse->auxiliary09 = $path;
            $systemwarehouse->save();
        }
        return $this->infoResponse("systemwarehouses.index");
    }

    public function getAllWarehouses($request)
    {
        return SystemWarehouse::controlAccessUser()
            ->leftJoin('systemstatus', 'systemwarehouses.statusid', 'systemstatus.statusid')
            ->leftJoin('systemcenterdistributions', 'systemwarehouses.centerid', 'systemcenterdistributions.centerid')
            ->select([
                'systemcenterdistributions.name as centername',
                'systemwarehouses.warehouseid',
                'systemwarehouses.name',
                'systemwarehouses.center',
                'systemwarehouses.auxiliary01',
                'systemwarehouses.auxiliary02',
                'systemwarehouses.auxiliary03',
                'systemwarehouses.auxiliary04',
                'systemwarehouses.auxiliary05',
                'systemstatus.name as status'
            ])
            ->ForName($request->get('name'));
    }





}
