<?php

namespace App\Http\Controllers\Yard\ReceiptUnit\Inventory;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Inventory\CreateNewInventoryRequest;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Yard\InventoryBox;
use App\Models\Yard\InventoryCountStock;
use App\Models\Yard\ReceiptUnit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryBoxesController extends BaseController
{
    public function index()
    {
        $this->authorize('inventorybox.index');
        $status = SystemStatus::whereSchemaid('IBX')
            ->select(['statusid', 'name'])->get();
        $centers = $this->getCenterForSelect();
        $types = SystemStatus::whereSchemaid('INC')->select('name', 'statusid')->get();

        return view('Yards.InventoryBoxes.index', compact('status', 'centers', 'types'));
    }

    public function getdatainventories(Request $request)
    {
        $data = InventoryBox::controlAccessUser()
            ->leftjoin('systemcenterdistributions as s', 'inventoryboxstock.centerid', '=', 's.centerid')
            ->leftjoin('systemstatus as s2', 'inventoryboxstock.statusid', '=', 's2.statusid')
            ->leftjoin('systemstatus as s3', 'inventoryboxstock.statustypeid', '=', 's3.statusid')
            ->leftjoin('users as u', 'inventoryboxstock.createuser', '=', 'u.id')
            ->ForStatus($request->get('statusid'))
            ->ForFolio($request->get('folio'))
            ->select([
                's.name as centername', 's3.name as type', 'inventoryboxstock.description',
                'inventoryboxstock.createdate', 'inventoryboxstock.quantitybox', 'inventoryboxstock.id as folioinv',
                'inventoryboxstock.quantityboxtrue', 'u.name as user', 's2.name as status', 'inventoryboxstock.id', 's2.statusid',
            ])->get();

        return $this->showDataToView($data, 'Yards.InventoryBoxes.partials.buttons');
    }

    public function store(CreateNewInventoryRequest $request)
    {

        $total = ReceiptUnit::getCountUnit($request['centerid']);
        $inventory = InventoryBox::createNewInventory($request, $total);
        DB::beginTransaction();
        try {
            $units = ReceiptUnit::whereCenterid($inventory->centerid)
                ->select(['id', 'barcode', 'boxnumber', 'zoneid', 'locationid', 'statusid'])
                ->whereNotIn('statusid', ['TR'])
                ->get();
            foreach ($units as $unit) {
                InventoryCountStock::createDetailCount($inventory, $unit);
            }
            DB::commit();

            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Inventario agregado correctamente',
                ], 200);
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            DB::rollBack();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al crear  el inventario : ' . $e->getMessage(),
                ], 500);
            }
        }
    }

    public function edit(InventoryBox $inventorybox)
    {
        $this->authorize('inventorybox.edit');
        $data = InventoryCountStock::controlAccessUser()->where('countid', $inventorybox->id)
            ->with(['center:centerid,name,description', 'status:statusid,name', 'locationold:locationid,name',
                'locationnew:locationid,name', 'user:id,name', 'zoneold:zoneid,name',
                'zonenew:zoneid,name', 'inventory', 'folio',
            ])
            ->get();

        return view('Yards.InventoryBoxes.edit', compact('inventorybox', 'data'));
    }

    public function update(InventoryBox $inventorybox)
    {
        $inventorybox->statusid = 'TO';
        $inventorybox->save();

        return $this->redirectBackSuccess();
    }

    public function destroy(InventoryBox $inventorybox, Request $request)
    {
        $this->authorize('inventorybox.destroy');
        $inventorybox->delete();

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Inventario agregado correctamente',
            ], 200);
        }

    }

    public function activateInventory(Request $request)
    {
        $this->authorize('inventorybox.edit');
        $inventoryboxActive = InventoryBox::controlAccessUser()->where('statusid', 'AT')->first();
        if (! empty($inventoryboxActive)) {
            $inventoryboxActive->statusid = 'TO';
            $inventoryboxActive->save();
        }
        $inventorybox = InventoryBox::controlAccessUser()->where('id', $request['id'])->first();
        $inventorybox->statusid = 'AT';
        $inventorybox->save();

        return response()->json('Registro actualizado con exito.');
    }
}
