<?php

namespace App\Http\Controllers\Yard\StorageOperators;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Operator\CreateOperatorRequest;
use App\Models\Yard\StorageOperator;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class StorageOperatorsController extends BaseController
{
    public function index()
    {
        $this->authorize('storageoperators.index');

        return view('Yards.StorageOperators.index');
    }

    public function getDataOperators(Request $request)
    {

        $data = StorageOperator::controlAccessUser()
            ->with('status', 'center')
            ->Name($request->get('name'))
            ->Barcode($request->get('barcode'))
            ->select('storageoperators.*');

        return $this->showDataToView($data, 'Yards.StorageOperators.partials.buttons');
    }

    public function create()
    {
        $this->authorize('storageoperators.create');
        $centers = $this->getCenterForSelect();

        return view('Yards.StorageOperators.create', compact('centers'));
    }

    public function store(CreateOperatorRequest $request)
    {

        $operator = new StorageOperator($request->validated());
        $operator->save();

        return $this->successResponse('storageoperators.index');
    }

    public function edit(StorageOperator $storageoperator)
    {
        $this->authorize('storageoperators.edit');
        $status = $this->getStatusSTS();

        return view('Yards.StorageOperators.edit',
            compact('storageoperator', 'status'));
    }

    public function update(Request $request, StorageOperator $storageoperator): RedirectResponse
    {
        $storageoperator->update($request->except('centerid'));

        return $this->infoResponse('storageoperators.index');
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

    public function show(string $storageoperator) {}
}
