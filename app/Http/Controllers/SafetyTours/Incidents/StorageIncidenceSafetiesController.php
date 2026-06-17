<?php

namespace App\Http\Controllers\SafetyTours\Incidents;

use App\Http\Controllers\BaseController;
use App\Models\SafetyTours\Incidents\StorageIncidenceSafety;
use Illuminate\Http\Request;

class StorageIncidenceSafetiesController extends BaseController
{
    public function index()
    {
        $status_total = StorageIncidenceSafety::leftJoin('systemstatus as s', 'storage_incidence_safeties.statusid', '=', 's.statusid')
            ->groupBy('s.name')
            ->selectRaw('s.name, COUNT(s.statusid) as total_status')
            ->get();

        return view('SafetyTours.StorageIncidenceSafety.index', compact('status_total'));
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

    public function show(StorageIncidenceSafety $storage_incidence_safety) {}

    public function edit(StorageIncidenceSafety $storage_incidence_safety)
    {
        $status = $this->getStatusForSchema('IRS');

        return view(
            'SafetyTours.StorageIncidenceSafety.edit',
            compact('storage_incidence_safety', 'status')
        );
    }

    public function update(Request $request, StorageIncidenceSafety $storage_incidence_safety)
    {
        $storage_incidence_safety->update([
            'name' => $request->name,
            'description' => $request->description,
            'priority' => $request->priority,
            'location' => $request->location,
            'auxiliary01' => $request->auxiliary01,
            'auxiliary02' => $request->auxiliary02,
            'auxiliary03' => $request->auxiliary03,
            'statusid' => $request->statusid,
        ]);

        return $this->redirectBackSuccessMessage('Registro actualizado correctamente');
    }

    public function destroy(StorageIncidenceSafety $storage_incidence_safety) {}
}
