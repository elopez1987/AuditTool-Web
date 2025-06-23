<?php

namespace App\Http\ControllersApp\Yard\Customers;

use App\Http\Controllers\Controller;
use App\Models\Yard\Customer\OrderCustomer;
use App\Traits\ResponseApi;

class CustomersController extends Controller
{
    use ResponseApi;

    public function getCustomerForCenter()
    {
        $customers = OrderCustomer::controlAccessUser()
            ->where('statusid', '=', 'E')
            ->select(['centerid', 'id as customerid', 'name'])->get();
        return $this->getResponseSuccess('getCustomerForCenter', $customers);
    }
    public function getCustomerForGarrett()
    {
        $customers = OrderCustomer::controlAccessUser()
            ->select(['centerid', 'warehouseid', 'id', 'name', 'customerid', 'shiptoid'])
            ->where('statusid','=','E')
            ->whereNotNull('configurationflag')
            ->whereRaw("charindex('OCF-03', configurationflag) > 0")->get();
        return $this->getResponseSuccess('getCustomerForGarrett', $customers);
    }
}
