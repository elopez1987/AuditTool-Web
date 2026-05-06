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

    public function createaccess(string $uuiduser)
    {
        $user = User::where('uuiduser', $uuiduser)->firstOrFail();

        $warehouses = SystemWarehouse::select('warehouseid', 'name', 'centerid')
            ->where('statusid', 'E')
            ->orderBy('name')
            ->get();

        $access = $this->getAccess($user->username);

        $isSuperUser = SuperUser::where('userid', Auth::id())->exists();

        $centers = $isSuperUser
            ? SystemCenterDistribution::get(['centerid', 'name'])
            : SystemCenterDistribution::controlAccessUser()->get(['centerid', 'name']);

        return view('Security.Users.LevelWarehouse.create', compact(
            'user',
            'warehouses',
            'access',
            'centers'
        ));
    }

    public function storeLevelWarehouse(Request $request, string $uuiduser): RedirectResponse
    {
        $request->validate([
            'warehouse' => ['required', 'array', 'min:1'],
            'warehouse.*' => ['required', 'exists:systemwarehouses,warehouseid'],
        ]);

        $user = User::where('uuiduser', $uuiduser)->firstOrFail();
        $warehouses = $request->input('warehouse');

        $centers = SystemWarehouse::whereIn('warehouseid', $warehouses)
            ->pluck('centerid', 'warehouseid');

        $records = collect($warehouses)->map(fn ($warehouse) => [
            'username' => $user->username,
            'warehouseid' => $warehouse,
            'centerid' => $centers[$warehouse],
        ])->toArray();

        DB::transaction(function () use ($user, $records) {
            SecurityAccessWarehouse::where('username', $user->username)->delete();
            SecurityAccessWarehouse::insert($records);
        });

        return redirect()->back()->with('info', 'Registro agregado con éxito');
    }

    private function getAccess(string $username)
    {
        return SecurityAccessWarehouse::query()
            ->where('username', $username)
            ->select(['id', 'centerid', 'warehouseid'])
            ->get();
    }
}
