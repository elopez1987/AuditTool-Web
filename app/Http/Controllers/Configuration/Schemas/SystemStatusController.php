<?php

namespace App\Http\Controllers\Configuration\Schemas;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\Schemas\CreateStatusRequest;
use App\Models\Configuration\Schemas\SystemSchema;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Services\Configuration\ServiceSystemCenterDistribution;
use App\Services\Configuration\ServiceSystemWarehouse;
use App\Services\Configuration\SystemSchemaService;
use App\Services\Configuration\SystemStatusService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SystemStatusController extends BaseController
{
    public function __construct(
        private readonly SystemStatusService $statusService,
        private readonly SystemSchemaService $schemaService,
        private readonly ServiceSystemCenterDistribution $centerDistribution,
        private readonly ServiceSystemWarehouse $systemWarehouse,
    ) {}

    public function index($id)
    {
        $systemschema = $this->schemaService->findById($id);
        $centers = $this->centerDistribution->getCenters();
        $warehouses = $this->systemWarehouse->getWarehouses();
        $statusList = $this->statusService->findBySchema($id);

        return view(
            'Configuration.SystemSchemas.createstatus',
            compact('systemschema', 'centers', 'warehouses', 'statusList')
        );
    }

    public function store(CreateStatusRequest $request, $id): RedirectResponse
    {
        $schema = SystemSchema::where('schemaid', $id)->first();
        SystemStatus::create([
            'schemaid' => $schema->schemaid,
            'statusid' => $request['statusid'],
            'name' => $request['namestatus'],
            'comment' => $request['comment'],
            'auxiliary01' => $request['auxiliary01'],
            'auxiliary02' => $request['centerid'],
            'auxiliary03' => $request['warehouseid'],
            'auxiliary04' => $request['auxiliary04'],
            'auxiliary05' => $request['auxiliary05'],
        ]);

        return $this->redirectBackInfo();
    }

    public function update($id, Request $request)
    {
        $status = SystemStatus::findOrFail($id);
        $status->centerid = $request['centerid'] ?? '';
        $status->warehouseid = $request['warehouseid'] ?? '';
        $status->name = $request['namestatus'];
        $status->comment = $request['comment'] ?? '';
        $status->auxiliary01 = $request['auxiliary01'] ?? '';
        $status->auxiliary02 = $request['auxiliary02'] ?? '';
        $status->auxiliary03 = $request['auxiliary03'] ?? '';
        $status->auxiliary04 = $request['auxiliary04'] ?? '';
        $status->auxiliary05 = $request['auxiliary05'] ?? '';

        $status->save();

        return $this->redirectBackSuccess();
    }

    public function destroy($id)
    {
        SystemStatus::destroy($id);

        return $this->redirectBackSuccessMessage('Estatus eliminado con exito.');
    }
}
