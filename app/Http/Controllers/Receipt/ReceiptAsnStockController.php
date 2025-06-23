<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Receipt\ReceiptAsn\CreateReceiptStockRequest;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptStock;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;


class ReceiptAsnStockController extends BaseController
{
    public function addstock($id)
    {
        $receiptasn = ReceiptAsn::whereId($id)->first();
        $stocks = ReceiptStock::whereReceiptid($id)->get();
        return view('Receipt.ReceiptAsn.addStock',
            compact('receiptasn', 'stocks'));
    }

    public function addstockstore(CreateReceiptStockRequest $request, $id)
    {
        $error = null;
        DB::beginTransaction();
        try {
            $receipt = ReceiptAsn::findOrFail($id);
            $this->createReceiptStock($request, $receipt);
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $error = $e->getMessage();
            DB::rollback();
        }
        if ($success) {
            return $this->redirectBackSuccess();
        } else {
            return redirect::back()->withInput()->with(session()->flash('error', 'No se pudieron guardar los datos!!' . $error));
        }
    }

    public function createReceiptStock($request, $receipt)
    {
        $sku = InventoryPartnumber::wherePrtnum($request['prtnum'])->first();
        if(empty($sku)){
            return $this->errorResponse('El número de parte no existe, favor de validar.');
        }
        $dias = Carbon::parse($request['productiondate'])->addDays($sku->shelflife)->format('Y-m-d');
        ReceiptStock::create([
            'centerid' => $receipt->centerid,
            'warehouseid' => $receipt->warehouseid,
            'shipmentid' => $receipt->shipmentid,
            'receiptid' => $receipt->id,
            'partnumberid' => $sku->id,
            'category01id' => $sku->category01id,
            'category02id' => $sku->category02id,
            'stockcategoryid' => $receipt->stockcategoryid,
            'expirecode' => $request['batch'],
            'expiredate' => $dias,
            'productiondate' => $request['productiondate'],
            'quantitytrue' => $request['quantity']
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
