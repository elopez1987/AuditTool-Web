<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Receipt\ReceiptAsn\CreateReceiptStockRequest;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptStock;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;

class ReceiptAsnStockController extends BaseController
{
    public function addstock($id)
    {
        $receiptasn = ReceiptAsn::controlAccessUser()->whereId($id)->first();
        $stocks = ReceiptStock::whereReceiptid($id)->get();

        return view(
            'Receipt.ReceiptAsn.addStock',
            compact('receiptasn', 'stocks'),
        );
    }

    public function addstockstore(CreateReceiptStockRequest $request, $id)
    {
        $error = null;
        DB::beginTransaction();
        try {
            $receipt = ReceiptAsn::controlAccessUser()->findOrFail($id);

            $sku = InventoryPartnumber::controlAccessUser()->wherePrtnum($request['prtnum'])->first();
            if (empty($sku)) {
                return $this->errorResponse('El número de parte no existe, favor de validar.');

            }
            $totalProduct = $request['rotary'] + $request['definitive'];
            if ($totalProduct > $request['quantity']) {
                return $this->errorResponse('La cantidad de producto a devolver no puede se mayor a la cantidad.');

            }

            $this->createReceiptStock($request, $receipt, $sku);
            DB::commit();

            return $this->redirectBackSuccessMessage('Stock agregado con exito.');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollback();

            return $this->errorResponse('No se pudo agregar el stock, favor de validar.'.$error);
        }
    }

    private function createReceiptStock($request, $receipt, $sku)
    {
        $expiredate = Carbon::parse($request['productiondate'])->addDays((int) $sku->shelflife)->format('Y-m-d');
        ReceiptStock::create([
            'receiptid' => $receipt->id,
            'partnumberid' => $sku->id,
            'expirecode' => $request['batch'],
            'expiredate' => $expiredate,
            'productiondate' => $request['productiondate'],
            'quantity' => $request['quantity'],
            'rotary' => $request['rotary'],
            'definitive' => $request['definitive'],
        ]);
    }

    public function updateQuantityStock($id, Request $request)
    {
        $stock = ReceiptStock::findOrFail($id);
        $stock->quantity = $request['quantity'];
        $stock->expirecode = $request['expirecode'];
        $stock->save();

        return $this->redirectBackSuccess();
    }

    public function deletestock($id)
    {
        $stock = ReceiptStock::findOrFail($id);
        $stock->delete();

        return $this->redirectBackSuccess();
    }
}
