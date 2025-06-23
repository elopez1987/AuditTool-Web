<?php

namespace App\Http\Controllers\Shipments\OrderDelivery\Events;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Shipments\Exception;
use App\Traits\Web\Shipments\OrderDelivery\CloseProcessOrderDelivery;
use Illuminate\Support\Facades\DB;

class DebugShipmentController extends BaseController
{
    use CloseProcessOrderDelivery;

    public function debugshipment($id)
    {
        DB::beginTransaction();
        try {
            $this->closeOrderDelivery($id);
            DB::commit();
            return $this->redirectBackSuccessMessage('Embarque cerrado correctamente!!');
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return $this->errorResponse('Error al procesar la solicitud!!' . $error);
        }
    }


}
