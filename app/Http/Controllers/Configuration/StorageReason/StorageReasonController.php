<?php

namespace App\Http\Controllers\Configuration\StorageReason;

use App\Http\Controllers\BaseController;
use App\Models\Yard\StorageReason;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class StorageReasonController extends BaseController
{
    public function index()
    {
        $this->authorize('storagereasons.index');

        return view('Configuration.StorageReasons.index');
    }

    public function getDataTypeMovements(Request $request)
    {
        $data = StorageReason::with(['status' => function ($q) {
            $q->select('statusid', 'name');
        },
        ])
            ->Name($request->get('name'))
            ->select('storagereasons.*');

        return $this->showDataToView($data, 'Configuration.StorageReasons.partials.buttons');
    }

    public function create()
    {
        $this->authorize('storagereasons.create');

        return view('Configuration.StorageReasons.create');
    }

    public function store(Request $request): RedirectResponse
    {
        DB::transaction(function () use ($request) {
            $data = [
                'name' => $request->input('name'),
                'razonstatusid' => $request->input('razonstatusid'),
                'type' => $request->input('type'),
                'auxiliary01' => $request->input('auxiliary01'),
                'auxiliary02' => $request->input('auxiliary02'),
                'auxiliary03' => $request->input('auxiliary03'),
                'auxiliary04' => $request->input('auxiliary04'),
                'auxiliary05' => $request->input('auxiliary05'),
                'schemaid' => 'STS',
                'statusid' => 'E',
                'created_at' => now(),
                'updated_at' => now(),
            ];

            if ($request->filled('id')) {
                $data['id'] = $request->input('id');

                $columns = implode(', ', array_keys($data));

                DB::unprepared(
                    'SET IDENTITY_INSERT storagereasons ON; '
                    . "INSERT INTO storagereasons ({$columns}) VALUES ("
                    . implode(', ', array_map(function ($value) {
                        return $value === null ? 'NULL' : "'" . str_replace("'", "''", $value) . "'";
                    }, array_values($data)))
                    . '); SET IDENTITY_INSERT storagereasons OFF;'
                );
            } else {
                DB::table('storagereasons')->insert($data);
            }
        });

        return $this->successResponse('storagereasons.index');
    }

    public function edit(StorageReason $storagereason)
    {
        $this->authorize('storagereasons.edit');
        $status = $this->getStatusSTS();

        return view('Configuration.StorageReasons.edit', compact('storagereason', 'status'));
    }

    public function update(StorageReason $storagereason, Request $request): RedirectResponse
    {
        $storagereason->update($request->all());

        return $this->infoResponse('storagereasons.index');
    }
}
