<?php

namespace App\Http\Controllers\Inventory\InventoryFiscal;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Inventory\InventoryFiscal\InventoryAdjustLocationRequest;
use App\Models\Inventory\InventoryFiscal\InventoryHead;
use App\Models\Inventory\InventoryFiscal\InventoryLocationCount;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class InventoryErrorsController extends BaseController
{
    public function index($id)
    {
        $inventoryhead = InventoryHead::controlAccessUser()
            ->where('inventory_uuid', $id)
            ->firstOrFail();

        $cedulas = DB::table('inventory_location_counts as ilc')
            ->leftJoin('inventory_card_counts as icc', 'ilc.inventory_card_id', '=', 'icc.id')
            ->leftJoin('inventory_cards as ic', 'icc.inventory_card_id', '=', 'ic.id')
            ->select(
                'ilc.inventory_card_id',
                'ic.name as cardname',
                DB::raw('count(icc.inventory_card_id) as totalerrores'),
            )
            ->where('ilc.inventory_head_id', $inventoryhead->id)
            ->where('ilc.statusid_location', 0)
            ->groupBy('ic.name', 'ilc.inventory_card_id')->get();

        return view('Inventory.InventoryHead.ErrorsDetail.index',
            compact('inventoryhead', 'cedulas'));
    }

    public function getDetailInventoryErrors(Request $request)
    {
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
            ->where('ilc.inventory_head_id', $request->inventory_head_id)
            ->where('ilc.statusid_location', 0);

        return $this->showDataToView($data, 'Inventory.InventoryHead.ErrorsDetail.buttons');
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
}
