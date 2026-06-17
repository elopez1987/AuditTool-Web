<?php

namespace App\Http\Controllers\SafetyTours\Incidents;

use App\Http\Controllers\BaseController;
use App\Models\SafetyTours\Incidents\StorageIncidenceSafety;
use Illuminate\Http\Request;

class StorageIncidenceSafetiesController extends BaseController
{
    public function index()
    {

        return view('SafetyTours.StorageIncidenceSafety.index');
    }

    public function getIncidenceSafeties(Request $request)
    {
        $data = StorageIncidenceSafety::controlAccessUser()
            ->with(['status:statusid,name,auxiliary01', 'category:id,name',
                'categorydetail:id,name', 'center:centerid,name,description',
                'createuser:id,name', 'closeduser:id,name'])
            ->get();

        return $this->showDataToView($data, 'SafetyTours.StorageIncidenceSafety.partials.buttons');
    }

    public function create() {}

    public function store(Request $request) {}

    public function show(StorageIncidenceSafety $storageIncidenceSafety) {}

    public function edit(StorageIncidenceSafety $storageIncidenceSafety) {}

    public function update(Request $request, StorageIncidenceSafety $storageIncidenceSafety) {}

    public function destroy(StorageIncidenceSafety $storageIncidenceSafety) {}
}
