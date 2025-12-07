<?php

namespace App\Http\Controllers\Yard\Carriers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Carriers\ContactsCarriersRequest;
use App\Models\Yard\Carriers\ShipmentCarrier;
use App\Models\Yard\Carriers\Shipmentcarrierscontact;
use Illuminate\Http\Request;

class ContactsCarriersController extends BaseController
{
    public function index($id)
    {
        $model = ShipmentCarrier::controlAccessUser()->findOrFail($id);
        if (empty($model)) {
            return $this->errorResponse('No se encontro el registro solicitado.');
        }

        return view('Yards.ShipmentCarriers.Contacts.create', compact('model'));
    }

    public function update(ContactsCarriersRequest $request, $id)
    {
        Shipmentcarrierscontact::create($request->validated());

        // Si es petición AJAX, devolver JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registro agregado con éxito.',
            ]);
        }

        return $this->redirectBackSuccessMessage('Registro agregado con exito.');
    }

    public function destroy($id)
    {
        $model = Shipmentcarrierscontact::findOrFail($id);
        $model->delete();

        // Si es petición AJAX, devolver JSON
        if (request()->ajax() || request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Registro eliminado con éxito.',
            ]);
        }

        return $this->redirectBackSuccessMessage('Registro eliminado con exito.');
    }

    public function ContactsForCarrier(Request $request)
    {
        $carrierId = $request->carrierid;
        $data = Shipmentcarrierscontact::controlAccessUser()
            ->where('carrierid', $carrierId);

        return $this->showDataToView($data, 'Yards.ShipmentCarriers.Contacts.buttons');
    }
}
