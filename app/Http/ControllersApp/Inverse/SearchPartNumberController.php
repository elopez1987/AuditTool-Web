<?php

namespace App\Http\ControllersApp\Inverse;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Inverse\SearchPartNumberRequest;
use App\Models\Inventory\PartNumber\InventoryPartnumber;
use App\Traits\ResponseApi;

class SearchPartNumberController extends Controller
{
    use ResponseApi;

    public function getSkuForInverse(SearchPartNumberRequest $request)
    {
        $partnumber = $request->input('partnumberid');
        $partnumberid = $this->searchPartNumber($partnumber);
        return $this->getResponseSuccess('getDataSku',
            [
                'partnumberid' => $partnumberid->prtnum,
                'description' => $partnumberid->lngdsc,
                'barcode' => $partnumberid->barcode
            ]);
    }

    /**
     * @param mixed $partnumber
     * @return InventoryPartnumber
     */
    public function searchPartNumber(mixed $partnumber): InventoryPartnumber
    {
        return InventoryPartnumber::controlAccessUser()
            ->where('prtnum', '=', $partnumber)
            ->orWhere('barcode', '=', $partnumber)
            ->first();
    }


}
