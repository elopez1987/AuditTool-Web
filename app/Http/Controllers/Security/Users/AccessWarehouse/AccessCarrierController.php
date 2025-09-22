<?php

namespace App\Http\Controllers\Security\Users\AccessWarehouse;

use App\Http\Controllers\BaseController;
use App\Http\Services\Security\ServiceUser;
use App\Models\Security\Users\AccessCarrier;
use App\Traits\Web\Security\QueryUser;
use App\User;
use Illuminate\Http\Request;

class AccessCarrierController extends BaseController
{
    use QueryUser;
    private ServiceUser $serviceUser;
    public function __construct(ServiceUser $serviceUser)
    {
        $this->serviceUser = $serviceUser;
    }

    public function index($uuiduser)
    {
        $this->authorize('securityusers.accesscarriers');
        $user = User::where('uuiduser', $uuiduser)->first();
        $carriers = $this->getCarriersForSelect();
        $listcarries = $this->getCarriersForUser($user);
        return view(
            'Security.Users.AccessCarriers.index',
            compact('user', 'carriers', 'listcarries')
        );
    }

    public function update(Request $request)
    {
        $centerid = $this->getCenterForCarrier($request);
        $this->serviceUser->assignCarrierToUser($request, $centerid);
        return $this->redirectBackSuccessMessage('Linea asignada con exito!!');
    }

    public function destroy($id)
    {
        $row = AccessCarrier::where('recordid', $id)->first();
        $row->delete();
        return $this->redirectBackSuccessMessage('Registro eliminado con exito');
    }
}
