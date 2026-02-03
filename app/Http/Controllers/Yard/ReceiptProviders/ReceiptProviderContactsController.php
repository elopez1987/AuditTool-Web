<?php

namespace App\Http\Controllers\Yard\ReceiptProviders;

use App\Http\Controllers\BaseController;
use App\Models\Receipt\ReceiptProvider;
use App\Models\Yard\Providers\ReceiptProviderContacts;
use Illuminate\Http\Request;

class ReceiptProviderContactsController extends BaseController
{
    public function create($id)
    {
        $provider = ReceiptProvider::findOrFail($id);

        return view('Yards.ReceiptProviders.Contacts.create', compact('provider'));
    }

    public function contactsForProviders(Request $request)
    {
        $providerId = $request->providerid;
        $data = ReceiptProviderContacts::whereProviderid($providerId)->get();

        return $this->showDataToView($data, 'Yards.ReceiptProviders.Contacts.buttons');
    }

    public function update(Request $request, $id)
    {
        $provider = ReceiptProvider::findOrFail($id);
        ReceiptProviderContacts::create(
            [
                'centerid' => $provider->centerid,
                'warehouseid' => $provider->warehouseid,
                'providerid' => $provider->id,
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'value01' => $request->value01,
                'value02' => $request->value02,
                'value03' => $request->value03,
            ],
        );

        return $this->redirectBackSuccessMessage('Registro agregado correctamente');
    }

    public function destroy($id)
    {
        $contact = ReceiptProviderContacts::findOrFail($id);
        $contact->delete();

        // Si es petición AJAX, devolver JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registro eliminado con éxito.',
            ]);
        }

        return $this->redirectBackSuccessMessage('Registro eliminado con exito.');
    }
}
