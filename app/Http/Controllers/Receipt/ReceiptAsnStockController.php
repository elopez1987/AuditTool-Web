<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Receipt\ReceiptAsn\CreateReceiptStockRequest;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptStock;
use DB;
use Exception;
use Illuminate\Http\Request;
use Log;

class ReceiptAsnStockController extends BaseController
{
    public function addstock($id)
    {
        $receiptasn = ReceiptAsn::controlAccessUser()->where('uuid_asn', $id)->first();
        if (empty($receiptasn)) {
            return $this->errorResponse('No existe el número de recibo solicitado.');
        }
        $stocks = ReceiptStock::whereReceiptid($receiptasn->id)->get();

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
            $receipt = ReceiptAsn::controlAccessUser()->where('id', $id)->first();
            if (empty($receipt)) {
                return $this->errorResponse('No existe número de recibo solicitado.');
            }

            $sku = InventoryPartnumber::controlAccessUser()->wherePrtnum($request['prtnum'])->first();
            if (empty($sku)) {
                return $this->errorResponse('El número de parte no existe, favor de validar.');
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
        ReceiptStock::create([
            'receiptid' => $receipt->id,
            'partnumberid' => $sku->id,
            'quantity' => $request['rotary'] + $request['definitive'],
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
