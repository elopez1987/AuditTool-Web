<?php

namespace App\Http\Controllers\Shipments\MonitorOperation\VerificationProcess;

use App\Http\Controllers\Controller;
use App\Models\Shipments\ShipmentContainer;
use App\Models\Shipments\ShipmentControl;
use App\Models\Shipments\ShipmentStock;
use App\Traits\ResponseApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;


class DeleteLpnFromShipmentController extends Controller
{
    use ResponseApi;

    public function index(Request $request)
    {
        DB::beginTransaction();
        try {
            $lpn = ShipmentContainer::where('id', '=', $request['id'])->first();
            $quantiyStock = ShipmentStock::where('lpnid', $lpn->lpnid)->get();

            foreach ($quantiyStock as $stock) {
                $shipmentControl = ShipmentControl::controlAccessUser()
                    ->where('orderid', $lpn->orderid)
                    ->where('partnumberid', $stock->partnumberid)->first();
                $shipmentControl->quantitytrue = $shipmentControl->quantitytrue - $stock->quantity;
                $shipmentControl->quantityfail = $shipmentControl->quantityfail + $stock->quantity;
                $shipmentControl->save();
            }

            $lpn->delete();
            ShipmentStock::where('lpnid', '=', $lpn->lpnid)->delete();
            DB::commit();
            return $this->responseSuccessAjax('', 'Proceso ejecuta con exito.');
        } catch (Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            return $this->responseSuccessAjax('', 'Error al procesar la solicitud, favor de intentarlo de nuevo.');
        }
    }

}
