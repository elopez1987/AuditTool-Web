<?php

namespace App\Http\ControllersApp\Inventory\Dron\CycleCountingDron;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\FiscalInventory\Dron\StorageCountDronDetail;
use App\Models\Inventory\FiscalInventory\Dron\StorageCountDronHead;
use App\Traits\ResponseApi;
use DB;
use Illuminate\Http\Request;

class CycleCountController extends BaseController
{

    use ResponseApi;
    public function saveCountDron(Request $request)
    {
        DB::beginTransaction();
        try {
            $centerid = $this->getCenterForUser();
            $inventoryHead = StorageCountDronHead::updateOrCreate(
                [
                    'centerid' => $centerid,
                    'countid' => $request['id']
                ],
                [
                    'warehouse' => $request['alm'],
                    'countdate' => $request['fecha'],
                    'countrack' => $request['rack'],
                    'countflyer' => $request['piloto']
                ]);
            foreach ($request['ubi'] as $detail) {
                StorageCountDronDetail::updateOrCreate(
                    [
                        'centerid' => $centerid,
                        'inventoryid' => $inventoryHead->id,
                        'countid' => $request['id'],
                        'ubicacion' => $detail['ubicacion'],
                    ],
                    [
                        'wd' => $detail['#_wd'],
                        'lpn' => $detail['lpn'],
                        'ultimo_movimiento' => $detail['ultimo_movimiento'],
                        'idh' => $detail['idh'],
                        'cantidad_pz' => $detail['cantidad_pz'],
                        'uom' => $detail['uom_'],
                        'cajas' => $detail['cajas'],
                        'footprint' => $detail['footprint'],
                        'estatus' => $detail['estatus'],
                        'lote' => $detail['lote'],
                        'fecha_manufactura' => $detail['fecha_manufactura'],
                        'fecha_expiracion' => $detail['fecha_expiracion'],
                        'fecha_de_alta' => $detail['fecha_de_alta'],
                        'ultimo_usuario' => $detail['ultimo_usuario'],
                        'familia' => $detail['familia'],
                        'descripcion' => $detail['descripcion'],
                        'area' => $detail['area'],
                        'match_wd' => $detail['Match_wd'],
                        'real' => $detail['Real'],
                        'incidencia' => $detail['Incidencia'],
                        'nota' => $detail['Nota'],
                        'comentario' => $detail['Comentario'],
                        'auxiliary01' => $detail['Timestamp']
                    ]
                );
            }
            DB::commit();
            $success = true;
        } catch (Exception) {
            $success = false;
            DB::rollback();
        }
        if ($success) {
            return $this->getResponseWithSuccess('saveCountDron', 'Datos procesados con exito.');
        } else {
            return $this->getResponseWithErrors('saveCountDron', 'Error al procesar la solicitud, favor de intentar mas tarde.');
        }
    }
}
