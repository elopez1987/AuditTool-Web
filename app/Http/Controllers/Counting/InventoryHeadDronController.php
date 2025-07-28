<?php

namespace App\Http\Controllers\Counting;

use App\Http\Controllers\Controller;
use App\Models\Inventory\FiscalInventory\Cards\InventoryCard;
use App\Models\Inventory\FiscalInventory\Dron\InventoryLocationForDron;
use App\Models\Inventory\FiscalInventory\Dron\LocactionDronEvidence;
use App\Models\Inventory\FiscalInventory\Head\InventoryHead;
use App\Models\Inventory\FiscalInventory\Stock\InventoryStock;
use App\Traits\Counting\QueryInventoryHead;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;


class InventoryHeadDronController extends Controller
{
    use QueryInventoryHead;

    public function index($id)
    {
        $inventoryhead = InventoryHead::findOrFail($id);
        $totales = $this->getTotales($inventoryhead);
        $totalLocations = InventoryLocationForDron::where('inventoryid', $id)->count();
        $totalCount = InventoryLocationForDron::where('inventoryid', $id)->where('statusid', 'C')->count();
        $totalContados = $this->getTotalCountLocations($id);
        $errorLocations = $this->getLocationsWithErrors($id);
        $totalPasillos = InventoryLocationForDron::where('inventoryid', $id)->distinct('aisleid')->count('aisleid');
        $totalErrors = InventoryLocationForDron::controlAccessUser()->where('inventoryid',$id)->where('error','=','S')->count();
        return view('Counting.InventoryHeadDron.index',
            compact('inventoryhead', 'totales', 'totalLocations', 'totalCount',
                'totalContados', 'errorLocations', 'totalPasillos','totalErrors'));
    }

    public function detaillocationdronfiscal($id)
    {
        $location = InventoryLocationForDron::findOrFail($id);
        $card = InventoryCard::where('cardid', $location->cardid)->where('inventoryid', $location->inventoryid)->first();
        $evidences = LocactionDronEvidence::where('locationid', $location->recordid)->get();
        $stock = InventoryStock::controlAccessUser()->where('inventoryid', $location->inventoryid)
            ->where('locationid',$location->locationid)->first();
        return view('Counting.InventoryHeadDron.Card.detaillocation',
            compact('location', 'card', 'evidences','stock'));
    }

    public function saveResponseSuccessLocation(Request $request)
    {
        $location = InventoryLocationForDron::where('recordid', $request['locationid'])->first();
        $location->error = 'N';
        $location->usercheck = \Auth::user()->username;
        $location->validatedate = Carbon::now();
        $location->save();
        return response()->json('ok');
    }

    public function saveResponseErrorLocation(Request $request)
    {
        $location = InventoryLocationForDron::where('recordid', $request['locationid'])->first();
        $location->error = 'S';
        $location->usercheck = \Auth::user()->username;
        $location->validatedate = Carbon::now();
        $location->save();
        return response()->json('ok');
    }

    public function closeCardForInventoryDron(Request $request)
    {
       $card = InventoryCard::where('inventoryid',$request['inventoryid'])
           ->where('cardid',$request['cardid'])->first();
       $card->finishdate02 = Carbon::now();
       $card->responsible02 = \Auth::user()->username;
       $card->save();
        return response()->json('ok');
    }

    /**
     * @param $id
     * @return array
     */
    public function getTotalCountLocations($id): array
    {
        return DB::select(" select aisleid,count(distinct cardid) as totalcedulas,count(locationid) as totallocations,
       (select count(ii.recordid) from inventorylocationsdron ii
                                    where  ii.statusid='C' and ii.inventoryid=$id and ii.aisleid = i.aisleid)  as contados
       from inventorylocationsdron i
                                                                               where inventoryid=$id
                group by aisleid
        ");
    }

    /**
     * @param $id
     * @return array
     */
    public function getLocationsWithErrors($id): array
    {
        return DB::select(" select i.areaid,i.aisleid,i.cardid,i.locationid,i.partnumberid,iv.lngdsc as description,i.quantity
                    from inventorylocationsdron i
                    left join inventorypartnumbers iv on i.partnumberid = iv.prtnum and i.centerid = iv.centerid
                    where i.inventoryid=$id
                    and i.error='S'
        ");
    }


}
