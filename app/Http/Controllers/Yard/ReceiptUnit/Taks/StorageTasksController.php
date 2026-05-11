<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Taks;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Task\CreateTaksOperator;
use App\Jobs\Yard\SendMessageNewTaskJob;
use App\Models\Yard\ReceiptUnit;
use App\Models\Yard\StorageTask;
use App\Models\Yard\StorageUnitTrace;
use App\Models\Yard\StorageZone\StorageZone;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class StorageTasksController extends BaseController
{
    public function index()
    {
        $this->authorize('storagetask.index');
        $status = $this->getStatusForSchema('SKS');

        return view('Yards.StorageTask.index', compact('status'));
    }

    public function getDataTask(Request $request)
    {
        $data = StorageTask::controlAccessUser()
            ->leftJoin('systemcenterdistributions', 'storagetasks.centerid', 'systemcenterdistributions.centerid')
            ->leftJoin('systemwarehouses', 'storagetasks.warehouseid', 'systemwarehouses.warehouseid')
            ->leftJoin('systemstatus', 'storagetasks.statusid', 'systemstatus.statusid')
            ->leftJoin('storagezones', 'storagetasks.zoneid', 'storagezones.id')
            ->leftJoin('storagelocations', 'storagetasks.locationid', 'storagelocations.id')
            ->leftJoin('storagereasons', 'storagetasks.razonstatusid', 'storagereasons.id')
            ->leftJoin('users', 'storagetasks.operatoryard', 'users.id')
            ->leftJoin('receiptunits', 'storagetasks.receiptunitid', 'receiptunits.id')
            ->leftJoin('receipttrucks', 'receiptunits.foliotruck', 'receipttrucks.id')
            ->leftJoin('storagelocations as l2', 'receiptunits.locationid', 'l2.id')
            ->select([
                'systemcenterdistributions.description as centername',
                'systemwarehouses.name as warehouse',
                'users.name as asignado',
                'receiptunits.id as folionumber',
                'receiptunits.barcode',
                'receiptunits.boxnumber',
                'receiptunits.econumber',
                'receipttrucks.operatorname',
                'systemstatus.name as status',
                'storagezones.name as zone',
                'storagelocations.name as location',
                'storagereasons.name as rason',
                'storagetasks.id',
                'storagetasks.created_at',
                'storagetasks.statusid',
                'l2.name as locationactual',
            ])
            ->whereHas('receiptunit', function ($q) use ($request) {
                $q->Folio($request->get('folionumber'));
                $q->Barcode($request->get('barcode'));
                $q->BoxNumber($request->get('boxnumber'));
            })
            ->ForStatusTask($request->get('statusid'))
            ->whereNotIn('storagetasks.statusid', ['CO'])
            ->orderBy('storagetasks.id', 'desc')->latest()->take(100)->get();

        return DataTables::of($data)
            ->addColumn('cancelledbtn', 'Yards.StorageTask.partials.cancelled')
            ->addColumn('creation', function ($data) {
                return Carbon::parse($data->created_at)->toDateTimeString();
            })->rawColumns(['cancelledbtn'])->toJson();
    }

    public function store(CreateTaksOperator $request)
    {
        try {
            $receiptunit = ReceiptUnit::searchForId($request['folionumber']);

            if (empty($receiptunit)) {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El número de folio no existe!!',
                    ], 404);
                }
            }

            $zone = StorageZone::whereId($request['zoneid'])->select('zoneid')->first();
            $razonstatusid = $this->getRazonStatus($zone);
            $task = $this->createNewTask($receiptunit, $razonstatusid, $request);
            StorageUnitTrace::createTraceUnit($receiptunit, '4');
            $parameterNotificacionWarehouse = $this->getParameterNotificacionWarehouse($receiptunit->warehouseid);

            if (! empty($parameterNotificacionWarehouse) && in_array('MWH-01', (array) $parameterNotificacionWarehouse)) {
                SendMessageNewTaskJob::dispatch($task->id)->onQueue('yms-web');
            }

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Tarea creada exitosamente.',
                    'task' => $task,
                ]);
            }

            return $this->successResponse('storagetask.index');
        } catch (\Exception $e) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear la tarea: ' . $e->getMessage(),
                ], 500);
            }
        }
    }

    public function destroy(Request $request, StorageTask $storagetask)
    {
        $storagetask->statusid = 'CO';
        $storagetask->save();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Tarea cancelada exitosamente.',
            ]);
        }

    }

    public function createNewTask(ReceiptUnit $receiptunit, string $razonstatusid, CreateTaksOperator $request): StorageTask
    {
        return StorageTask::create([
            'centerid' => $receiptunit->centerid,
            'warehouseid' => $receiptunit->warehouseid,
            'receiptunitid' => $receiptunit->id,
            'razonstatusid' => $razonstatusid,
            'assignedto' => $request['typeid'],
            'operatoryard' => $request['patieroid'],
            'zoneid' => $request['zoneid'],
            'locationid' => $request['locationid'],
        ]);
    }

    public function getRazonStatus(StorageZone $zone): string
    {
        if ($zone->zoneid === 'PA') {
            $razonstatusid = '5';
        } elseif ($zone->zoneid === 'CO') {
            $razonstatusid = '4';
        }

        return $razonstatusid;
    }

    public function getZonesForCreateTask()
    {
        $zones = StorageZone::controlAccessUser()
            ->whereRaw("charindex('ZO-03', configurationflag) > 0")
            ->select(['name', 'id'])->get();

        return response()->json($zones);
    }

    public function getPatierosForCreateTask()
    {
        $center = $this->getCenterForUser();
        $patieros = User::leftJoin('securityaccesswarehouses', 'users.username', '=', 'securityaccesswarehouses.username')
            ->select(['users.name', 'users.id'])
            ->where('securityaccesswarehouses.centerid', '=', $center)
            ->where('users.statusid', '=', 'E')
            ->whereNotNull('configurationflag')
            ->whereRaw("charindex('USR-01', configurationflag) > 0")
            ->get();

        return response()->json($patieros);
    }
}
