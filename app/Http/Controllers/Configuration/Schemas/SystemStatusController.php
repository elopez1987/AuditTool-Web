<?php

namespace App\Http\Controllers\Configuration\Schemas;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\Schemas\CreateStatusRequest;
use App\Models\Configuration\Schemas\SystemSchema;
use App\Models\Configuration\Schemas\SystemStatus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SystemStatusController extends BaseController
{
    public function addStatus($id)
    {
        $systemschema = SystemSchema::where('schemaid', $id)->first();
        $status = $this->getStatusForSchema($systemschema);
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();
        $statusList = SystemStatus::where('schemaid', $systemschema->schemaid)->get();

        return view(
            'Configuration.SystemSchemas.createstatus',
            compact('systemschema', 'status', 'centers', 'warehouses', 'statusList')
        );
    }

    public function storeStatus(CreateStatusRequest $request, $id): RedirectResponse
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

    public function updatestatus($id, Request $request)
    {
        $status = SystemStatus::findOrFail($id);
        $status->centerid = $request['centerid'] ?? '';
        $status->warehouseid = $request['warehouseid'] ?? '';
        $status->name = $request['namestatus'];
        $status->comment = $request['comment'] ?? '';
        $status->auxiliary01 = $request['auxiliary01'] ?? '';  // Se utiliza para el color del estatus en la celda
        $status->auxiliary02 = $request['auxiliary02'] ?? '';
        $status->auxiliary03 = $request['auxiliary03'] ?? '';
        $status->auxiliary04 = $request['auxiliary04'] ?? '';
        $status->auxiliary05 = $request['auxiliary05'] ?? '';

        $status->save();

        return $this->redirectBackSuccess();
    }

    public function destroystatus($id)
    {
        SystemStatus::destroy($id);

        return $this->redirectBackSuccessMessage('Estatus eliminado con exito.');
    }
}
