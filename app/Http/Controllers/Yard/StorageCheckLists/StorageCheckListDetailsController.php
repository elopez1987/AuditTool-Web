<?php

namespace App\Http\Controllers\Yard\StorageCheckLists;

use App\Http\Controllers\BaseController;
use App\Models\Yard\StorageCheckList;
use App\Models\Yard\StorageCheckListDetail;
use Illuminate\Http\Request;

class StorageCheckListDetailsController extends BaseController
{
    public function index(StorageCheckList $storagechecklist)
    {
        $details = StorageCheckListDetail::whereChecklistid($storagechecklist->id)->get();
        $status = $this->getStatusSTS();

        return view('Yards.StorageCheckListDetails.create', compact('storagechecklist', 'details', 'status'));
    }

    public function store(Request $request, StorageCheckList $storagechecklist)
    {
        StorageCheckListDetail::create([
            'checklistid' => $storagechecklist->id,
            'description' => $request['description'],
            'auxiliary01' => $request['auxiliary01'],
            'auxiliary02' => $request['auxiliary02'],
            'auxiliary03' => $request['auxiliary03'],
            'auxiliary04' => $request['auxiliary04'],
            'auxiliary05' => $request['auxiliary05'],
        ]);

        return $this->redirectBackSuccess();
    }

    public function update(Request $request, $id)
    {
        $detail = StorageCheckListDetail::findOrFail($id);
        $detail->statusid = $request['statusedit'];
        $detail->description = $request['descriptionedit'];
        $detail->auxiliary01 = $request['auxiliary01'];
        $detail->auxiliary02 = $request['auxiliary02'];
        $detail->auxiliary03 = $request['auxiliary03'];
        $detail->auxiliary04 = $request['auxiliary04'];
        $detail->auxiliary05 = $request['auxiliary05'];
        $detail->save();

        return $this->redirectBackSuccess();
    }

}
