<?php

namespace App\Http\Controllers;

use App\Models\Configuration\NameSystem\NameSystem;
use App\Models\Shipments\OrderDeliverie;
use App\Models\Yard\ReceiptUnit;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class HomeController extends BaseController
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $image = NameSystem::first();
        return view('home', compact('image'));
    }

    public function logout()
    {
        Auth::logout();
        return Redirect::to('/login');
    }


    public function getDataStatusShipment()
    {
        $data = OrderDeliverie::controlAccessUser()
            ->leftJoin('shipmentcarriers', 'orderdeliveries.carrierid', '=', 'shipmentcarriers.id')
            ->leftJoin('ordercustomers', 'orderdeliveries.customerid', '=', 'ordercustomers.id')
            ->leftJoin('ordermonitor', 'orderdeliveries.id', '=', 'ordermonitor.orderid')
            ->leftJoin('systemstatus', 'orderdeliveries.statusid', '=', 'systemstatus.statusid')
            ->select(['ordercustomers.name as customername', 'shipmentcarriers.name as carriername',
                'orderdeliveries.shipmentid', 'ordercustomers.city', 'systemstatus.name as status'])
            ->whereNotIn('orderdeliveries.statusid', ['DH', 'CA'])->get();
        $data = json_encode($data);
        return response()->json($data);
    }





}
