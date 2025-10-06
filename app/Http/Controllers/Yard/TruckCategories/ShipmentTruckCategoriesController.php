<?php

namespace App\Http\Controllers\Yard\TruckCategories;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\TruckCategory\CreateTruckCategory;
use App\Models\Yard\ShipmentTruckCategory;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class ShipmentTruckCategoriesController extends BaseController
{
    public function index()
    {
        $this->authorize('shipmenttruckcategories.index');
        $status = $this->getStatusSTS();

        return view('Yards.ShipmentTruckCategories.index', compact('status'));
    }

    public function getDataTruckCategories(Request $request)
    {
        $data = ShipmentTruckCategory::with('status')
            ->Name($request->get('name'))
            ->Status($request->get('statusid'))->get();

        return DataTables::of($data)
            ->addColumn('btn', 'Yards.ShipmentTruckCategories.partials.buttons')
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function create()
    {
        $this->authorize('shipmenttruckcategories.create');

        return view('Yards.ShipmentTruckCategories.create');
    }

    public function store(CreateTruckCategory $request)
    {
        $truckcategory = new ShipmentTruckCategory($request->validated());
        $truckcategory->save();

        return $this->redirectBackInfo();
    }

    public function edit(ShipmentTruckCategory $shipmenttruckcategory)
    {
        $this->authorize('shipmenttruckcategories.edit');
        $status = $this->getStatusSTS();

        return view(
            'Yards.ShipmentTruckCategories.edit',
            compact('status', 'shipmenttruckcategory')
        );
    }

    public function update(Request $request, ShipmentTruckCategory $shipmenttruckcategory)
    {
        $shipmenttruckcategory->update($request->except('truckcategoryid'));

        return $this->redirectBackInfo();
    }
}
