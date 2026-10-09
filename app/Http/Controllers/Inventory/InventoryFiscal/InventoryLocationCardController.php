<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryAssignCounterCard;
use App\Models\Inventory\InventoryFiscal\InventoryAssignCounterLocation;
use App\Models\Inventory\InventoryFiscal\InventoryCardCount;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\Models\Inventory\InventoryFiscal\InventoryLocationCount;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class InventoryLocationCardController extends BaseController
{
    public function index($id)
    {
        $inventoryCardCount = InventoryCardCount::with('inventoryhead:id,name')->findOrFail($id);
        $inventoryHead = InventoryHead::where('id', $inventoryCardCount->inventory_head_id)->first();

        return view(
            'Inventory.InventoryHead.LocationCard.index',
            compact('inventoryCardCount', 'inventoryHead'),
        );
    }

    private function baseLocationCardQuery(Request $request, InventoryCardCount $inventoryCardCount)
    {
        return DB::table('inventory_location_counts as ilc')
            ->join('inventory_aisles as ia', 'ilc.inventory_aisle_id', '=', 'ia.id')
            ->join('inventory_card_counts', 'ilc.inventory_card_id', '=', 'inventory_card_counts.id')
            ->join('inventory_cards as ic', 'inventory_card_counts.inventory_card_id', '=', 'ic.id')
            ->join('inventory_locations as il', 'ilc.inventory_location_id', '=', 'il.id')
            ->leftJoin('inventorypartnumbers as ipn', 'ilc.inventorypartnumber01_id', '=', 'ipn.id')
            ->leftJoin('inventorypartnumbers as ipn2', 'ilc.inventorypartnumber02_id', '=', 'ipn2.id')
            ->leftJoin('users as u', 'ilc.user_count01_id', '=', 'u.id')
            ->leftJoin('users as u2', 'ilc.user_count02_id', '=', 'u2.id')
            ->leftJoin('systemstatus as s', 'ilc.statusid_count01', '=', 's.statusid')
            ->leftJoin('systemstatus as s2', 'ilc.statusid_count02', '=', 's2.statusid')
            ->where('ilc.inventory_head_id', $inventoryCardCount->inventory_head_id)
            ->where('ilc.inventory_card_id', $inventoryCardCount->id)
            ->when($request->filled('name'), function ($q) use ($request) {
                $q->where('il.name', 'like', '%' . $request->name . '%');
            });
    }

    public function getLocationCardInventory(Request $request, $id)
    {
        $inventoryCardCount = InventoryCardCount::findOrFail($id);
        $query = $this->baseLocationCardQuery($request, $inventoryCardCount)
            ->select([
                'ilc.id',
                'ilc.type',
                'ilc.statusid_count01 as statusid01',
                'ilc.statusid_count02 as statusid02',
                'ia.name as pasillo',
                'ic.name as cedula',
                'il.name as ubicacion',
                'ipn.prtnum as sku01',
                'ipn.lngdsc as description01',
                'ilc.quantity01',
                'u.name as user_count01',
                'ipn2.prtnum as sku02',
                'ipn2.lngdsc as description02',
                'ilc.quantity02',
                'u2.name as user_count02',
                's.auxiliary01 as color_count01',
                's2.auxiliary01 as color_count02',
                'ilc.statusid_location',
            ]);

        return DataTables::of($query)
            ->addColumn('btn', 'Inventory.InventoryHead.LocationCard.partials.buttons')
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function getLocationCardKpis(Request $request, $id)
    {
        $inventoryCardCount = InventoryCardCount::findOrFail($id);

        $totals = $this->baseLocationCardQuery($request, $inventoryCardCount)
            ->selectRaw("
                SUM(CASE WHEN ilc.statusid_count01 = 'F' THEN 1 ELSE 0 END) as pendientes_count01,
                SUM(CASE WHEN ilc.statusid_count01 = 'C' THEN 1 ELSE 0 END) as terminados_count01,
                SUM(CASE WHEN ilc.statusid_count02 = 'F' THEN 1 ELSE 0 END) as pendientes_count02,
                SUM(CASE WHEN ilc.statusid_count02 = 'C' THEN 1 ELSE 0 END) as terminados_count02,
                SUM(CASE WHEN ilc.statusid_location = 1 THEN 1 ELSE 0 END) as ubicaciones_correctas,
                SUM(CASE WHEN ilc.statusid_location = 0 THEN 1 ELSE 0 END) as ubicaciones_con_error
            ")
            ->first();

        return response()->json([
            'pendientesCount01' => $totals->pendientes_count01 ?? 0,
            'terminadosCount01' => $totals->terminados_count01 ?? 0,
            'pendientesCount02' => $totals->pendientes_count02 ?? 0,
            'terminadosCount02' => $totals->terminados_count02 ?? 0,
            'ubicacionesCorrectas' => $totals->ubicaciones_correctas ?? 0,
            'ubicacionesConError' => $totals->ubicaciones_con_error ?? 0,
        ]);
    }

    public function initializeCount1($id)
    {
        $inventoryCardCount = InventoryCardCount::findOrFail($id);

        $inventoryCardCount->update([
            'statusid_count01' => 'F',
            'key_user_count_01' => null,
        ]);

        InventoryLocationCount::where('inventory_head_id', $inventoryCardCount->inventory_head_id)
            ->where('inventory_card_id', $inventoryCardCount->id)
            ->update([
                'inventorypartnumber01_id' => null,
                'quantity01' => null,
                'statusid_count01' => 'F',
                'user_count01_id' => null,
                'statusid_location' => null,
                'type' => null,
            ]);

        return $this->redirectBackSuccessMessage('Conteo inicializado con exito.');
    }

    public function initializeCount2($id)
    {
        $inventoryCardCount = InventoryCardCount::findOrFail($id);

        $inventoryCardCount->update([
            'statusid_count02' => 'F',
            'key_user_count_02' => null,
        ]);

        InventoryLocationCount::where('inventory_head_id', $inventoryCardCount->inventory_head_id)
            ->where('inventory_card_id', $inventoryCardCount->id)
            ->update([
                'inventorypartnumber02_id' => null,
                'quantity02' => null,
                'statusid_count02' => 'F',
                'user_count02_id' => null,
                'statusid_location' => null,
            ]);

        return $this->redirectBackSuccessMessage('Conteo inicializado con exito.');
    }

    public function validateCard($id)
    {
        try {
            return DB::transaction(function () use ($id) {
                $inventoryCardCount = InventoryCardCount::findOrFail($id);

                $base = InventoryLocationCount::where('inventory_head_id', $inventoryCardCount->inventory_head_id)
                    ->where('inventory_card_id', $inventoryCardCount->id)
                    ->where('statusid_count01', 'C')
                    ->where('statusid_count02', 'C')
                    ->whereNotNull('quantity01')
                    ->whereNotNull('quantity02')
                    ->whereNotNull('inventorypartnumber01_id')
                    ->whereNotNull('inventorypartnumber02_id');

                (clone $base)
                    ->whereColumn('quantity01', 'quantity02')
                    ->whereColumn('inventorypartnumber01_id', 'inventorypartnumber02_id')
                    ->update(['statusid_location' => true]);

                (clone $base)
                    ->where(fn ($q) => $q
                        ->whereColumn('quantity01', '!=', 'quantity02')
                        ->orWhereColumn('inventorypartnumber01_id', '!=', 'inventorypartnumber02_id'),
                    )
                    ->update(['statusid_location' => false]);

                $locationsErrors = InventoryLocationCount::query()
                    ->select('id')
                    ->where('inventory_head_id', $inventoryCardCount->inventory_head_id)
                    ->where('inventory_card_id', $inventoryCardCount->id)
                    ->where('statusid_location', 0)
                    ->get();

                if ($locationsErrors->isNotEmpty()) {
                    $now = now();

                    // Valida que no exista ya una asignación abierta ('F') o activa ('A') para esta cédula,
                    // evitando crear registros duplicados de InventoryAssignCounterCard.
                    $inventoryCardExist = InventoryAssignCounterCard::query()
                        ->where('inventory_head_id', $inventoryCardCount->inventory_head_id)
                        ->where('inventory_card_count_id', $inventoryCardCount->id)
                        ->whereIn('statusid', ['F', 'A'])
                        ->exists();

                    // Si ya existe, se sale sin crear nada nuevo (evita duplicados).
                    if ($inventoryCardExist) {
                        return $this->errorResponse('Ya existe una asignación de conteo activa para esta cédula.');
                    }

                    // Solo continúa si NO existe una asignación previa.
                    $inventoryCard = InventoryAssignCounterCard::create([
                        'inventory_head_id' => $inventoryCardCount->inventory_head_id,
                        'inventory_card_count_id' => $inventoryCardCount->id,
                        'statusid' => 'F',
                    ]);

                    $rows = $locationsErrors->map(fn ($locationError) => [
                        'inventory_assign_counter_card_id' => $inventoryCard->id,
                        'inventory_location_count_id' => $locationError->id,
                        'statusid' => 'F',
                        'created_at' => $now,
                        'updated_at' => $now,
                    ])->all();

                    InventoryAssignCounterLocation::insert($rows);
                }

                return $this->redirectBackSuccessMessage('Conteo inicializado con éxito.');
            });
        } catch (ModelNotFoundException $e) {
            Log::warning('validateCard: InventoryCardCount no encontrado', ['id' => $id]);

            return $this->errorResponse('La cédula de conteo indicada no existe.');
        } catch (\Throwable $e) {
            Log::error('Error en validateCard', [
                'inventory_card_count_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse('Ocurrió un error al validar la cédula.');
        }
    }

    public function terceronteo($id)
    {
        dd($id);
    }
}
