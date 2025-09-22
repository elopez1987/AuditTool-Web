<?php

namespace App\Http\Controllers\Shipments;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\General\EvidencePhoto;
use App\Models\Shipments\StorageIncidenceJourneyDetails;
use App\Models\Shipments\StorageScheduleInverse;
use App\Models\Shipments\StorageScheduleInverseOperation;
use App\Models\Shipments\StorageScheduleInverseTraffic;
use App\Models\Yard\ReceiptUnit;
use App\Traits\ResponseApi;
use App\Traits\Web\Shipments\QueryInverseOperation;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;

class StorageScheduleInverseOperationsController extends BaseController
{
    use ResponseApi;
    use QueryInverseOperation;

    public function updateDataOperation(StorageScheduleInverse $storagescheduleinverse)
    {
        $details = $this->getDataOperation($storagescheduleinverse);
        $operation = StorageScheduleInverseOperation::where('orderid', $storagescheduleinverse->orderid)
            ->first();
        $skusVerificados = $this->getVerificados($storagescheduleinverse->orderid);
        $trafficDetails = StorageScheduleInverseTraffic::where('orderid', $storagescheduleinverse->orderid)->get();
        $status = SystemStatus::whereSchemaid('LOV')->select(['name', 'statusid'])->get();
        $evidences = EvidencePhoto::where('model_type', '=', 'StorageScheduleInverse')
            ->where('model_id', $storagescheduleinverse->orderid)->get();
        return view(
            'Shipments.StorageScheduleInverse.operation.edit',
            compact(
                'storagescheduleinverse',
                'details',
                'operation',
                'status',
                'trafficDetails',
                'skusVerificados',
                'evidences'
            )
        );
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $inverse = StorageScheduleInverse::findOrFail($id);
            if (! empty($request['folionumber'])) {
                $receiptunit = ReceiptUnit::controlAccessUser()->where('id', $request['folionumber'])->first();
                if (! empty($receiptunit)) {
                    $inverse->folionumber = $receiptunit->id;
                    $inverse->save();
                } else {
                    return $this->errorResponse('El número de folio ingresado no existe, favor de validar.');
                }
            }
            $this->createNewOperation($inverse, $request);
            $this->updateStatusInverseToFinish($request['warehousedate'], $inverse);
            DB::commit();
            return $this->redirectBackSuccessMessage('Registro agregado con exito');
        } catch (Exception $e) {
            $error = $e->getMessage();
            DB::rollback();
            return $this->errorResponse('No se pudieron guardar los datos, favor de validar ' . $error);
        }
    }

    public function openCountToReceiptInverse(Request $request)
    {
        $item = StorageIncidenceJourneyDetails::where('folioid', $request['id'])->first();
        DB::delete("
                delete from storagescheduleinverseoperationdetails
                where partnumberid={$item->partnumberid}
                and orderid = {$item->orderid}
                and delivery = {$item->delivery} ");
        return $this->responseSuccessAjax('', Carbon::now());
    }

    public function openCountCheck(Request $request)
    {
        $shipment = StorageScheduleInverseOperation::where('orderid', $request['orderid'])->first();
        $shipment->finishdate = null;
        $shipment->save();
        return $this->responseSuccessAjax('', Carbon::now());
    }

    /**
     * @param $inverse
     * @param Request $request
     *
     * @return void
     */
    public function createNewOperation($inverse, Request $request): void
    {
        StorageScheduleInverseOperation::updateOrCreate(
            [
                'centerid' => $inverse->centerid,
                'warehouseid' => $inverse->warehouseid,
                'orderid' => $inverse->orderid,
                'inverseid' => $inverse->id,
            ],
            [
                'wmsdate' => $request['wmsdate'],
                'warehousedate' => $request['warehousedate'],
                'statusid' => $request['statusid'],
            ]
        );
    }

    /**
     * @param $warehousedate
     * @param $inverse
     *
     * @return void
     */
    public function updateStatusInverseToFinish($warehousedate, $inverse): void
    {
        if ($warehousedate !== '') {
            $inverse->statusid = 'TD';
            $inverse->save();
        }
    }
}
