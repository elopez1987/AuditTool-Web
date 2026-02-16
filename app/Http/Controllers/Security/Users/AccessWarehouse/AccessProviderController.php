<?php

namespace App\Http\Controllers\Security\Users\AccessWarehouse;

use App\Http\Controllers\BaseController;
use App\Http\Services\Security\ServiceUser;
use App\Models\Security\Users\AccessProvider;
use App\Traits\Web\Security\QueryUser;
use App\User;
use Illuminate\Http\Request;

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
        $providers = $this->getReceiptProviderForSelect();
        $listProviders = AccessProvider::with('provider:id,name,providerid')
            ->where('username', $user->username)->get();

        return view(
            'Security.Users.AccessProviders.index',
            compact('user', 'providers', 'listProviders'),
        );
    }

    public function update(Request $request)
    {
        $centerProvider = $this->getCenterForProvider($request['providerid']);
        $this->serviceUser->assignProviderToUser($request, $centerProvider);

        return $this->redirectBackSuccessMessage('Proveedor asignado correctamente');
    }

    public function destroy($id)
    {
        $provider = AccessProvider::destroy($id);

        return $this->redirectBackSuccessMessage('Proveedor eliminado correctamente');
    }
}
