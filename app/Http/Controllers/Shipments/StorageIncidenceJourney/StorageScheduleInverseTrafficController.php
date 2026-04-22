<?php

namespace App\Http\Controllers\Shipments\StorageIncidenceJourney;

use App\Http\Controllers\BaseController;
use App\Http\Controllers\Shipments\Exception;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageScheduleInverse;
use App\Models\Shipments\StorageScheduleInverseTraffic;
use App\Models\Yard\ReceiptUnit;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class StorageScheduleInverseTrafficController extends BaseController
{
    public function index(StorageScheduleInverse $storagescheduleinverse)
    {
        $this->authorize('storagescheduleinverse.edit');
        $details = StorageScheduleInverse::getDataInverse($storagescheduleinverse);

        return view(
            'Shipments.StorageScheduleInverse.edit',
            compact('storagescheduleinverse', 'details')
        );
    }

    public function update(Request $request, StorageScheduleInverse $storagescheduleinverse)
    {
        if ($request['folionumber'] !== '') {
            $folio = ReceiptUnit::controlAccessUser()->where('id', $request['folionumber'])->first();
            if (empty($folio)) {
                return $this->errorResponse('No existe número de folio en sistema, favor de validar');
            }
        }
        $storagescheduleinverse->folionumber = $request['folionumber'];
        $storagescheduleinverse->appointment = $request['appointment'];
        $storagescheduleinverse->save();

        return $this->redirectBackSuccessMessage('Registro actualizado con exito');
    }

    public function updateDataTraffic(Request $request, StorageScheduleInverse $storagescheduleinverse)
    {
        $error = null;
        DB::beginTransaction();
        try {
            $order = $this->getDeliverie($storagescheduleinverse);
            if ($request['delivery'] !== '') {
                $total = count($request['delivery']) - 1;
                for ($i = 0; $i <= $total; $i++) {
                    $this->updateTraffic($order, $storagescheduleinverse, $request, $i);
                }
            }
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $error = $e->getMessage();
            DB::rollback();
        }
        if ($success) {
            return $this->redirectBackSuccessMessage('Datos agregados correctamente');
        }

        return $this->errorResponse('No se pudieron guardar los datos' . $error);
    }

    public function updateTraffic($order, StorageScheduleInverse $storagescheduleinverse, Request $request, int $i): void
    {
        StorageScheduleInverseTraffic::updateOrCreate(
            [
                'centerid' => $order->centerid,
                'warehouseid' => $order->warehouseid,
                'orderid' => $order->id,
                'inverseid' => $storagescheduleinverse->id,
                'delivery' => $request['delivery'][$i],
            ],
            [
                'typereport' => $request['type'][$i],

                'requestzare' => $request['requestzare'][$i],
                'responsezare' => $request['responsezare'][$i],
                'zare' => $request['zare'][$i],

                'requestne' => $request['requestne'][$i],
                'responsene' => $request['responsene'][$i],
                'ne' => $request['ne'][$i],

                'requestnc' => $request['requestnc'][$i],
                'responsenc' => $request['responsenc'][$i],
                'nc' => $request['nc'][$i],

                'adjust' => $request['adjust'][$i],
                'username' => Auth::user()->username,
            ]
        );
    }

    /**
     * @return OrderDeliverie|Builder|Model|object|null
     */
    public function getDeliverie(StorageScheduleInverse $storagescheduleinverse)
    {
        return OrderDeliverie::controlAccessUser()
            ->where('id', $storagescheduleinverse->orderid)->first();
    }
}
