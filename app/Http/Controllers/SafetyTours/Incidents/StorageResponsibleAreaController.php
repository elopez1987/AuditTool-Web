<?php

namespace App\Http\Controllers\SafetyTours\Incidents;

use App\Http\Controllers\BaseController;
use App\Http\Requests\SafetyTours\StorageResponsibleAreaRequest;
use App\Models\SafetyTours\Incidents\StorageResponsibleArea;
use Illuminate\Http\Request;

class StorageResponsibleAreaController extends BaseController
{
    public function index()
    {
        return view('SafetyTours.StorageResponsibleArea.index');
    }

    public function getResponsibleArea(Request $request)
    {
        $data = StorageResponsibleArea::controlAccessUser()
            ->with(['status:statusid,name,auxiliary01',
                'center:centerid,description,name'])
            ->get();

        return $this->showDataToView($data, 'SafetyTours.StorageResponsibleArea.partials.buttons');
    }

    public function create()
    {
        $centers = $this->getCenterForSelect();

        return view('SafetyTours.StorageResponsibleArea.create', compact('centers'));
    }

    public function store(StorageResponsibleAreaRequest $request)
    {

        StorageResponsibleArea::create($request->validated());

        return $this->redirectBackSuccessMessage('Registro agregado con exito.');
    }

    public function show(StorageResponsibleArea $responsible_area_security)
    {
        return $responsible_area_security;
    }

    public function edit(StorageResponsibleArea $responsible_area_security)
    {
        $status = $this->getStatusSTS();

        return view(
            'SafetyTours.StorageResponsibleArea.edit',
            compact('responsible_area_security', 'status')
        );
    }

    public function update(Request $request, StorageResponsibleArea $responsible_area_security)
    {
        $responsible_area_security->update([
            'name' => $request->name,
            'auxiliary01' => $request->auxiliary01,
            'auxiliary02' => $request->auxiliary02,
            'auxiliary03' => $request->auxiliary03,
            'statusid' => $request->statusid,
        ]);

        return $this->redirectBackSuccessMessage('Registro actualizado con exito.');
    }

    public function destroy(StorageResponsibleArea $responsible_area_security)
    {
        StorageResponsibleArea::destroy($responsible_area_security->id);

        return $this->redirectBackSuccessMessage('Registro eliminado con exito.');
    }
}
