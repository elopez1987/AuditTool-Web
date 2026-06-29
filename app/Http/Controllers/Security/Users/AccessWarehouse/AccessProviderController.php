<?php

namespace App\Http\Controllers\Security\Users\AccessWarehouse;

use App\Http\Controllers\BaseController;
use App\Http\Services\Security\ServiceUser;
use App\Models\Receipt\ReceiptProvider;
use App\Models\Security\Users\AccessProvider;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Traits\Web\Security\QueryUser;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AccessProviderController extends BaseController
{
    use QueryUser;

    private ServiceUser $serviceUser;

    public function __construct(ServiceUser $serviceUser)
    {
        $this->serviceUser = $serviceUser;
    }

    public function index($uuiduser)
    {
        $user = User::where('uuiduser', $uuiduser)->firstOrFail();

        $listProviders = AccessProvider::with('provider:id,name,providerid')
            ->where('username', $user->username)->get();

        $assignedProviderIds = $listProviders->pluck('provider.id');

        $providers = ReceiptProvider::controlAccessUser()->where('statusid', 'E')
            ->whereNotIn('id', $assignedProviderIds)
            ->select(['id', 'name'])->get();

        return view(
            'Security.Users.AccessProviders.index',
            compact('user', 'providers', 'listProviders'),
        );
    }

    public function update(Request $request)
    {
        $providerIds = collect($request->input('providerid', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->unique()
            ->values();

        if ($providerIds->isEmpty()) {
            return $this->errorResponse('Debe seleccionar al menos un proveedor.');
        }

        $username = $request->input('username');

        $centerProvider = SecurityAccessWarehouse::where('username', $username)
            ->pluck('centerid')
            ->first();

        if (is_null($centerProvider)) {
            return $this->errorResponse('No se encontró el centro asociado al usuario.');
        }

        DB::transaction(function () use ($centerProvider, $providerIds, $username) {
            foreach ($providerIds as $providerId) {
                AccessProvider::updateOrCreate(
                    [
                        'centerid' => $centerProvider,
                        'username' => $username,
                        'providerid' => $providerId,
                    ]
                );
            }
        });

        return $this->redirectBackSuccessMessage('Proveedor asignado correctamente');
    }

    public function destroy($id)
    {
        $provider = AccessProvider::destroy($id);

        return $this->redirectBackSuccessMessage('Proveedor eliminado correctamente');
    }
}
