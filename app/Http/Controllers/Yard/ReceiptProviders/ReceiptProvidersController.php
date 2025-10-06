<?php

namespace App\Http\Controllers\Yard\ReceiptProviders;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Providers\ReceiptProviderCreateRequest;
use App\Http\Requests\Web\Yard\Providers\UpdateProvidersRequest;
use App\Models\Receipt\ReceiptProvider;
use Illuminate\Http\Request;

class ReceiptProvidersController extends BaseController
{
    public function index()
    {
        $this->authorize('receiptproviders.index');
        $warehouses = $this->getWarehouseForSelect();

        return view('Yards.ReceiptProviders.index', compact('warehouses'));
    }

    public function getDataProviders(Request $request)
    {
        $data = ReceiptProvider::controlAccessUser()
            ->with(['status:statusid,name', 'center:centerid,description,name', 'warehouse:warehouseid,name'])
            ->Name($request->get('name'))
            ->Warehouse($request->get('warehouseid'))
            ->get();

        return $this->showDataToView($data, 'Yards.ReceiptProviders.partials.buttons');
    }

    public function create()
    {
        $this->authorize('receiptproviders.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view(
            'Yards.ReceiptProviders.create',
            compact('centers', 'warehouses')
        );
    }

    public function store(ReceiptProviderCreateRequest $request)
    {
        $provider = new ReceiptProvider($request->validated());
        $provider->save();

        return $this->successResponse('receiptproviders.index');
    }

    public function edit(ReceiptProvider $receiptprovider)
    {
        $this->authorize('receiptproviders.edit');
        $status = $this->getStatusSTS();

        return view(
            'Yards.ReceiptProviders.edit',
            compact('status', 'receiptprovider')
        );
    }

    public function update(UpdateProvidersRequest $request, ReceiptProvider $receiptprovider)
    {
        $receiptprovider->update($request->except('centerid', 'warehouseid'));

        return $this->infoResponse('receiptproviders.index');
    }
}
