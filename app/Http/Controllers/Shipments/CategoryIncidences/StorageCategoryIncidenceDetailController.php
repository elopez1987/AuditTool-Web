<?php

namespace App\Http\Controllers\Shipments\CategoryIncidences;

use App\Http\Controllers\BaseController;
use App\Models\Receipt\StorageCategoryIncidence;
use App\Models\Receipt\StorageCategoryIncidenceDetail;
use Illuminate\Http\Request;

class StorageCategoryIncidenceDetailController extends BaseController
{
    public function adddetailcategory($id)
    {
        $category = StorageCategoryIncidence::findOrFail($id);
        $details = StorageCategoryIncidenceDetail::where('categoryincid', $id)->get();
        $status = $this->getStatusSTS();
        return view('Shipments.CategoryIncidences.adddetail', compact('category', 'details', 'status'));
    }

    public function storedetailcategoryincidence(Request $request, $id)
    {
        $category = StorageCategoryIncidence::findOrFail($id);
        StorageCategoryIncidenceDetail::create([
            'centerid' => $category->centerid,
            'categoryincid' => $id,
            'description' => $request['descriptiondetail'],
            'auxiliary01' => $request['auxiliary01'],
            'auxiliary02' => $request['auxiliary02'],
            'auxiliary03' => $request['auxiliary03'],
            'auxiliary04' => $request['auxiliary04'],
            'auxiliary05' => $request['auxiliary05'],
        ]);
        return $this->redirectBackSuccess();
    }

    public function updatedetail($id, Request $request)
    {
        $detail = StorageCategoryIncidenceDetail::findOrFail($id);
        $detail->description = $request['description'];
        $detail->auxiliary01 = $request['auxiliary01'];
        $detail->auxiliary02 = $request['auxiliary02'];
        $detail->auxiliary03 = $request['auxiliary03'];
        $detail->auxiliary04 = $request['auxiliary04'];
        $detail->auxiliary05 = $request['auxiliary05'];
        $detail->statusid = $request['statusid'];
        $detail->save();
        return $this->redirectBackSuccess();
    }
}
