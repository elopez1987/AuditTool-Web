<?php

namespace App\Http\Controllers\Shipments\ScheduleInverse;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\ScheduleInverse\ScheduleInverse;
use App\Models\Shipments\ScheduleInverse\ScheduleInverseDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ScheduleInverseDetailController extends BaseController
{
    public function index($id)
    {
        $this->authorize('scheduleinverse.show');
        $schedulle = ScheduleInverse::find($id);

        return view('Shipments.ScheduleInverse.show', compact('schedulle'));
    }

    public function getSchedulleInverseDetail(Request $request)
    {
        $data = ScheduleInverseDetail::controlAccessUser()
            ->select([
                'scheduleinversedetail.id',
                'scheduleinverse.id as schedulleid',
                'scheduleinversedetail.workday',
                'status_blocks.name as bloque',
                'scheduleinversedetail.folioid',
                'scheduleinversedetail.quantity',
                'scheduleinversedetail.statusid',
                'users.name as username',
                'status_names.name as statusname',
            ])
            ->leftJoin('scheduleinverse', 'scheduleinversedetail.schedulleid', '=', 'scheduleinverse.id')
            ->leftJoin('systemstatus as status_blocks', 'scheduleinversedetail.secuenceid', '=', 'status_blocks.statusid')
            ->leftJoin('systemstatus as status_names', 'scheduleinversedetail.statusid', '=', 'status_names.statusid')
            ->leftJoin('users', 'scheduleinversedetail.assigneduser', '=', 'users.id')
            ->where('scheduleinverse.id', $request['id'])
            ->get();

        return $this->showDataToView($data, 'Shipments.ScheduleInverse.SchedulleDetail.buttons');
    }

    public function changestatusdetail(Request $request)
    {
        $this->authorize('scheduleinverse.changestatusdetail');
        $schedulle = ScheduleInverseDetail::find($request['id']);
        if ($schedulle->statusid === 'DIS') {
            $schedulle->statusid = 'RES';
            $message = 'Bloque reservado con exito';
        } else {
            $schedulle->statusid = 'DIS';
            $message = 'Registro activado con exito';
        }
        $schedulle->save();

        return response()->json([
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '0',
            'Message' => $message,
        ], '200');
    }
}
