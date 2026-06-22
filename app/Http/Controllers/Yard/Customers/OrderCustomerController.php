<?php

namespace App\Http\Controllers\Yard\Customers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Yard\Customer\CreateCustomerRequest;
use App\Models\Yard\Customer\OrderCustomer;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class OrderCustomerController extends BaseController
{
    protected string $routePath = 'ordercustomers';

    protected string $viewPath = 'Yards.OrderCustomers';

    public function index()
    {
        $this->authorize('ordercustomers.index');
        $warehouses = $this->getWarehouseForSelect();

        return view('Yards.OrderCustomers.index', compact('warehouses'));
    }

    public function getDataCustomers(Request $request)
    {
        $data = OrderCustomer::controlAccessUser()
            ->with(['center:centerid,description', 'warehouse:warehouseid,name', 'status:statusid,name'])
            ->Name($request->input('name'))
            ->Shipto($request->input('shiptoid'))
            ->Warehouse($request->input('warehouseid'))
            ->select([
                'ordercustomers.id',
                'ordercustomers.centerid',
                'ordercustomers.warehouseid',
                'ordercustomers.name',
                'ordercustomers.shiptoid',
                'ordercustomers.city',
                'ordercustomers.timetransfer',
                'ordercustomers.statusid',
            ]);

        return $this->showDataToView($data, "{$this->viewPath}.partials.buttons");
    }

    public function create()
    {
        $this->authorize('ordercustomers.create');
        $centers = $this->getCenterForSelect();
        $warehouses = $this->getWarehouseForSelect();

        return view(
            "{$this->viewPath}.create",
            compact('centers', 'warehouses'),
        );
    }

    public function store(CreateCustomerRequest $request): RedirectResponse
    {
        $customerid = OrderCustomer::whereCenterid($request['centerid'])
            ->whereWarehouseid($request['warehouseid'])
            ->whereShiptoid($request['shiptoid'])->first();
        if (! empty($customerid)) {
            return $this->errorResponse('El cliente ya existe en el catalogo');
        }
        $customer = new OrderCustomer($request->all());
        $customer->save();

        return $this->successResponse("{$this->routePath}.index");
    }

    public function edit(OrderCustomer $ordercustomer)
    {
        $this->authorize('ordercustomers.edit');
        $status = $this->getStatusSTS();

        return view(
            "{$this->viewPath}.edit",
            compact('ordercustomer', 'status'),
        );
    }

    public function update(OrderCustomer $ordercustomer, Request $request): RedirectResponse
    {
        $ordercustomer->update($request->all());

        return $this->infoResponse("{$this->routePath}.index");
    }

    public function destroy(Request $request)
    {
        $this->authorize('ordercustomers.destroy');
        $ordercustomer = OrderCustomer::controlAccessUser()->find($request->id);
        $ordercustomer->delete();

        return $this->responseSuccessAjax('', now());
    }
}
