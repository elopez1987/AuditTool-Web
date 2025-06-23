<?php

namespace App\Http\Controllers\Counting;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\FiscalInventory\Cards\InventoryCard;
use App\Models\Inventory\FiscalInventory\Dron\InventoryLocationForDron;
use App\Models\Inventory\FiscalInventory\Head\InventoryHead;
use App\Traits\Counting\QueryInventoryHead;
use DB;
use Illuminate\Http\Request;
use PDF;


class InventoryEventHeadController extends BaseController
{
    use QueryInventoryHead;

    public function activeinventoryHead($id)
    {
        $this->authorize('inventoryhead.active');
        $inventory = InventoryHead::findOrFail($id);
        $inventory->startdate = now();
        $inventory->statusid = 'AT';
        $inventory->save();
        return $this->redirectBackSuccessMessage('Inventario activado con exito');
    }

    public function closeinventoryHead($id)
    {
        $this->authorize('inventoryhead.close');
        $inventory = InventoryHead::findOrFail($id);
        $inventory->finishdate = now();
        $inventory->statusid = 'TO';
        $inventory->save();
        return $this->redirectBackSuccessMessage('Inventario cerrado con exito');
    }

    public function createCardToInventory($id)
    {
        $cards = InventoryCard::controlAccessUser()->where('inventoryid', $id)->first();
        if (!empty($cards)) {
            return $this->errorResponse('Las cedulas de este inventario ya estan generadas.');
        }
        $inventoryHead = InventoryHead::controlAccessUser()->findOrFail($id);
        if ($inventoryHead->inventorytype == 'APP') {
            DB::statement('EXEC sp_insertlocationandcardtoapp :inventoryid', ['inventoryid' => $inventoryHead->id,]);
        }
        if ($inventoryHead->inventorytype == 'DRON') {
            DB::statement('EXEC sp_insertlocationandcardtodron :inventoryid', ['inventoryid' => $inventoryHead->id,]);
        }
        return $this->redirectBackSuccessMessage('Cédulas creadas con exito!!');
    }

    public function printcardcount01($id)
    {
        $card = $this->getCardForPDF($id);
        $numero = '1';
        if ($card->inventorytype == 'APP') {
            $locations = $this->getCountCard01($card);
        }
        if ($card->inventorytype == 'DRON') {
            $locations = InventoryLocationForDron::where('inventoryid', $card->inventoryid)
                ->where('cardid', $card->cardid)->get();
        }
        if ($card->centerid == 'AVBSMX' || $card->centerid == 'BRIDGESTONES') {
            $fortmat = 'Counting.InventoryHead.Formats.BRIDGESTONE.closecard';
        } else {
            $fortmat = 'Counting.InventoryHead.Formats.closecard';
        }
        $pdf = PDF::loadView($fortmat, compact('card', 'locations', 'numero'));
        return $pdf->download('CEDULA DE CONTEO 01 -' . $card->cardid . '.pdf');
    }

    public function printcardcount02($id)
    {
        $card = $this->getCardForPDF($id);
        $numero = '2';
        if ($card->inventorytype == 'APP') {
            $locations = $this->getCountCard02($card);
        }
        if ($card->inventorytype == 'DRON') {
            $locations = InventoryLocationForDron::where('inventoryid', $card->inventoryid)
                ->where('cardid', $card->cardid)->get();
        }
        if ($card->centerid == 'AVBSMX' || $card->centerid == 'BRIDGESTONES') {
            $fortmat = 'Counting.InventoryHead.Formats.BRIDGESTONE.closecard';
        } else {
            $fortmat = 'Counting.InventoryHead.Formats.closecard';
        }
        $pdf = PDF::loadView($fortmat, compact('card', 'locations', 'numero'));
        return $pdf->download('CEDULA DE CONTEO 02 -' . $card->cardid . '.pdf');
    }

    public function printcardcount03($id)
    {
        $card = InventoryCard::findOrFail($id);
        $inventoryHead = InventoryHead::where('id', $card->inventoryid)->first();
        if ($inventoryHead->inventorytype == 'APP') {
            $locations = $this->getCountCard03($card);
        }
        if ($inventoryHead->inventorytype == 'DRON') {
            $locations = InventoryLocationForDron::where('inventoryid', $inventoryHead->id)
                ->where('cardid', $card->cardid)->where('error', '=', 'S')->get();
        }
        $fortmat = 'Counting.InventoryHead.Formats.count3';
        $pdf = PDF::loadView($fortmat, compact('card', 'locations'));
        return $pdf->download('CEDULA DE CONTEO 03 -' . $card->cardid . '.pdf');
    }

    public function getLocationWithError(Request $request)
    {
        $inventory = $request->get('inventoryid');
        $data = $this->getLocationErrors($inventory);
        return \DataTables::of($data)->toJson();
    }


}
