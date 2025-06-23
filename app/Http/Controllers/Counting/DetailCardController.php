<?php

namespace App\Http\Controllers\Counting;

use App\Http\Controllers\BaseController;
use App\Http\Services\Counting\ServiceInventoryLocations;
use App\Models\Inventory\FiscalInventory\Cards\InventoryCard;
use App\Models\Inventory\FiscalInventory\Head\InventoryHead;
use App\Models\Inventory\FiscalInventory\Locations\InventoryLocationLog;
use App\Models\Inventory\FiscalInventory\Locations\MonitorLocationInventory;
use App\Traits\Counting\QueryInventoryHead;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class DetailCardController extends BaseController
{
    use QueryInventoryHead;

    private ServiceInventoryLocations $serviceInventoryLocation;

    public function __construct(ServiceInventoryLocations $serviceInventoryLocations)
    {
        $this->serviceInventoryLocation = $serviceInventoryLocations;
    }

    public function detailcardscount($id)
    {
        $inventory = InventoryHead::findOrFail($id);
        return view('Counting.InventoryHead.Details.detailcards', compact('inventory'));
    }

    public function getDataCardsInventory(Request $request)
    {
        $data = $this->getCardInventory($request);
        return DataTables::of($data)
            ->addColumn('btn', 'Counting.InventoryHead.Details.btndetailcard')
            ->rawColumns(['btn'])
            ->toJson();
    }

    public function detailcard($id)
    {
        $card = InventoryCard::controlAccessUser()->where('recordid', '=', $id)->first();
        $inventory = InventoryHead::where('id', '=', $card->inventoryid)->first();
        if ($inventory->inventorytype == 'APP') {
            $locations = MonitorLocationInventory::where('cardid', '=', $card->cardid)
                ->where('id', '=', $inventory->id)
                ->orderBy('locationid')->get();
            return view('Counting.InventoryHead.Card.card', compact('inventory', 'card', 'locations'));
        }
        if ($inventory->inventorytype == 'DRON') {
            $locations = $this->getLocationsForCountDron($inventory, $card);
            $totalForStatus = $this->getTotalForStatusCountDron($inventory, $card);
            return view('Counting.InventoryHeadDron.Card.card',
                compact('inventory', 'card', 'locations', 'totalForStatus'));
        }
    }


//    Detalle de sku
    public function detailskucount($id)
    {
        $inventory = InventoryHead::findOrFail($id);
        return view('Counting.InventoryHead.Details.detailskus', compact('inventory'));
    }

    public function getInventorySku(Request $request)
    {
        $data = $this->getStockForInventory($request);
        return DataTables::of($data)
            ->editColumn('totalstock', 'Counting.InventoryHead.Details.totalstock')
            ->editColumn('tconteo01', 'Counting.InventoryHead.Details.tconteo01')
            ->editColumn('tconteo02', 'Counting.InventoryHead.Details.tconteo02')
            ->rawColumns(['totalstock', 'tconteo01', 'tconteo02'])
            ->toJson();
    }


//    Detalle de ubicaciones
    public function detaillocationscount($id)
    {
        $inventory = InventoryHead::findOrFail($id);
        return view('Counting.InventoryHead.Details.detaillocations', compact('inventory'));
    }

    public function addlocationinventory(Request $request)
    {
        $head = InventoryHead::where('id', $request['inventoryid'])->first();
        $location = $this->serviceInventoryLocation->createNewLocation($head, $request);
        return response()->json($location);
    }

    public function getLocationInventory(Request $request)
    {
        $data = $this->getLocationNumberCount($request);
        return DataTables::of($data)->toJson();
    }


    public function updateCardFree($id, Request $request)
    {
        $this->serviceInventoryLocation->clearCountInventory($id, $request);
        return $this->redirectBackSuccessMessage('Cédula liberada con exito!');
    }

    public function detaillogcard($id)
    {
        $card = InventoryCard::controlAccessUser()->where('recordid', '=', $id)->first();
        $errors = InventoryLocationLog::where('cardid',$card->cardid)->get();
        return view('Counting.InventoryHead.Log.index', compact('card', 'errors'));
    }

}
