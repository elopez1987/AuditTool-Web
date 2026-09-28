<?php

namespace App\Http\Controllers\Configuration\LogSystem;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Configuration\ActiviyLog\SearchActivityLogRequest;
use App\Services\Configuration\ActivityLogService;
use App\ViewModels\Configuration\ActivityLogViewModel;
use Yajra\DataTables\Facades\DataTables;

class ActivityLogController extends Controller
{
    public function __construct(
        private readonly ActivityLogViewModel $logViewModel,
        private readonly ActivityLogService $logService,
    ) {}

    public function index()
    {
        $this->authorize('activitylog.index');

        return view(
            'Configuration.ActivityLogs.index',
            $this->logViewModel->index(),
        );
    }

    public function getDataActivityLog(SearchActivityLogRequest $request)
    {

        $data = $this->logService->all($request->validated());

        return DataTables::of($data)
            ->addColumn('btn', 'Configuration.ActivityLogs.partials.buttons')
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function show(int $id)
    {
        return view('Configuration.ActivityLogs.show', $this->logViewModel->show($id));
    }
}
