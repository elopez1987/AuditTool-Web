<?php

namespace App\Http\Controllers\Yard\Customers;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Models\Yard\Customer\OrderCustomer;
use Illuminate\Http\Request;

class ParameterCustomerController extends BaseController
{
    protected string $routePath = 'ordercustomers';

    protected string $viewPath = 'Yards.OrderCustomers';

    public function parameters($id)
    {
        $this->authorize('ordercustomers.parameters');
        $model = OrderCustomer::findOrFail($id);
        $parameters = SystemStatus::whereSchemaid('OCF')
            ->select(['statusid', 'name'])->get();

        return view(
            "{$this->viewPath}.Parameters.create",
            compact('model', 'parameters')
        );
    }

    public function addparameters(Request $request, $id)
    {
        $carrier = OrderCustomer::findOrFail($id);
        $carrier->configurationflag = $request['parameter'];
        $carrier->save();

        return $this->redirectBackSuccess();
    }
}
