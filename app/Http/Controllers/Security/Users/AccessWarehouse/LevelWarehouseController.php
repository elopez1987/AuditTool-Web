<?php

namespace App\Http\Controllers\Security\Users\AccessWarehouse;

use App\Http\Controllers\Controller;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Security\Users\SuperUser;
use App\User;
use DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LevelWarehouseController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    public function createaccess($uuiduser)
    {
        $user = User::where('uuiduser', $uuiduser)->first();
        $warehouses = SystemWarehouse::select('warehouseid', 'name', 'centerid')->where('statusid', 'E')->get();
        $access = $this->getAccess($user->id);
        $users = SuperUser::select('userid')->get()->toArray();
        $filteredArray = array_filter($users, function ($item) {
            return in_array(Auth::user()->id, $item);
        });
        if (! empty($filteredArray)) {
            $centers = SystemCenterDistribution::get(['centerid', 'name']);
        } else {
            $centers = SystemCenterDistribution::controlAccessUser()->get(['centerid', 'name']);
        }
        return view('Security.Users.LevelWarehouse.create', compact('user', 'warehouses', 'access', 'centers'));
    }

    public function storeLevelWarehouse(Request $request, $uuiduser): RedirectResponse
    {
        $user = User::where('uuiduser', $uuiduser)->first();
        DB::table('securityaccesswarehouses')
            ->where('username', '=', $user->username)->delete();
        $requestInput = $request->all();
        $warehouses = $requestInput['warehouse'];
        foreach ($warehouses as $warehouse) {
            $center = SystemWarehouse::where('warehouseid', $warehouse)->select('centerid')->first();
            $securityAccess = new SecurityAccessWarehouse();
            $securityAccess->username = $user->username;
            $securityAccess->warehouseid = $warehouse;
            $securityAccess->centerid = $center->centerid;
            $securityAccess->save();
        }
        return redirect()->back()->with(session()->flash('info', 'Registro agregadó con exito'));
    }

    private function getAccess($id)
    {
        $user = User::findOrFail($id);
        return (new SecurityAccessWarehouse())->where('username', $user->username)
            ->select(['id', 'centerid', 'warehouseid'])->get();
    }
}
