<?php

namespace App\Http\Controllers\SafetyTours\Incidents;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\SafetyTours\Incidents\SaveEmailResponsibleRequest;
use App\Models\SafetyTours\Incidents\StorageResponsibleArea;
use App\Models\SafetyTours\Incidents\StorageSafetyResponsibleEmail;
use Illuminate\Http\Request;

class StorageResponsibleEmailsController extends BaseController
{
    public function index($id)
    {
        $storageResponsibleArea = StorageResponsibleArea::find($id);
        $contacts = StorageSafetyResponsibleEmail::where('storage_safety_responsible_area_id', $id)->get();

        return view(
            'SafetyTours.StorageResponsibleArea.Emails.index',
            compact('storageResponsibleArea', 'contacts'),
        );
    }

    public function store(SaveEmailResponsibleRequest $request, $id)
    {
        StorageSafetyResponsibleEmail::create([
            'storage_safety_responsible_area_id' => $id,
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'level' => $request->level,
            'attempts' => $request->attempts,
        ]);

        return $this->redirectBackSuccessMessage('Contacto agregado correctamente');
    }

    public function update(Request $request, $id)
    {
        $contact = StorageSafetyResponsibleEmail::find($id);
        $contact->update([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'level' => $request->level,
            'attempts' => $request->attempts,
        ]);

        return $this->redirectBackSuccessMessage('Contacto actualizado correctamente');
    }

    public function destroy($id)
    {
        $storageResponsibleArea = StorageSafetyResponsibleEmail::find($id);
        $storageResponsibleArea->delete();

        return $this->redirectBackSuccessMessage('Contacto eliminado correctamente');
    }
}
