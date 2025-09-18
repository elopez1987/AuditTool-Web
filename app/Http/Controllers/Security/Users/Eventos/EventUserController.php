<?php

namespace App\Http\Controllers\Security\Users\Eventos;

use App\Http\Controllers\BaseController;
use App\Imports\Security\ImportUsers;
use App\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class EventUserController extends BaseController
{

	public function restoreuser($id)
    {
		$user = User::withTrashed()->where('id', $id)->first();
        $user->statusid = 'E';
        $user->save();
        $user->restore();
        return $this->redirectBackSuccessMessage('Usuario restaurado correctamente');
    }

    public function importusers(Request $request)
    {
        Excel::import(new ImportUsers(), $request->file('file'), '', \Maatwebsite\Excel\Excel::XLSX);
        return $this->successResponse('securityusers.index');
    }

    public function parameter($uuiduser)
    {
        $this->authorize('securityusers.parameter');
        $user = User::where('uuiduser', $uuiduser)->withTrashed()->firstOrFail();
        $parameters = $this->getStatusForSchema('USR');
        return view('Security.Users.Parameter.create', compact('user', 'parameters'));
    }

    public function securityusersparameterstore($uuiduser, Request $request)
    {
        $user = User::where('uuiduser', $uuiduser)->withTrashed()->first();
        $user->configurationflag = $request['parameter'];
        $user->save();
        return $this->redirectBackSuccess();
    }


}
