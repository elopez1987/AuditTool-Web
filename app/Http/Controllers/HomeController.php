<?php

namespace App\Http\Controllers;

use App\Models\Configuration\NameSystem\NameSystem;
use App\Models\Shipments\OrderDeliverie;
use Illuminate\Http\Request;
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

    public function logout(Request $request)
    {
//        Auth::logout();
//        return Redirect::to('/');

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');


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
