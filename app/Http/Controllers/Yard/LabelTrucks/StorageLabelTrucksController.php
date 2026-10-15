<?php

namespace App\Http\Controllers\Yard\LabelTrucks;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\LabelTruck\RegisterLabelRequest;
use App\Imports\Yard\ImportLabelTruck;
use App\Models\Security\Users\AccessCarrier;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageLabelTruck\StorageLabelTruck;
use App\Traits\Web\General\GenerateLabel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\Facades\DataTables;

class StorageLabelTrucksController extends BaseController
{
    use GenerateLabel;

    public function index()
    {
        $this->authorize('storagelabeltrucks.index');
        $carriers = $this->getCarriersForSelect();

        return view('Yards.StorageLabelTrucks.index', compact('carriers'));
    }

    public function getDataLabelsTrucks(Request $request)
    {
        $carrier = AccessCarrier::where('username', Auth::user()->username)
            ->select('carrierid')->get()->toArray();
        $data = StorageLabelTruck::controlAccessUser()
            ->with(['center', 'status', 'carrier'])
            ->select([
                'storagelabeltrucks.id',
                'storagelabeltrucks.centerid',
                'storagelabeltrucks.barcode',
                'storagelabeltrucks.carrierid',
                'storagelabeltrucks.boxnumber',
                'storagelabeltrucks.econumber',
            ])
            ->ForCarrierOperator($carrier)
            ->Label($request->input('label'))
            ->BoxNumber($request->input('boxnumber'))
            ->Carrier($request->input('carrierid'))
            ->Type($request->input('typeid'));

        return DataTables::of($data)
            ->addColumn('btn', 'Yards.StorageLabelTrucks.partials.buttons')
            ->rawColumns(['btn', 'type'])->toJson();
    }

    public function create()
    {
        $this->authorize('storagelabeltrucks.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $trucks = $this->shipmentTruckActiveToSelect();
        $unittype = $this->unitTypeActiveToSelect();
        $carriers = $this->getCarriersForTransport();

        return view(
            'Yards.StorageLabelTrucks.create',
            compact(
                'centers',
                'carriers',
                'trucks',
                'unittype',
                'warehouses',
            ),
        );
    }

    public function store(RegisterLabelRequest $request)
    {
        $exist = StorageLabelTruck::controlAccessUser()->where('boxnumber', $request['boxnumber'])->first();
        if (! empty($exist)) {
            return $this->errorResponse('La placa ya se encuentra registrado con el codigo : ' . $exist->barcode);
        }
        $barcode = $this->getFolioYMS($request['centerid']);
        $label = new StorageLabelTruck($request->validated() + ['barcode' => $barcode->folionumber]);
        $label->save();

        return $this->redirectBackSuccess();
    }

    public function edit(StorageLabelTruck $storagelabeltruck)
    {
        $this->authorize('storagelabeltrucks.edit');
        $trucks = $this->shipmentTruckActiveToSelect();
        $unittypes = $this->unitTypeActiveToSelect();
        $carriers = $this->getCarriersForSelect();

        return view(
            'Yards.StorageLabelTrucks.edit',
            compact('storagelabeltruck', 'carriers', 'trucks', 'unittypes'),
        );
    }

    public function update(Request $request, StorageLabelTruck $storagelabeltruck)
    {
        $storagelabeltruck->update($request->except('centerid', 'warehouseid'));

        ReceiptUnit::controlAccessUser()
            ->where('boxnumber', $storagelabeltruck->boxnumber)
            ->whereNotIn('statusid', ['TR'])
            ->update([
                'barcode' => $storagelabeltruck->barcode,
            ]);

        return $this->redirectBackInfo();
    }

    public function destroy(StorageLabelTruck $storagelabeltruck)
    {
        $this->authorize('storagelabeltrucks.destroy');
        $storagelabeltruck->delete();

        return $this->redirectBackSuccess();
    }

    public function printlabeltruck(StorageLabelTruck $storagelabeltruck)
    {
        return $this->generateLabelBox($storagelabeltruck);
    }

    public function importlabels(Request $request)
    {
        Excel::import(new ImportLabelTruck, $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);

        return $this->redirectBackSuccessMessage('Archivo cargado con exito');
    }
}
