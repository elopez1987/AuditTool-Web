<?php

namespace App\Http\Controllers\Security\Users;

use App\Http\Controllers\BaseController;
use App\Models\Security\Users\SuperUser;
use App\User;
use Illuminate\Http\Request;

class SuperUserController extends BaseController
{
    public function index()
    {
        $this->authorize('superusers.index');
        $users = SuperUser::leftJoin('users', 'users.id', '=', 'securitysuperusers.userid')
            ->select(['securitysuperusers.*', 'users.name','users.username'])->get();
        return view('Security.SuperUsers.index', compact('users'));
    }

    public function create()
    {
        $this->authorize('superusers.create');
        $users = User::where('statusid', 'E')->select(['id', 'name'])->get();
        return view('Security.SuperUsers.create', compact('users'));
    }

    public function store(Request $request)
    {
        //        dd($request->all());
        $this->authorize('superusers.create');
        $users = new SuperUser($request->all());
        $users->save();
        return $this->redirectBackSuccessMessage('Usuario agregado con exito');
    }

    public function destroy($id)
    {
        $this->authorize('superusers.destroy');
        SuperUser::where('userid', $id)->delete();
        return $this->redirectBackSuccessMessage('Usuario eliminado con exito');
    }
}
