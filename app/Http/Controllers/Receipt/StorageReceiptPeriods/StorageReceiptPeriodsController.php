<?php

namespace App\Http\Controllers\Receipt\StorageReceiptPeriods;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Receipt\StorageReceiptPeriods\StorageReceiptPeriodRequest;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Receipt\StorageReceiptPeriods\StorageReceiptPeriod;
use App\Models\Receipt\StorageSchedulleReceipt;
use Illuminate\Http\Request;


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
            if (!empty($validateData)) {
                return $this->errorResponse('Ya existe un periodo activo para la fecha seleccionada');
            }
        }
        $storageReceiptPeriod = new StorageReceiptPeriod($request->validated());
        $storageReceiptPeriod->save();
        $bloks = SystemStatus::where('auxiliary02', $storageReceiptPeriod->centerid)
            ->where('auxiliary03', $storageReceiptPeriod->warehouseid)
            ->where('schemaid', 'BHR')->select('statusid', 'name')->get();
        foreach ($dates as $date) {
            foreach ($bloks as $bloq) {
                StorageSchedulleReceipt::create(
                    [
                        'centerid' => $storageReceiptPeriod->centerid,
                        'warehouseid' => $storageReceiptPeriod->warehouseid,
                        'periodid' => $storageReceiptPeriod->id,
                        'workday' => $date,
                        'secuence' => $bloq->statusid,
                    ]
                );
            }
        }
        return $this->successResponse('storagereceiptperiods.index');
    }

    public function show(StorageReceiptPeriod $storageReceiptPeriod)
    {
        $this->authorize('storagereceiptperiods.show');

    }


    public function destroy(StorageReceiptPeriod $storageReceiptPeriod)
    {
        $this->authorize('storagereceiptperiods.destroy');


    }

    public function activeperiod($id)
    {
        $this->authorize('storagereceiptperiods.activate');
        $period = StorageReceiptPeriod::controlAccessUser()->findOrFail($id);
        if (!empty($period)) {
            if ($period->statusid = 'EPR-01') {
                $periodActive = StorageReceiptPeriod::controlAccessUser()->where('statusid', 'EPR-02')->first();
                if (!empty($periodActive)) {
                    $periodActive->statusid = 'EPR-01';
                    $periodActive->save();
                }
                $period->statusid = 'EPR-02';
                $period->save();
            } else {
                $period->statusid = 'EPR-01';
                $period->save();
            };
        }
        return $this->redirectBackSuccessMessage('Periodo actualizado con exito');
    }


}
