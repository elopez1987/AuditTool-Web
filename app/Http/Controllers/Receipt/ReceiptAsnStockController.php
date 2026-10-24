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
            $this->createReceiptStock($request, $receipt);
            DB::commit();

            return $this->redirectBackSuccessMessage('Stock agregado con exito.');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            DB::rollback();

            return $this->errorResponse('No se pudo agregar el stock, favor de validar.' . $error);
        }
    }

    private function createReceiptStock($request, $receipt): void
    {
        $sku = InventoryPartnumber::controlAccessUser()->wherePrtnum($request['prtnum'])->first();
        if (empty($sku)) {
            $this->errorResponse('El número de parte no existe, favor de validar.');

            return;
        }
        $expiredate = Carbon::parse($request['productiondate'])->addDays((int) $sku->shelflife)->format('Y-m-d');
        ReceiptStock::create([
            'receiptid' => $receipt->id,
            'partnumberid' => $sku->id,
            'expirecode' => $request['batch'],
            'expiredate' => $expiredate,
            'productiondate' => $request['productiondate'],
            'quantitytrue' => $request['quantity'],
        ]);
    }

    public function updateQuantityStock($id, Request $request)
    {
        $stock = ReceiptStock::findOrFail($id);
        $stock->quantitytrue = $request['quantitytrue'];
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
