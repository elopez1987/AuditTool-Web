<?php

namespace App\Http\Controllers\Shipments\ScheduleInverse;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Shipments\ScheduleInverse\ScheduleInverseDetail;
use App\Models\Shipments\ScheduleInverse\ShipmentForSchedulle;
use App\Models\Shipments\ScheduleInverse\ShipmentSchedulleDetail;
use App\Models\Shipments\ScheduleInverse\StorageShipmentSchedule;
use App\Models\Shipments\StorageIncidenceJourney;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Log;
use Ramsey\Uuid\Uuid;

class StorageShipmentScheduleController extends BaseController
{
    public function index()
    {
        $this->authorize('reserveschedulleinverse.index');

        return view('Shipments.ReserveSchedulleInverse.index');
    }

    public function getreserveschedulleinverse()
    {
        $carriers = $this->getCarrierForUser();
        $data = StorageShipmentSchedule::controlAccessUser()
            ->CarrierIn($carriers)
            ->leftJoin('systemcenterdistributions', 'systemcenterdistributions.centerid', '=', 'storageshipmentsschedule.centerid')
            ->leftJoin('systemwarehouses', 'systemwarehouses.warehouseid', '=', 'storageshipmentsschedule.warehouseid')
            ->leftJoin('systemstatus', 'systemstatus.statusid', '=', 'storageshipmentsschedule.statusid')
            ->leftJoin('shipmentcarriers', 'shipmentcarriers.id', '=', 'storageshipmentsschedule.carrierid')
            ->leftJoin('users', 'users.id', '=', 'storageshipmentsschedule.createuser')
            ->select([
                'storageshipmentsschedule.*',
                'systemcenterdistributions.name as centername',
                'systemwarehouses.name as warehousename',
                'systemstatus.name as statusname',
                'shipmentcarriers.name as carriername',
                'users.name as createuser',
            ])->get();

        return $this->showDataToView($data, 'Shipments.ReserveSchedulleInverse.partials.buttons');
    }

    public function create()
    {
        $this->authorize('reserveschedulleinverse.create');

        return view('Shipments.ReserveSchedulleInverse.create');
    }

    public function getQuantityMax()
    {
        $center = $this->getCenterForUser();
        $quantitymax = SystemDetailParameter::where('parameterid', 'QUANTITY-DEV')
            ->where('value01', $center)->first();

        return response()->json($quantitymax);
    }

    public function getWorkdays()
    {
        $workdays = ScheduleInverseDetail::controlAccessUser()
            ->select(['scheduleinversedetail.workday'])
            ->leftJoin('scheduleinverse', function ($join) {
                $join->on('scheduleinversedetail.schedulleid', '=', 'scheduleinverse.id')
                    ->on('scheduleinversedetail.centerid', '=', 'scheduleinverse.centerid');
            })
            ->where('scheduleinverse.statusid', 'ACT')
            ->distinct()
            ->get();

        return response()->json($workdays);
    }

    public function getQuantityForDays()
    {
        $quantityForDays = ScheduleInverseDetail::controlAccessUser()->select([
            'scheduleinversedetail.workday',
            DB::raw('SUM(scheduleinversedetail.quantity) as total'),
        ])
            ->leftJoin('scheduleinverse', function ($join) {
                $join->on('scheduleinversedetail.schedulleid', '=', 'scheduleinverse.id')
                    ->on('scheduleinversedetail.centerid', '=', 'scheduleinverse.centerid');
            })
            ->distinct()
            ->groupBy('scheduleinversedetail.workday')
            ->get();

        return response()->json($quantityForDays);
    }

    public function getDetailIncidenteJourney()
    {
        $carriers = $this->getCarrierForUser();
        $data = ShipmentForSchedulle::controlAccessUser()
            ->CarrierIn($carriers)
            ->whereNotIn('statusdevid', ['AGD', 'ENT'])->get();

        return $this->showDataToView($data, '');
    }

    public function saveSchedulleInverse(Request $request)
    {
        DB::beginTransaction();
        try {
            $this->authorize('reserveschedulleinverse.store');
            $folioids = $request->input('folioids');
            $fechawork = $request->input('fechawork');
            $totalQuantity = $request->input('totalQuantity');
            $carrier = $this->getCarrierForUserFirst();
            $center = $this->getCenterForUser();

            $totalReservado = ScheduleInverseDetail::controlAccessUser()
                ->leftJoin('scheduleinverse', function ($join) {
                    $join->on('scheduleinversedetail.schedulleid', '=', 'scheduleinverse.id')
                        ->on('scheduleinversedetail.centerid', '=', 'scheduleinverse.centerid');
                })
                ->where('scheduleinversedetail.workday', $fechawork)
                ->where('scheduleinverse.statusid', 'ACT')
                ->sum('scheduleinversedetail.quantity');

            if ($totalReservado > $totalQuantity) {
                return response()->json(['message' => 'La cantidad de reservada supera la cantidad de pedidos.']);
            }

            $shipmentSchedulle = StorageShipmentSchedule::create([
                'centerid' => $this->getCenterForUser(),
                'warehouseid' => $this->getWarehouseForUser(),
                'carrierid' => $carrier->carrierid,
                'appointment' => Carbon::now(),
                'value01' => Uuid::uuid4(),
            ]);

            foreach ($folioids as $folio) {
                $storageIncede = StorageIncidenceJourney::controlAccessUser()->where('folioid', $folio)->first();
                $storageIncede->statusdevid = 'AGD';
                $storageIncede->save();
                ShipmentSchedulleDetail::create([
                    'centerid' => $shipmentSchedulle->centerid,
                    'warehouseid' => $shipmentSchedulle->warehouseid,
                    'shipmentschedulleid' => $shipmentSchedulle->id,
                    'incidencejourneyid' => $storageIncede->folioid,
                ]);
            }

            $detailbloques = ScheduleInverseDetail::controlAccessUser()->where('workday', $fechawork)
                ->whereNull('folioid')
                ->get();
            foreach ($detailbloques as $bloque) {
                if ($totalQuantity > 0) {
                    $quantityBlock = SystemStatus::where('schemaid', 'KLH')
                        ->where('auxiliary02', $center)
                        ->where('statusid', $bloque->secuenceid)->first();

                    if ($totalQuantity > $quantityBlock->auxiliary01) {
                        $quantity = $quantityBlock->auxiliary01;
                    } else {
                        $quantity = $totalQuantity;
                    }
                    $bloque->folioid = $shipmentSchedulle->id;
                    $bloque->quantity = $quantity;
                    $bloque->statusid = 'RES';
                    $bloque->assigneduser = Auth::id();
                    $bloque->save();

                    $totalQuantity -= $quantityBlock->auxiliary01;
                }
            }
            DB::commit();

            return response()->json(['message' => 'Horario generado exitosamente']);
        } catch (Exception $e) {
            Log::error($e->getMessage());
            DB::rollback();

            return response()->json(['message' => 'Error al generar el horario.']);
        }
    }

    public function show($id)
    {
        $this->authorize('reserveschedulleinverse.show');
        $reserveschedule = StorageShipmentSchedule::controlAccessUser()->findOrFail($id);
        $details = ScheduleInverseDetail::controlAccessUser()->where('folioid', $id)
            ->leftJoin('systemstatus', 'systemstatus.statusid', '=', 'scheduleinversedetail.secuenceid')
            ->select(['scheduleinversedetail.folioid',
                'scheduleinversedetail.workday',
                'scheduleinversedetail.quantity',
                'systemstatus.name as bloque',
            ])->get();

        $shipments = StorageShipmentSchedule::controlAccessUser()->select([
            'vw_shipmentforschedulleinverse.shipmentid',
            'vw_shipmentforschedulleinverse.carriername',
            'vw_shipmentforschedulleinverse.customername',
            'vw_shipmentforschedulleinverse.quantity',
        ])
            ->leftJoin('storageshipmentsscheduledetail', function ($join) {
                $join->on('storageshipmentsschedule.id', '=', 'storageshipmentsscheduledetail.shipmentschedulleid')
                    ->on('storageshipmentsschedule.centerid', '=', 'storageshipmentsscheduledetail.centerid');
            })
            ->leftJoin('vw_shipmentforschedulleinverse', function ($join) {
                $join->on('storageshipmentsscheduledetail.incidencejourneyid', '=', 'vw_shipmentforschedulleinverse.folioid')
                    ->on('storageshipmentsscheduledetail.centerid', '=', 'vw_shipmentforschedulleinverse.centerid');
            })
            ->where('storageshipmentsschedule.id', $id)
            ->get();

        return view('Shipments.ReserveSchedulleInverse.show', compact('reserveschedule', 'details', 'shipments'));
    }
}
