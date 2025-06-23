<?php

namespace App\Http\ControllersApp\Inventory\Dron\InventoryFiscalDron;

use App\Http\Controllers\BaseController;
use App\Models\Inventory\FiscalInventory\Cards\InventoryCard;
use App\Models\Inventory\FiscalInventory\Dron\InventoryLocationForDron;
use App\Models\Inventory\FiscalInventory\Dron\LocactionDronEvidence;
use App\Models\Inventory\FiscalInventory\Head\InventoryHead;
use App\Models\Inventory\FiscalInventory\Locations\InventoryLocationLog;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Traits\EvidencePhotoTrait;
use App\Traits\ResponseApi;
use App\User;
use Auth;
use Carbon\Carbon;
use Illuminate\Http\Request;


class InventoryCountDronController extends BaseController
{
    use ResponseApi;
    use EvidencePhotoTrait;

    public function saveCountForFiscal(Request $request)
    {
        $user = User::where('username', Auth::user()->username)->select(['name', 'id', 'username'])->first();
        $centerid = SecurityAccessWarehouse::where('username', $user->username)->value('centerid');
        $inventoryHead = InventoryHead::where('centerid', $centerid)->where('inventorytype', '=', 'DRON')->where('statusid', '=', 'AT')->first();
        if (empty($inventoryHead)) {
            return $this->getResponseWithErrors('saveCountForFiscal', 'No existe inventario activo, favor de validar.');
        }
        $location = InventoryLocationForDron::where('inventoryid', $inventoryHead->id)
            ->where('locationid', $request['locationid'])->first();
        if (empty($location)) {
            return $this->getResponseWithErrors('saveCountForFiscal', 'La ubicación no se encuentra programada para conteo.');
        }
        $card = InventoryCard::where('cardid', $location->cardid)->where('inventoryid', $inventoryHead->id)->first();
        if (empty($card->startdate01)) {
            $card->startdate01 = Carbon::now();
            $card->responsible01 = $request['piloto'];
            $card->save();
        }
        if ($location->statusid == 'C') {
            $this->saveLogError($inventoryHead, $card, $location);
            return $this->getResponseLocationCount('saveCountForFiscal', 'La ubicación ya se encuentra contada.');
        }
        $this->saveDataToLocation($request, $location);
        $this->savePhotosEvidences($request, $inventoryHead, $location);
        return $this->getResponseWithSuccess('saveCountForFiscal', 'Ubicación registrada con exito.');
    }


    public function saveCountForDronLaser(Request $request)
    {
        $user = User::where('username', Auth::user()->username)->select(['name', 'id', 'username'])->first();
        $centerid = SecurityAccessWarehouse::where('username', $user->username)->value('centerid');
        $inventoryHead = InventoryHead::where('centerid', $centerid)->where('inventorytype', '=', 'DRON')->where('statusid', '=', 'AT')->first();
        if (empty($inventoryHead)) {
            return $this->getResponseWithErrors('saveCountForDronLaser', 'No existe inventario activo, favor de validar.');
        }
        $location = InventoryLocationForDron::where('inventoryid', $inventoryHead->id)
            ->where('locationid', $request['locationid'])->first();
        if (empty($location)) {
            return $this->getResponseWithErrors('saveCountForDronLaser', 'La ubicación no se encuentra programada para conteo.');
        }
        if ($location->statusid == 'C') {
            $this->saveLogError($inventoryHead, $card, $location);
            return $this->getResponseLocationCount('saveCountForDronLaser', 'La ubicación ya se encuentra contada.');
        }
        $location->quantity = $request['quantity'];
        $location->save();
        $this->savePhotosEvidences($request, $inventoryHead, $location);
        return $this->getResponseWithSuccess('saveCountForDronLaser', 'Ubicación registrada con exito.');
    }


    public function saveLogError($inventoryHead, $card, $location)
    {
        InventoryLocationLog::create([
            'inventoryid' => $inventoryHead->id,
            'cardid' => $card->cardid,
            'locationid' => $location->locationid,
            'description' => 'La ubicación ya se encuenta contada',
        ]);
    }

    /**
     * @param Request $request
     * @param $location
     * @return void
     */
    public function saveDataToLocation(Request $request, $location): void
    {
        $location->partnumberid = str_starts_with($request['partnumberid'], '000') ? substr($request['partnumberid'], 3) : $request['partnumberid'];
        $location->quantity = $request['quantity'];
        $location->usercount = $request['piloto'];
        $location->countdate = $request['timestamp'];
        $location->statusid = 'C';
        $location->save();
    }

    /**
     * @param Request $request
     * @param $inventoryHead
     * @param $location
     * @return void
     */
    public function savePhotosEvidences(Request $request, $inventoryHead, $location): void
    {
        foreach ($request['evidencias'] as $evidencia) {
            $file = $evidencia['foto'];
            $path = $this->saveFileToModelBase64($file, 'InventoryHead', $inventoryHead, 'EvidenceFiscal', 'IVF', 'Table');
            LocactionDronEvidence::create([
                'inventoryid' => $inventoryHead->id,
                'cardid' => $location->cardid,
                'locationid' => $location->recordid,
                'path' => $path,
                'createuser' => $request['piloto']
            ]);
        }
    }

}
