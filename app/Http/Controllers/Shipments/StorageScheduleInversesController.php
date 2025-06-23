<?php

namespace App\Http\Controllers\Shipments;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Shipments\SchedulleInverse\CreateScheduleInverseRequest;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Models\Shipments\StorageIncidenceJourneyDetails;
use App\Models\Shipments\StorageScheduleInverse;
use App\Models\Shipments\StorageScheduleInverseInventory;
use App\Models\Shipments\StorageScheduleInverseOperation;
use App\Models\Shipments\StorageScheduleInverseTraffic;
use Auth;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

class StorageScheduleInversesController extends BaseController
{
    public function index()
    {
        $this->authorize('storagescheduleinverse.index');
        $status = SystemStatus::where('schemaid', 'LIV')->select('name', 'statusid')->get();
        return view('Shipments.StorageScheduleInverse.index', compact('status'));
    }

    public function getDataInverse(Request $request)
    {
        $data = StorageScheduleInverse::controlAccessUser()
            ->leftJoin('systemcenterdistributions', 'systemcenterdistributions.centerid', '=', 'storagescheduleinverse.centerid')
            ->leftJoin('systemwarehouses', 'systemwarehouses.warehouseid', '=', 'storagescheduleinverse.warehouseid')
            ->leftJoin('orderdeliveries', 'orderdeliveries.id', '=', 'storagescheduleinverse.orderid')
            ->leftJoin('ordercustomers', 'ordercustomers.id', '=', 'orderdeliveries.customerid')
            ->leftJoin('shipmentcarriers', 'shipmentcarriers.id', '=', 'orderdeliveries.carrierid')
            ->leftJoin('systemstatus', 'systemstatus.statusid', '=', 'storagescheduleinverse.statusid')
            ->leftJoin('receiptunits', 'receiptunits.id', '=', 'storagescheduleinverse.folionumber')
            ->ForStatus($request->get('statusid'))
            ->ForShipment($request->get('shipmentid'))
            ->select([
                'systemcenterdistributions.name as center',
                'systemwarehouses.name as warehouse',
                'orderdeliveries.shipmentid',
                'ordercustomers.name as customer',
                'shipmentcarriers.name as carrier',
                'storagescheduleinverse.id',
                'storagescheduleinverse.adddate',
                'storagescheduleinverse.appointment',
                'storagescheduleinverse.statusid',
                'systemstatus.name as status',
                'receiptunits.id as folio',
                'receiptunits.entrydate'
            ])
            ->orderBy('storagescheduleinverse.id', 'desc')
            ->limit(200)
            ->get();
        return $this->showDataToView($data, 'Shipments.StorageScheduleInverse.partials.buttons');
    }

    public function create()
    {
        $this->authorize('storagescheduleinverse.create');
        return view('Shipments.StorageScheduleInverse.create');
    }

    public function store(CreateScheduleInverseRequest $request)
    {
        $error = null;
        DB::beginTransaction();
        try {
            $order = $this->getOrderDeliverie($request['shipmentid']);
            if (empty($order)) {
                return $this->errorResponse('El número de embarque no  existe.');
            } else {
                $incidence = $this->getIncidenceJourney($order);
                $deliveries = $this->getIncidenceJourneyDetails($incidence);
                if (empty($incidence)) {
                    return $this->errorResponse('El número de embarque no cuenta con incidencia registrada, favor de validar.');
                } else {
                    $inverse = $this->createNewInverse($incidence);
                    if (!empty($deliveries)) {
                        foreach ($deliveries as $delivery) {
                            $this->createRegisterTraffic($incidence, $inverse, $delivery);
                            $this->createRegisterInventory($incidence, $inverse, $delivery);
                        }
                    }
                    $this->createRegisterOperation($incidence, $inverse);
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
            return $this->redirectBackSuccessMessage('Embarque agregado correctamente.');
        } else {
            return $this->errorResponse('No se pudieron guardar los datos,favor de validar.' . $error);
        }
    }


    public function closeprocessinverse(Request $request)
    {
        $inverse = StorageScheduleInverse::where('id', $request['id'])->first();
        $inverse->statusid = 'TD';
        $inverse->save();

        $inverseOperation = StorageScheduleInverseOperation::controlAccessUser()
            ->where('orderid', $inverse->orderid)->first();
        $inverseOperation->statusid = 'CI';
        $inverseOperation->save();

        $inverseInventory = StorageScheduleInverseInventory::where('orderid', $inverse->orderid)->first();
        $inverseInventory->statusid = 'TD';
        $inverseInventory->save();

        return $this->responseSuccessAjax('', Carbon::now());
    }


    /**
     * @param $incidence
     * @param $inverse
     * @return void
     */
    public function createRegisterOperation($incidence, $inverse): void
    {
        StorageScheduleInverseOperation::updateOrCreate(
            [
                'centerid' => $incidence->centerid,
                'warehouseid' => $incidence->warehouseid,
                'orderid' => $incidence->orderid,
                'inverseid' => $inverse->id,
            ]);
    }

    /**
     * @param $incidence
     * @param $inverse
     * @param $delivery
     * @return void
     */
    public function createRegisterInventory($incidence, $inverse, $delivery): void
    {
        StorageScheduleInverseInventory::updateOrCreate(
            [
                'centerid' => $incidence->centerid,
                'warehouseid' => $incidence->warehouseid,
                'orderid' => $incidence->orderid,
                'inverseid' => $inverse->id,
                'delivery' => $delivery->delivery,
            ]);
    }

    /**
     * @param $incidence
     * @param $inverse
     * @param $delivery
     * @return void
     */
    public function createRegisterTraffic($incidence, $inverse, $delivery): void
    {
        StorageScheduleInverseTraffic::updateOrCreate([
            'centerid' => $incidence->centerid,
            'warehouseid' => $incidence->warehouseid,
            'orderid' => $incidence->orderid,
            'inverseid' => $inverse->id,
            'delivery' => $delivery->delivery,
            'partnumberid' => $delivery->partnumberid,
        ], [
            'username' => Auth::user()->username
        ]);
    }


    public function getOrderDeliverie($shipmentid)
    {
        return OrderDeliverie::controlAccessUser()->where('shipmentid', $shipmentid)
            ->select(['id', 'shipmentid'])->first();
    }


    public function getIncidenceJourney($order)
    {
        return StorageIncidenceJourney::controlAccessUser()->where('orderid', $order->id)
            ->select(['centerid', 'warehouseid', 'orderid', 'folioid'])->first();
    }

    public function getIncidenceJourneyDetails($incidence)
    {
        return StorageIncidenceJourneyDetails::controlAccessUser()
            ->where('orderid', $incidence->orderid)->select('delivery', 'partnumberid')->get();
    }

    /**
     * @param $incidence
     * @return StorageScheduleInverse|Model
     */
    public function createNewInverse($incidence)
    {
        return StorageScheduleInverse::updateOrCreate([
            'centerid' => $incidence->centerid,
            'warehouseid' => $incidence->warehouseid,
            'orderid' => $incidence->orderid
        ]);
    }


}
