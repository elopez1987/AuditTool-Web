<?php

namespace App\Http\Controllers\Receipt;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Receipt\ReceiptProcess\CreateReceiptPalletsRequest;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Models\Receipt\ReceiptAsn;
use App\Models\Receipt\ReceiptContainer;
use App\Models\Receipt\StorageReceiptPallet;
use Illuminate\Http\Request;

class ReceiptContainersController extends BaseController
{
    //    Registro de tarimas blancas en recibo
    public function addpallets($id)
    {
        $receipt = ReceiptAsn::findOrFail($id);
        $materials = InventoryPartnumber::controlAccessUser()
            ->whereHas('category01', function ($q) {
                $q->where('description', 'TARIMA');
            })->select('lngdsc', 'id')->get();

        $data = StorageReceiptPallet::controlAccessUser()
            ->leftJoin('inventorypartnumbers', 'storagereceiptpallets.partnumberid', '=', 'inventorypartnumbers.id')
            ->where('storagereceiptpallets.receiptid', '=', $id)
            ->select([
                'inventorypartnumbers.lngdsc as description',
                'storagereceiptpallets.quantity',
                'storagereceiptpallets.damage',
                'storagereceiptpallets.id',
                'storagereceiptpallets.partnumberid',
            ])->get();
        return view('Receipt.ReceiptAsn.AddPallets.index', compact('receipt', 'materials', 'data'));
    }

    public function addpalletssave($id, CreateReceiptPalletsRequest $request)
    {
        if ($request['quantity'] < $request['damage']) {
            return $this->errorResponse('La cantidad de daños no puede ser mayor a la cantidad de piezas');
        }
        $receipt = ReceiptAsn::findOrFail($id);
        StorageReceiptPallet::create(
            [
                'centerid' => $receipt->centerid,
                'warehouseid' => $receipt->warehouseid,
                'receiptid' => $receipt->id,
                'partnumberid' => $request['partnumberid'],
                'quantity' => $request['quantity'],
                'damage' => $request['damage'],
            ]
        );
        return $this->redirectBackSuccessMessage('Datos guardados con exito!!');
    }

    public function updatepallets(Request $request, $id)
    {
        if ($request['quantitymod'] < $request['damagemod']) {
            return $this->errorResponse('La cantidad de daños no puede ser mayor a la cantidad de piezas');
        }
        $data = StorageReceiptPallet::controlAccessUser()->findOrFail($id);
        $data->partnumberid = $request['partnumberidmod'];
        $data->quantity = $request['quantitymod'];
        $data->damage = $request['damagemod'];
        $data->save();
        return $this->redirectBackSuccessMessage('Datos actualizados con exito!!');
    }

    public function deletepallets(Request $request, $id)
    {
        $data = StorageReceiptPallet::controlAccessUser()->findOrFail($id);
        $data->delete();
        return $this->redirectBackSuccessMessage('Datos eliminados con exito!!');
    }

    //    --------------------------------------------------------------------------------------------------------------------

    //    Borrar contenedores de recibo
    public function deleteLpnOfReceipt(Request $request)
    {
        $container = ReceiptContainer::where('recordid', '=', $request['id'])->first();
        $container->delete();
        return response()->json();
    }

    //    Actualizar cantidad del contenedor
    public function updateQuantityContainer(Request $request, $id)
    {
        $container = ReceiptContainer::controlAccessUser()->where('recordid', '=', $id)->first();
        $container->quantity = $request['quantity'];
        $container->save();
        return $this->redirectBackSuccessMessage('Datos actualizados con exito!!');
    }
}
