<?php

namespace App\Http\Services\Shipment\IncidecesJourney;

use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Models\Shipments\StorageIncidenceJourney;
use App\Models\Shipments\StorageIncidenceJourneyDetails;
use App\Models\Shipments\StorageKeyIncidenceOperation;
use App\Traits\Web\General\GetParameters;
use Auth;
use DB;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class ServiceIncidenceJourneyDetail
{
    use GetParameters;

    public function updateDetailIncidence($request)
    {
        $categories = $this->getCategoryIncidences();
        $datos = $request['comment'];
        $registros = count($datos) - 1;
        for ($i = 0; $i <= $registros; $i++) {
            if ($request['comment'][$i] !== '') {
                $detail = $this->UpdateIncidenceDetail($request, $i);
                $parameter = $this->getParametersOfWarehouse($detail->warehouseid);
                if (in_array('SWH-35', $parameter)) {
                    $this->createNewRegisterForKeyOperation($detail, $categories);
                }
            }
        }
    }

    public static function getCategoryIncidences(): array
    {
        return StorageCategoryIncidenceDetail::controlAccessUser()
            ->where('auxiliary04', '=', 'F')
            ->where('auxiliary01', '=', 'SHP')
            ->pluck('categoryincdetailid')->toArray();
    }

    /**
     * @return StorageIncidenceJourneyDetails|Builder|Model|object|null
     */
    public static function UpdateIncidenceDetail($request, int $i)
    {
        $detail = StorageIncidenceJourneyDetails::where('folioid', $request['folioiddetail'][$i])->first();
        $detail->statusiddetail = $request['statusiddetail'][$i];
        $detail->auxiliary01 = 'StorageCategoryIncidenceDetails';
        $detail->auxiliary02 = $request['responsible'][$i];
        $detail->auxiliary04 = Auth::user()->username;
        $detail->comment = $request['comment'][$i];
        $detail->save();

        return $detail;
    }

    public static function createNewRegisterForKeyOperation($detail, array $categories): void
    {
        if (in_array($detail->razoncode, $categories)) {
            StorageKeyIncidenceOperation::updateOrCreate([
                'centerid' => $detail->centerid,
                'warehouseid' => $detail->warehouseid,
                'orderid' => $detail->orderid,
                'delivery' => $detail->delivery,
                'partnumberid' => $detail->partnumberid,
            ]);
            $incidence = StorageIncidenceJourney::where('orderid', $detail->orderid)->first();
            $incidence->statuskeyid = 'RF';
            $incidence->save();
        }
    }

    public static function updateDetailForFolio($request, $folioid)
    {
        $folio = StorageIncidenceJourneyDetails::findOrFail($folioid);
        $folio->razoncode = $request['razoncode'];
        $folio->partnumberid = $request['partnumberid'];
        $folio->quantity = $request['quantity'];
        $folio->save();
    }

    public function getDetailResponseIncidence($orderid)
    {
        return DB::table('storageincidencejourneydetails')
            ->select(
                'storagesincidencekeyoperation.id',
                'orderdeliveries.shipmentid',
                'storageincidencejourneydetails.delivery',
                'storageincidencejourneydetails.partnumberid',
                'inventorypartnumbers.lngdsc as description',
                'storageincidencejourneydetails.quantity',
                'storageincidencejourneydetails.unitmeasure',
                'storagecategoryincidencedetails.description as concepto',
                'storagecategoryincidencedetails.auxiliary02 as code',
                'storageincidencejourneydetails.comment',
                'systemstatus.name as status',
                's2.description as responsable',
                'sc.description as keyresponse',
                'users.name as createuser',
                'storagesincidencekeyoperation.keyoperationdate as responsedate',
                'storageincidencekeytransport.razoncode as codetr',
                'storageincidencekeytransport.keytransportuser',
                'storageincidencekeytransport.keytransportdate',
                'u.name as keyusertr',
                'storageincidencejourneydetails.folioid'
            )
            ->leftJoin('orderdeliveries', function ($join) {
                $join->on('storageincidencejourneydetails.orderid', '=', 'orderdeliveries.id')
                    ->on('storageincidencejourneydetails.centerid', '=', 'orderdeliveries.centerid');
            })
            ->leftJoin('inventorypartnumbers', function ($join) {
                $join->on('storageincidencejourneydetails.partnumberid', '=', 'inventorypartnumbers.prtnum')
                    ->on('storageincidencejourneydetails.centerid', '=', 'inventorypartnumbers.centerid');
            })
            ->leftJoin('storagecategoryincidencedetails', 'storageincidencejourneydetails.razoncode', '=', 'storagecategoryincidencedetails.categoryincdetailid')
            ->leftJoin('systemstatus', function ($join) {
                $join->on('storageincidencejourneydetails.statusiddetail', '=', 'systemstatus.statusid')
                    ->on('storageincidencejourneydetails.schemaid', '=', 'systemstatus.schemaid');
            })
            ->leftJoin('storagecategoryincidencedetails as s2', 'storageincidencejourneydetails.auxiliary02', '=', 's2.categoryincdetailid')
            ->leftJoin('storagesincidencekeyoperation', function ($join) {
                $join->on('storageincidencejourneydetails.orderid', '=', 'storagesincidencekeyoperation.orderid')
                    ->on('storageincidencejourneydetails.delivery', '=', 'storagesincidencekeyoperation.delivery')
                    ->on('storageincidencejourneydetails.partnumberid', '=', 'storagesincidencekeyoperation.partnumberid');
            })
            ->leftJoin('storageincidencekeytransport', function ($join) {
                $join->on('storageincidencejourneydetails.orderid', '=', 'storageincidencekeytransport.orderid')
                    ->on('storageincidencejourneydetails.delivery', '=', 'storageincidencekeytransport.delivery')
                    ->on('storageincidencejourneydetails.partnumberid', '=', 'storageincidencekeytransport.partnumberid');
            })
            ->leftJoin(
                'storagecategoryincidencedetails as sc',
                'storagesincidencekeyoperation.razoncode',
                '=',
                'sc.categoryincdetailid'
            )
            ->leftJoin('users', 'storagesincidencekeyoperation.keyoperationuser', '=', 'users.username')
            ->leftJoin('users as u', 'storageincidencekeytransport.keytransportuser', '=', 'u.username')
            ->where('storageincidencejourneydetails.orderid', '=', $orderid)
            ->get();
    }
}
