<?php

namespace App\Http\Controllers\Receipt\StorageReceiptPeriods;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Receipt\StorageReceiptPeriods\StorageReceiptPeriodRequest;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Receipt\StorageReceiptPeriods\StorageReceiptPeriod;
use App\Models\Receipt\StorageSchedulleReceipt;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Log;

class StorageReceiptPeriodsController extends BaseController
{
    public function index()
    {
        $this->authorize('storagereceiptperiods.index');
        $status = $this->getStatusForSchema('STS');

        return view('Receipt.StorageReceiptPeriod.index', compact('status'));
    }

    public function getDataStorageReceiptPeriod(Request $request)
    {
        $this->authorize('storagereceiptperiods.index');
        $data = StorageReceiptPeriod::controlAccessUser()->with(['center', 'warehouse', 'status'])
            ->Name($request->get('name'))->get();

        return $this->showDataToView($data, 'Receipt.StorageReceiptPeriod.partials.buttons');
    }

    public function create()
    {
        $this->authorize('storagereceiptperiods.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view('Receipt.StorageReceiptPeriod.create', compact('centers', 'warehouses'));
    }

    public function store(StorageReceiptPeriodRequest $request)
    {
        $this->authorize('storagereceiptperiods.create');
        $dates = $this->getDatesInRange($request->get('startdate'), $request->get('finishdate'));
        foreach ($dates as $date) {
            $validateData = StorageSchedulleReceipt::controlAccessUser()->where('workday', $date)->first();
            if (! empty($validateData)) {
                return $this->errorResponse('Ya existe un periodo activo para la fecha seleccionada');
            }
        }
        $bloks = SystemStatus::where('centerid', $request['centerid'])
            ->where('warehouseid', $request['warehouseid'])
            ->where('schemaid', 'BHR')->select('statusid', 'name')->get();
        if ($bloks->isEmpty()) {
            return $this->errorResponse('No existen bloques de horario para el almacen y centro seleccionado');
        }

        DB::beginTransaction();
        try {
            $storageReceiptPeriod = new StorageReceiptPeriod($request->validated());
            $storageReceiptPeriod->save();
            foreach ($dates as $date) {
                foreach ($bloks as $bloq) {
                    StorageSchedulleReceipt::create(
                        [
                            'centerid' => $storageReceiptPeriod->centerid,
                            'warehouseid' => $storageReceiptPeriod->warehouseid,
                            'periodid' => $storageReceiptPeriod->id,
                            'workday' => $date,
                            'secuence' => $bloq->statusid,
                        ],
                    );
                }
            }
            DB::commit();

            return $this->successResponse('storagereceiptperiods.index');
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();

            return $this->errorResponse('No se pudo crear el periodo de recibo, favor de intentar nuevamente');
        }
    }

    public function show($id)
    {
        $this->authorize('storagereceiptperiods.show');
        $status = $this->getStatusForSchema('EPR');
        $periods = StorageReceiptPeriod::controlAccessUser()->where('id', $id)->first();
        $detalle = StorageSchedulleReceipt::controlAccessUser()
            ->with(['center', 'warehouse', 'status', 'statussecuence', 'receipt', 'period'])
            ->where('periodid', $id)->get();

        return view('Receipt.StorageReceiptPeriod.show', compact('periods', 'status', 'detalle'));
    }

    public function activeperiod($id)
    {
        $this->authorize('storagereceiptperiods.activate');
        $period = StorageReceiptPeriod::controlAccessUser()->findOrFail($id);
        if (! empty($period)) {
            if ($period->statusid === 'EPR-01') {
                $periodActive = StorageReceiptPeriod::controlAccessUser()->where('statusid', 'EPR-02')->first();
                if (! empty($periodActive)) {
                    $periodActive->statusid = 'EPR-01';
                    $periodActive->save();
                }
                $period->statusid = 'EPR-02';
            } else {
                $period->statusid = 'EPR-01';
            }
            $period->save();
        }

        return $this->redirectBackSuccessMessage('Periodo actualizado con exito');
    }

    public function destroy(StorageReceiptPeriod $storagereceiptperiod)
    {
        $this->authorize('storagereceiptperiods.destroy');
        $storagereceiptperiod->delete();

        return $this->redirectBackSuccessMessage('Registro eliminado con exito!!');
    }
}
