<?php

namespace App\Http\ControllersApp\Receipt\Incidence;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Receipt\Incidence\SubCategoryIncidenceRequest;
use App\Models\Receipt\StorageCategoryIncidence;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use App\Traits\ResponseApi;

class CategoryIncidenceController extends Controller
{
    use ResponseApi;

    public function getReceiptCategoryIncidence()
    {
        $data = StorageCategoryIncidence::controlAccessUser()
            ->where('auxiliary01', '=', 'REC')
            ->where('statusid', '=', 'E')
            ->select(['categoryincid', 'description'])->get();
        return $this->getResponseSuccess('getReceiptCategoryIncidence', $data);
    }

    public function getReceiptSubCategoryIncidence(SubCategoryIncidenceRequest $request)
    {
        $data = StorageCategoryIncidenceDetail::controlAccessUser()
            ->where('statusid', '=', 'E')
            ->where('auxiliary01', '=', 'REC')
            ->where('categoryincid', '=', $request['categoryincid'])
            ->select(['categoryincdetailid', 'description'])->get();
        return $this->getResponseSuccess('getReceiptSubCategoryIncidence', $data);
    }

}
