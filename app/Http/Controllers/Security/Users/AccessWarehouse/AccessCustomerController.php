<?php

namespace App\Http\Controllers\Security\Users\AccessWarehouse;

use App\Http\Controllers\BaseController;
use App\Http\Services\Security\ServiceUser;
use App\Models\Security\Users\SecurityAccessCustomer;
use App\Traits\Web\Security\QueryUser;
use App\User;
use Illuminate\Http\Request;

class AccessCustomerController extends BaseController
{
    use QueryUser;

    private ServiceUser $serviceUser;

    public function __construct(ServiceUser $serviceUser)
    {
        $this->serviceUser = $serviceUser;
    }

    public function index($uuiduser)
    {
        $this->authorize('securityusers.accesscustomer');
        $user = User::where('uuiduser', $uuiduser)->firstOrFail();
        $customers = $this->getOrderCustomerForSelect();
        $listCustomers = SecurityAccessCustomer::where('username', $user->username)->get();

        return view(
            'Security.Users.AccessCustomer.index',
            compact('user', 'customers', 'listCustomers')
        );
    }

    public function update(Request $request)
    {
        $centerCustomer = $this->getCenterForCustomer($request['customerid']);
        $this->serviceUser->assignCustomerToUser($request, $centerCustomer);

        return $this->redirectBackSuccessMessage('Cliente agregado con exito');
    }

    public function destroy($id)
    {
        SecurityAccessCustomer::destroy($id);

        return $this->redirectBackSuccessMessage('Registro eliminado con exito.');
    }
}
