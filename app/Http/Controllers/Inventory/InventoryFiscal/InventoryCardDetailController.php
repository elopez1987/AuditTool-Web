<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class InventoryCardDetailController extends BaseController
{
    public function index($id)
    {
        $inventoryHead = InventoryHead::where('inventory_uuid', $id)->first();

        return view('Inventory.InventoryHead.CardDetail.index',
            ['inventoryHead' => $inventoryHead]);
    }

    public function getCardsInventory(Request $request, $id)
    {
        $query = DB::table('inventory_card_counts as icc')
            ->join('inventory_aisles as ia', 'icc.inventory_aisle_id', '=', 'ia.id')
            ->join('inventory_cards as ic', 'icc.inventory_card_id', '=', 'ic.id')
            ->leftJoin('users as u', 'icc.user_count01_id', '=', 'u.id')
            ->leftJoin('users as u2', 'icc.user_count02_id', '=', 'u2.id')
            ->leftJoin('systemstatus as s', 'icc.statusid_count01', '=', 's.statusid')
            ->leftJoin('systemstatus as s2', 'icc.statusid_count02', '=', 's2.statusid')
            ->where('icc.inventory_head_id', $id)
            ->when($request->input('name'), function ($q) use ($request) {
                $q->where('ia.name', 'like', '%' . $request->input('name') . '%');
            })
            ->when($request->input('cardname'), function ($q) use ($request) {
                $q->where('ic.name', 'like', '%' . $request->input('cardname') . '%');
            })
            ->select([
                'icc.id',
                'icc.inventory_head_id',
                'icc.statusid_count01 as statusid01',
                'icc.statusid_count02 as statusid02',
                'ia.name as pasillo',
                'ic.name as cedula',
                'u.name as user_count01',
                'u2.name as user_count02',
                's.name as statusid_count01',
                's2.name as statusid_count02',
                'icc.key_user_count_01',
                'icc.key_user_count_02',
                's.auxiliary01 as color_count01',
                's2.auxiliary01 as color_count02',
            ]);

        // Estos permisos no dependen de la fila (son abilities globales, no
        // por modelo), por lo que se evalúan UNA sola vez para toda la
        // respuesta en vez de una vez por fila. Antes, con 300 filas, la
        // columna 'btn' hacía view(...)->render() (resolución + compilación
        // Blade) más 4 chequeos @can por fila -> 300 renders + 1200 checks
        // de permisos, lo que explica la mayor parte de los 1.45s medidos:
        // las dos consultas SQL (conteo + selección), ya indexadas por
        // inventory_head_id, tardan solo unos pocos milisegundos.
        $permissions = [
            'show' => Gate::allows('inventorylocations-card.show'),
            'printformat01' => Gate::allows('inventorylocations-card.printformat01'),
            'printformat02' => Gate::allows('inventorylocations-card.printformat02'),
            'destroy' => Gate::allows('inventoryheads.destroy'),
            'clearuser' => Gate::allows('inventoryheads.clearuser'),
        ];

        return DataTables::of($query)
            ->addColumn('btn', fn ($row) => $this->renderButtons($row, $permissions))
            ->rawColumns(['btn'])
            ->toJson();
    }

    /**
     * Construye el HTML de la columna de acciones sin pasar por el motor de
     * vistas de Blade (view()->render()) en cada fila, ya que para un
     * datatable de cientos de filas ese costo de resolución/compilación se
     * vuelve el cuello de botella dominante frente a la propia consulta SQL.
     * Debe reflejar exactamente los mismos botones que
     * Inventory.InventoryHead.CardDetail.buttons.
     */
    private function renderButtons($row, array $permissions): string
    {
        $html = '';

        if ($permissions['show']) {
            $html .= '<a href="' . url('inventorylocations-card/' . $row->id) . '" class="ml-2 mr-2">'
                . '<button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm">'
                . '<img src="' . asset('/ico/viewdetail.png') . '" alt="" class="img-16 ml-2 mr-2"><span> Detalle</span>'
                . '</button></a>';
        }

        if ($permissions['printformat01'] && $row->statusid01 == 'C') {
            $html .= '<a href="' . url('print-format-inventory-01/' . $row->id) . '" class="ml-2 mr-2">'
                . '<button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm">'
                . '<img src="' . asset('/ico/pdf.png') . '" alt="" class="img-16 ml-2 mr-2"><span> Formato 01</span>'
                . '</button></a>';
        }

        if ($permissions['printformat02'] && $row->statusid02 == 'C') {
            $html .= '<a href="' . url('print-format-inventory-02/' . $row->id) . '" class="ml-2 mr-2">'
                . '<button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm">'
                . '<img src="' . asset('/ico/pdf.png') . '" alt="" class="img-16 ml-2 mr-2"><span> Formato 02</span>'
                . '</button></a>';
        }

        if ($permissions['destroy']) {
            $html .= '<button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm btn-delete ml-2 mr-2" data-id="' . $row->id . '">'
                . '<img src="' . asset('/ico/refuseunit.png') . '" alt="" class="img-16 ml-2 mr-2"><span> Eliminar</span>'
                . '</button>';
        }

        if ($permissions['clearuser']) {
            $html .= '<button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm btn-clear01 ml-2 mr-2" data-id="' . $row->id . '">'
                . '<img src="' . asset('/ico/reloadpage.png') . '" alt="" class="img-16 ml-2 mr-2"><span> Desasignar 1</span>'
                . '</button>';
            $html .= '<button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm btn-clear02 ml-2 mr-2" data-id="' . $row->id . '">'
                . '<img src="' . asset('/ico/reloadpage.png') . '" alt="" class="img-16 ml-2 mr-2"><span> Desasignar 2</span>'
                . '</button>';
        }

        return $html;
    }

    public function destroy($id)
    {
        try {

            $inventoryCard = DB::table('inventory_card_counts')->where('id', $id)->first();

            DB::table('inventory_location_counts')->where('inventory_card_id', $inventoryCard->id)->delete();
            DB::table('inventory_card_counts')->where('id', $id)->delete();

            return response()->json(['success' => true, 'message' => 'Cédula eliminada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al eliminar: ' . $e->getMessage()], 500);
        }
    }

    public function clearUser01($id)
    {
        Log::info('Desasignando usuario 1 de la cédula con ID: ' . $id);
        try {
            DB::table('inventory_card_counts')->where('id', $id)->update([
                'user_count01_id' => null,
            ]);

            return response()->json(['success' => true, 'message' => 'Cédula desasignada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al desasignar: ' . $e->getMessage()], 500);
        }
    }

    public function clearUser02($id)
    {
        Log::info('Desasignando usuario 2 de la cédula con ID: ' . $id);
        try {
            DB::table('inventory_card_counts')->where('id', $id)->update([
                'user_count02_id' => null,
            ]);

            return response()->json(['success' => true, 'message' => 'Cédula desasignada correctamente.']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'Error al desasignar: ' . $e->getMessage()], 500);
        }
    }
}
