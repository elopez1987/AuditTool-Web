<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Inventory\InventoryFiscal\InventoryAdjustLocationRequest;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use App\Models\Inventory\InventoryFiscal\InventoryAccountant;
use App\Models\Inventory\InventoryFiscal\InventoryAssignCounterCard;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\Models\Inventory\InventoryFiscal\InventoryLocationCount;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\Facades\DataTables;

class InventoryErrorsController extends BaseController
{
    public function index($id)
    {
        $inventoryhead = InventoryHead::controlAccessUser()
            ->where('inventory_uuid', $id)
            ->firstOrFail();

        return view(
            'Inventory.InventoryHead.ErrorsDetail.index',
            compact('inventoryhead'),
        );
    }

    /**
     * Detalle de ubicaciones con error (server-side).
     */
    public function getDetailInventoryErrors(Request $request, $id)
    {
        $headId = (int) ($request->input('inventory_head_id') ?: $id);

        $data = DB::table('inventory_location_counts as ilc')
            ->select([
                'ilc.id',
                'ias.name as aisle_name',
                'ic.name as card_name',
                'il.name as location_name',
                'ilc.inventorypartnumber01_id',
                'ilc.quantity01',
                'ilc.inventorypartnumber02_id',
                'ilc.quantity02',
                'ilc.type',
                'iv1.prtnum as sku01',
                'iv2.prtnum as sku02',
            ])
            ->join('inventory_locations as il', 'ilc.inventory_location_id', '=', 'il.id')
            ->join('inventory_card_counts as icc', 'ilc.inventory_card_id', '=', 'icc.id')
            ->join('inventory_cards as ic', 'icc.inventory_card_id', '=', 'ic.id')
            ->join('inventory_aisles as ias', 'ic.inventory_aisle_id', '=', 'ias.id')
            ->leftJoin('inventorypartnumbers as iv1', 'ilc.inventorypartnumber01_id', '=', 'iv1.id')
            ->leftJoin('inventorypartnumbers as iv2', 'ilc.inventorypartnumber02_id', '=', 'iv2.id')
            ->where('ilc.inventory_head_id', $headId)
            ->where('ilc.statusid_location', 0)
            ->when($request->filled('name'), function ($query) use ($request) {
                $term = trim((string) $request->input('name'));

                $query->where(function ($q) use ($term) {
                    $q->where('ic.name', 'like', '%' . $term . '%')
                        ->orWhere('il.name', 'like', '%' . $term . '%')
                        ->orWhere('ias.name', 'like', '%' . $term . '%')
                        ->orWhere('iv1.prtnum', 'like', '%' . $term . '%')
                        ->orWhere('iv2.prtnum', 'like', '%' . $term . '%');
                });
            });

        return $this->showDataToView($data, 'Inventory.InventoryHead.ErrorsDetail.buttons');
    }

    /**
     * Concentrado de errores por cédula (server-side).
     */
    public function getConcentratedInventoryErrors(Request $request, $id)
    {
        $headId = (int) $id;

        $data = DB::table('inventory_location_counts as ilc')
            ->leftJoin('inventory_card_counts as icc', 'ilc.inventory_card_id', '=', 'icc.id')
            ->leftJoin('inventory_cards as ic', 'icc.inventory_card_id', '=', 'ic.id')
            ->select([
                'ilc.inventory_card_id',
                'ic.name as cardname',
                DB::raw('count(ilc.id) as totalerrores'),
            ])
            ->where('ilc.inventory_head_id', $headId)
            ->where('ilc.statusid_location', 0)
            ->when($request->filled('name'), function ($query) use ($request) {
                $term = trim((string) $request->input('name'));
                $query->where('ic.name', 'like', '%' . $term . '%');
            })
            ->groupBy('ic.name', 'ilc.inventory_card_id');

        return DataTables::of($data)
            ->addColumn('btn', function ($row) {
                $cardId = $row->inventory_card_id;
                $url = url('printCardErrors/' . $cardId);

                return '<a href="' . $url . '" class="ml-2 mr-2">'
                    . '<button type="button" class="btn waves-effect waves-light btn-outline-info btn-sm">'
                    . '<img src="' . asset('/ico/pdf.png') . '" alt="" class="img-16 ml-2 mr-2">'
                    . '<span> Imprimir</span>'
                    . '</button></a>';
            })
            // PUT validateCard en InventoryLocationCardController (crea tareas 3er conteo)
            ->addColumn('btn3', function ($row) {
                $cardId = (int) $row->inventory_card_id;
                $url = url('validateCard/' . $cardId);
                $csrf = csrf_token();

                return '<form method="POST" action="' . $url . '" class="d-inline ml-2 mr-2" '
                    . 'onsubmit="return confirm(\'¿Generar tareas de 3er conteo para esta cédula?\');">'
                    . '<input type="hidden" name="_token" value="' . $csrf . '">'
                    . '<input type="hidden" name="_method" value="PUT">'
                    . '<button type="submit" class="btn waves-effect waves-light btn-outline-info btn-sm">'
                    . '<img src="' . asset('/ico/adreload.png') . '" alt="" class="img-16 ml-2 mr-2">'
                    . '<span> Generar 3er conteo</span>'
                    . '</button></form>';
            })
            ->rawColumns(['btn', 'btn3'])
            ->orderColumn('cardname', 'ic.name $1')
            ->orderColumn('totalerrores', 'count(ilc.id) $1')
            ->toJson();
    }

    /**
     * Cédulas pendientes de asignar contador 3er conteo (server-side).
     */
    public function getPendingAssignInventoryErrors(Request $request, $id)
    {
        $headId = (int) $id;

        $users = InventoryAccountant::query()
            ->where('inventory_head_id', $headId)
            ->join('users', 'inventory_accountants.user_id', '=', 'users.id')
            ->select('users.id', 'users.name')
            ->get();

        $data = InventoryAssignCounterCard::query()
            ->where('inventory_assign_counter_cards.inventory_head_id', $headId)
            ->join('inventory_card_counts', 'inventory_assign_counter_cards.inventory_card_count_id', '=', 'inventory_card_counts.id')
            ->join('inventory_cards', 'inventory_card_counts.inventory_card_id', '=', 'inventory_cards.id')
            ->select([
                'inventory_assign_counter_cards.id',
                'inventory_cards.name as card_name',
                'inventory_assign_counter_cards.statusid',
            ])
            ->where('inventory_assign_counter_cards.statusid', 'F')
            ->when($request->filled('name'), function ($query) use ($request) {
                $term = trim((string) $request->input('name'));
                $query->where('inventory_cards.name', 'like', '%' . $term . '%');
            });

        return DataTables::of($data)
            ->addColumn('btn', function ($row) use ($users) {
                return view('Inventory.InventoryHead.ErrorsDetail.buttons-assign', [
                    'id' => $row->id,
                    'users' => $users,
                ])->render();
            })
            ->rawColumns(['btn'])
            ->orderColumn('card_name', 'inventory_cards.name $1')
            ->toJson();
    }

    public function inventoryadjustcount(InventoryAdjustLocationRequest $request, $id)
    {
        try {
            return DB::transaction(function () use ($request, $id) {

                $locationAdjust = InventoryLocationCount::where('id', $id)
                    ->select(['id', 'inventorypartnumber01_id', 'quantity01',
                        'inventorypartnumber02_id', 'quantity02', 'statusid_location'])
                    ->first();

                if (is_null($locationAdjust)) {
                    return $this->errorResponse('La ubicación no existe');
                }

                $partnumber01 = InventoryPartnumber::controlAccessUser()
                    ->where('prtnum', $request->partnumber01)
                    ->select(['id', 'prtnum'])
                    ->first();

                if (is_null($partnumber01)) {
                    return $this->errorResponse('El número de parte no existe :' . $request->partnumber01);
                }

                $partnumber02 = InventoryPartnumber::controlAccessUser()
                    ->where('prtnum', $request->partnumber02)
                    ->select(['id', 'prtnum'])
                    ->first();

                if (is_null($partnumber02)) {
                    return $this->errorResponse('El número de parte no existe :' . $request->partnumber02);
                }

                $statusLocation = ($partnumber01->id === $partnumber02->id && (int) $request->quantity01 === (int) $request->quantity02) ? 1 : 0;

                $locationAdjust->update([
                    'inventorypartnumber01_id' => $partnumber01->id,
                    'inventorypartnumber02_id' => $partnumber02->id,
                    'quantity01' => $request->quantity01,
                    'quantity02' => $request->quantity02,
                    'statusid_location' => $statusLocation,
                ]);

                return $this->redirectBackSuccessMessage('Ubicación ajustada correctamente');
            });
        } catch (\Throwable $e) {
            Log::error('Error en inventoryadjustcount', [
                'location_id' => $id,
                'message' => $e->getMessage(),
            ]);

            return $this->errorResponse('Ocurrió un error al ajustar el conteo de la ubicación.');
        }
    }

    public function printCardErrors($cardId)
    {
        $pathImage = SystemDetailParameter::where('parameterid', '=', 'PARAM-IMG')->first();
        $locations = DB::table('inventory_location_counts as ilc')
            ->leftJoin('inventory_card_counts as icc', 'ilc.inventory_card_id', '=', 'icc.id')
            ->leftJoin('inventory_cards as ic', 'icc.inventory_card_id', '=', 'ic.id')
            ->leftJoin('inventory_locations as il', 'ilc.inventory_location_id', '=', 'il.id')
            ->select('ic.name as cardname', 'il.name as locationname')
            ->where('ilc.inventory_card_id', $cardId)
            ->where('ilc.statusid_location', 0)
            ->get();

        $fortmat = 'Inventory.InventoryHead.ErrorsDetail.card-errors';

        $pdf = \PDF::setOptions([
            'isRemoteEnabled' => true,
            'chroot' => [
                realpath(base_path()),
                realpath($pathImage->value01),
            ],
            'httpContext' => stream_context_create([
                'ssl' => [
                    'verify_peer' => false,
                    'verify_peer_name' => false,
                    'allow_self_signed' => true,
                ],
            ]),
        ])->loadView($fortmat, compact('locations'));

        return $pdf->download('Cédula de conteo-' . '.pdf');
    }

    public function assignCounter(Request $request, $id)
    {
        InventoryAssignCounterCard::where('id', $id)
            ->update([
                'user_id' => $request->userid,
                'statusid' => 'A',
            ]);

        return $this->redirectBackSuccessMessage('Usuario asignado correctamente');
    }

    /**
     * Asignaciones de cédulas a contadores (3er conteo) — server-side.
     */
    public function inventorycardassing(Request $request, $inventoryId)
    {
        $headId = (int) $inventoryId;

        $users = InventoryAccountant::query()
            ->where('inventory_head_id', $headId)
            ->join('users', 'inventory_accountants.user_id', '=', 'users.id')
            ->select('users.id', 'users.name')
            ->orderBy('users.name')
            ->get();

        $asignaciones = DB::table('inventory_assign_counter_cards as iacc')
            ->leftJoin('users as u', 'iacc.user_id', '=', 'u.id')
            ->leftJoin('systemstatus as s', 'iacc.statusid', '=', 's.statusid')
            ->join('inventory_card_counts as icc', 'iacc.inventory_card_count_id', '=', 'icc.id')
            ->join('inventory_cards as ic', 'icc.inventory_card_id', '=', 'ic.id')
            ->where('iacc.inventory_head_id', $headId)
            ->when($request->filled('name'), function ($query) use ($request) {
                $term = trim((string) $request->input('name'));

                $query->where(function ($q) use ($term) {
                    $q->where('ic.name', 'like', '%' . $term . '%')
                        ->orWhere('u.name', 'like', '%' . $term . '%')
                        ->orWhere('s.name', 'like', '%' . $term . '%');
                });
            })
            ->select([
                'iacc.id',
                'iacc.statusid',
                'u.name as user_name',
                'ic.name as card_name',
                's.name as status',
            ]);

        return DataTables::of($asignaciones)
            ->addColumn('btn', function ($row) use ($users) {
                // Solo permite cambiar usuario si la cédula no está terminada (C)
                if (($row->statusid ?? '') === 'C') {
                    return '';
                }

                return view('Inventory.InventoryHead.ErrorsDetail.buttons-change-user', [
                    'id' => $row->id,
                    'users' => $users,
                    'currentUserId' => null,
                ])->render();
            })
            ->rawColumns(['btn'])
            ->orderColumn('card_name', 'ic.name $1')
            ->orderColumn('user_name', 'u.name $1')
            ->orderColumn('status', 's.name $1')
            ->toJson();
    }

    /**
     * Cambia el usuario asignado a una cédula de 3er conteo.
     */
    public function assingnewuser(Request $request, $id)
    {
        $validated = $request->validate([
            'user_id' => ['required', 'integer'],
        ]);

        $assignment = InventoryAssignCounterCard::query()
            ->where('id', $id)
            ->first();

        if (is_null($assignment)) {
            return $this->errorResponse('La asignación no existe.');
        }

        if ($assignment->statusid === 'C') {
            return $this->errorResponse('No se puede cambiar el usuario de una cédula terminada.');
        }

        $isAccountant = InventoryAccountant::query()
            ->where('inventory_head_id', $assignment->inventory_head_id)
            ->where('user_id', $validated['user_id'])
            ->exists();

        if (! $isAccountant) {
            return $this->errorResponse('El usuario seleccionado no es contador de este inventario.');
        }

        $assignment->update([
            'user_id' => $validated['user_id'],
            'statusid' => $assignment->statusid === 'F' ? 'A' : $assignment->statusid,
        ]);

        return $this->redirectBackSuccessMessage('Usuario actualizado correctamente.');
    }
}
