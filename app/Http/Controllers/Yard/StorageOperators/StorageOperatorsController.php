<?php

namespace App\Http\Controllers\Yard\StorageOperators;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Operator\CreateOperatorRequest;
use App\Models\Yard\StorageOperator;
use App\Traits\Web\General\GenerateLabel;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StorageOperatorsController extends BaseController
{
    use GenerateLabel;

    public function index()
    {
        $this->authorize('storageoperators.index');
        return view("Yards.StorageOperators.index");
    }

    public function getDataOperators(Request $request)
    {
        $carrier = $this->getCarrierForUser();
        $data = StorageOperator::controlAccessUser()
            ->with('status','center','carrier')
            ->whereNotNull('storageoperators.carrierid')
            ->ForCarrierOperator($carrier)
            ->Name($request->get('name'))
            ->Barcode($request->get('barcode'))
            ->select('storageoperators.*');
        return $this->showDataToView($data, "Yards.StorageOperators.partials.buttons");
    }

    public function create()
    {
        $this->authorize('storageoperators.create');
        $centers = $this->getCenterForSelect();
        $carriers = $this->getCarriersForSelect();
        return view("Yards.StorageOperators.create",
            compact('centers', 'carriers'));
    }

    public function store(CreateOperatorRequest $request)
    {
        $barcode = $this->getFolioYMS($request['centerid']);
        $operator = new StorageOperator($request->validated() + ['barcode' => $barcode->folionumber]);
        $operator->save();
        return $this->successResponse("storageoperators.index");
    }

    public function edit(StorageOperator $storageoperator)
    {
        $this->authorize('storageoperators.edit');
        $carriers = $this->getCarriersForSelect();
        $status = $this->getStatusSTS();
        return view("Yards.StorageOperators.edit", compact('storageoperator', 'carriers','status'));
    }

    public function update(Request $request, StorageOperator $storageoperator): RedirectResponse
    {
        $storageoperator->update($request->except('centerid'));
        return $this->infoResponse("storageoperators.index");
    }

    public function destroy(StorageOperator $storageoperator)
    {
        $this->authorize('storageoperators.destroy');
        $storageoperator->delete();
        return $this->redirectBackSuccess();
    }

    public function printlabeloperator(StorageOperator $storageoperator)
    {
        $this->authorize('storageoperators.printcard');
        return $this->generateLabelOperator($storageoperator);
    }


}
