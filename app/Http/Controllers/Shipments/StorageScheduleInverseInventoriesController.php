<?php

namespace App\Http\Controllers\Shipments;

use App\Http\Controllers\BaseController;
use App\Models\Shipments\StorageScheduleInverse;
use App\Models\Shipments\StorageScheduleInverseInventory;
use Carbon\Carbon;
use DB;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class StorageScheduleInverseInventoriesController extends BaseController
{
    public function updateDataInventory(StorageScheduleInverse $storagescheduleinverse)
    {
        $details = $this->getData($storagescheduleinverse);

        return view('Shipments.StorageScheduleInverse.inventory.edit', compact('storagescheduleinverse', 'details'));
    }

    public function getData(StorageScheduleInverse $storagescheduleinverse): Collection
    {
        return DB::table('storagescheduleinverse')
            ->select(
                'storageincidencejourneydetails.delivery',
                'storagescheduleinversetraffic.requestzare',
                'storagescheduleinversetraffic.zare',
                'storagescheduleinversetraffic.requestne',
                'storagescheduleinversetraffic.ne',
                'storagescheduleinverseinventory.requestadjust',
                'storagescheduleinverseinventory.replyadjust',
                'storagescheduleinverseinventory.note'
            )
            ->leftJoin('orderdeliveries', 'orderdeliveries.id', '=', 'storagescheduleinverse.orderid')
            ->leftJoin('storageincidencejourneydetails', 'orderdeliveries.id', '=', 'storageincidencejourneydetails.orderid')
            ->leftJoin('storagescheduleinversetraffic', function ($join) {
                $join->on('storagescheduleinverse.id', '=', 'storagescheduleinversetraffic.inverseid')
                    ->on('storagescheduleinverse.orderid', '=', 'storagescheduleinversetraffic.orderid')
                    ->on('storagescheduleinversetraffic.delivery', '=', 'storageincidencejourneydetails.delivery');
            })
            ->leftJoin('storagescheduleinverseinventory', function ($join) {
                $join->on('storagescheduleinverse.id', '=', 'storagescheduleinverseinventory.inverseid')
                    ->on('storagescheduleinverse.orderid', '=', 'storagescheduleinverseinventory.orderid')
                    ->on('storagescheduleinverseinventory.delivery', '=', 'storageincidencejourneydetails.delivery');
            })
            ->where('storagescheduleinversetraffic.adjust', '=', 'S')
            ->where('storagescheduleinverse.orderid', '=', $storagescheduleinverse->orderid)
            ->distinct()->get();
    }

    public function update(Request $request, $id)
    {
        $error = null;
        DB::beginTransaction();
        try {
            $inverse = StorageScheduleInverse::findOrFail($id);
            if ($request['delivery'] !== '') {
                $total = count($request['delivery']) - 1;
                for ($i = 0; $i <= $total; $i++) {
                    $inventory = StorageScheduleInverseInventory::updateOrCreate(
                        [
                            'centerid' => $inverse->centerid,
                            'warehouseid' => $inverse->warehouseid,
                            'orderid' => $inverse->orderid,
                            'inverseid' => $id,
                            'delivery' => $request['delivery'][$i],
                        ],
                        [
                            'replyadjust' => Carbon::now(),
                            'note' => $request['note'][$i],
                        ]
                    );
                    if ($inventory->replyadjust !== '') {
                        $inventory->statusid = 'TD';
                        $inventory->save();
                    }
                }
            }
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $error = $e->getMessage();
            DB::rollback();
        }
        if ($success) {
            return $this->redirectBackSuccessMessage('Datos agregados correctamente');
        }

        return $this->errorResponse('No se pudieron guardar los datos!!' . $error);
    }
}
