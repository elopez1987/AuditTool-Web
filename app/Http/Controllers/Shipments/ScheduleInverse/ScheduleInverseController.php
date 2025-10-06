<?php

namespace App\Http\Controllers\Shipments\ScheduleInverse;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Shipments\SchedulleInverse\ScheduleInverseRequest;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Shipments\ScheduleInverse\ScheduleInverse;
use App\Models\Shipments\ScheduleInverse\ScheduleInverseDetail;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ScheduleInverseController extends BaseController
{
    public function index()
    {
        $this->authorize('scheduleinverse.index');

        return view('Shipments.ScheduleInverse.index');
    }

    public function getscheduleinverse()
    {
        $data = ScheduleInverse::controlAccessUser()
            ->leftJoin('systemcenterdistributions', 'systemcenterdistributions.centerid', '=', 'scheduleinverse.centerid')
            ->leftJoin('systemwarehouses', 'systemwarehouses.warehouseid', '=', 'scheduleinverse.warehouseid')
            ->leftJoin('users', 'users.id', '=', 'scheduleinverse.createuser')
            ->leftJoin('systemstatus', 'systemstatus.statusid', '=', 'scheduleinverse.statusid')
            ->select([
                'systemcenterdistributions.name as centername',
                'systemwarehouses.name as warehousename',
                'users.name as username',
                'systemstatus.name as statusname',
                'scheduleinverse.*',
            ])->get();

        return $this->showDataToView($data, 'Shipments.ScheduleInverse.partials.buttons');
    }

    public function create()
    {
        $this->authorize('scheduleinverse.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view('Shipments.ScheduleInverse.create', compact('centers', 'warehouses'));
    }

    public function store(ScheduleInverseRequest $request)
    {
        DB::beginTransaction();
        try {
            $center = $this->getCenterForUser();
            $this->authorize('scheduleinverse.create');
            $schedulle = ScheduleInverse::create($request->validated());
            $bloques = SystemStatus::where('schemaid', 'KLH')->where('auxiliary02', $center)
                ->orderBy('name')->get();
            $fechasDeLunesAViernes = $this->obtenerFechasDeLunesAViernes($request['startdate'], $request['finishdate']);
            foreach ($fechasDeLunesAViernes as $fecha) {
                foreach ($bloques as $bloque) {
                    ScheduleInverseDetail::create([
                        'centerid' => $schedulle->centerid,
                        'warehouseid' => $schedulle->warehouseid,
                        'schedulleid' => $schedulle->id,
                        'workday' => $fecha,
                        'secuenceid' => $bloque->statusid,
                    ]);
                }
            }
            DB::commit();

            return $this->redirectBackSuccessMessage('Horario creado con exito');
        } catch (Exception $e) {
            $error = $e->getMessage();
            Log::error($error);
            DB::rollback();

            return $this->errorResponse('Error al guardar los datos, intente nuevamente');
        }
    }

    public function activateschedulle(Request $request)
    {
        $this->authorize('scheduleinverse.activate');

        ScheduleInverse::controlAccessUser()->where('statusid', '=', 'ACT')
            ->update(['statusid' => 'INO']);

        $schedulle = ScheduleInverse::find($request['id']);
        $schedulle->statusid = 'ACT';
        $schedulle->save();

        return response()->json([
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '0',
            'Message' => 'Periodo activado con exito',
        ], '200');
    }

    public function obtenerFechasDeLunesAViernes($fechaInicio, $fechaFin)
    {
        $fechaInicio = new DateTime($fechaInicio);
        $fechaFin = new DateTime($fechaFin);
        $fechas = [];
        while ($fechaInicio <= $fechaFin) {
            if (in_array($fechaInicio->format('N'), [1, 2, 3, 4, 5])) { // De lunes a viernes
                $fechas[] = $fechaInicio->format('Y-m-d');
            }
            $fechaInicio->modify('+1 day');
        }

        return $fechas;
    }
}
