<?php

namespace App\Http\ControllersApp\Yard\CheckIn;

use App\Http\Controllers\Controller;
use App\Models\Yard\ReceiptUnitArrive;
use App\Traits\ResponseApi;
use Carbon\Carbon;
use Illuminate\Http\Request;

class StorageUnitArriveController extends Controller
{
    use ResponseApi;

//    Registra la llegada de la unidad a caseta de vigilancia
    public function index(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'token' => 'required|min:50|max:70',
        ]);
        if ($validator->fails()) {
            return $this->getResponseWithErrors('saveArriveUnitToCenter', 'El token es requerido con un largo mayor a 20 y menor a 31 caracteres');
        }
        $unitRegister = ReceiptUnitArrive::where('token', $request->token)->whereNull('closedate')->first();
        if (!empty($unitRegister)) {
            return $this->getResponseWithErrors('saveArriveUnitToCenter', 'El token se encuentra registrado.');
        }
        ReceiptUnitArrive::create([
            'token' => $request['token'],
            'startdate' => Carbon::now(),
        ]);
        return $this->getResponseWithSuccess('saveArriveUnitToCenter', 'El registro se guardo exitosamente');
    }
}
