<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\CircuitProcess;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\GenerateAccessWarehouse\CreateAccessWarehouseQrRequest;
use App\Models\Yard\GenerateAccessWarehouse;
use App\Traits\PrintFormat;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class GenerateAccessWarehouseController extends BaseController
{

    use PrintFormat;

    public function index()
    {
        $this->authorize('generateaccesswarehouse.index');
        $carriers = $this->getCarriersForSelect();
        return view('Yards.GenerateAccess.index', compact('carriers'));
    }

    public function getAccessWarehouse(Request $request)
    {
        $this->authorize('generateaccesswarehouse.index');
        $carrier = $this->getCarrierForUser();
        $data = GenerateAccessWarehouse::controlAccessUser()
            ->leftJoin('storageoperators as so', 'storageaccesswarehouse.operatorid', '=', 'so.id')
            ->leftJoin('storagelabeltrucks as sl', 'storageaccesswarehouse.boxid', '=', 'sl.id')
            ->leftJoin('storagelabeltrucks as st', 'storageaccesswarehouse.truckid', '=', 'st.id')
            ->leftJoin('systemstatus as se', 'storageaccesswarehouse.statusid', '=', 'se.statusid')
            ->leftJoin('shipmentcarriers as sc', 'sl.carrierid', '=', 'sc.id')
            ->leftJoin('users', 'storageaccesswarehouse.createuser', '=', 'users.id')
            ->where('storageaccesswarehouse.statusid', '=', 'AC')
            ->ForCarriers($carrier)
            ->ForName($request->get('name'))
            ->ForBox($request->get('boxnumber'))
            ->ForCarrierFront($request->get('carrierid'))
            ->select([
                'so.name as operador', 'so.drivernumber', 'so.phone',
                'storageaccesswarehouse.statusid', 'sc.name as carrier', 'sl.boxnumber as caja', 'st.boxnumber as tracto', 'se.name as status',
                'storageaccesswarehouse.datestart', 'storageaccesswarehouse.datefinish', 'storageaccesswarehouse.id', 'storageaccesswarehouse.shipmentid',
                'storageaccesswarehouse.confirmationnumber', 'users.name as createuser', 'storageaccesswarehouse.created_at as created'
            ]);
        return DataTables::of($data)
            ->addColumn('btn', 'Yards.GenerateAccess.partials.buttons')
            ->rawColumns(['btn'])->toJson();
    }

    public function create()
    {
        $this->authorize('generateaccesswarehouse.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $operators = $this->getOperatorForCarriers();
        $boxes = $this->getBoxForCarrier();
        $trucks = $this->getTruckForCarrier();
        return view('Yards.GenerateAccess.create',
            compact('centers', 'operators', 'boxes', 'trucks', 'warehouses'));
    }

    public function store(CreateAccessWarehouseQrRequest $request)
    {
        $access = new GenerateAccessWarehouse($request->validated());
        $access->auxiliary01 = bin2hex(random_bytes(30));
        $access->save();
        return $this->redirectBackSuccess();
    }

    public function edit(GenerateAccessWarehouse $generateaccesswarehouse)
    {
        $this->authorize('generateaccesswarehouse.edit');
        $operators = $this->getOperatorForCarriers();
        $boxes = $this->getBoxForCarrier();
        $trucks = $this->getTruckForCarrier();
        return view('Yards.GenerateAccess.edit', compact('operators',
            'boxes', 'trucks', 'generateaccesswarehouse'));
    }

    public function update(Request $request, GenerateAccessWarehouse $generateaccesswarehouse)
    {
        $generateaccesswarehouse->update($request->all());
        return $this->redirectBackSuccess();
    }

    public function generateAccessFormat($id)
    {
        return $this->printFormatAccess($id);
    }

    public function destroy($id)
    {
        $this->authorize('generateaccesswarehouse.destroy');
        GenerateAccessWarehouse::destroy($id);
        return $this->redirectBackSuccess();
    }


}
