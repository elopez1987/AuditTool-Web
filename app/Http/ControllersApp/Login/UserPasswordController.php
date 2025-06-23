<?php

namespace App\Http\ControllersApp\Login;

use App\Http\Controllers\Controller;
use App\Traits\ResponseApi;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserPasswordController extends Controller
{
    use ResponseApi;
    public function updatePasswordForUser(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'username' => 'required|string',
            'oldpassword' => 'required|string',
            'newpassword' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return $this->getResponseWithErrors('updatePasswordForUser',
                'Los datos Usuario,Contraseña actual y Nueva Contraseña son valores obligatorios.');
        }
        $username = $request['username'];
        $passwordOld = $request['oldpassword'];
        $passwordNew = $request['newpassword'];
        $user = User::where('username', $username)->first();
        if ($passwordOld == $passwordNew) {
            return $this->getResponseWithErrors('updatePasswordForUser',
                'La contraseña actual y la nueva no pueden ser iguales.');
        }
        if ($user->password2 != $passwordOld) {
            return $this->getResponseWithErrors('updatePasswordForUser',
                'La contraseña actual es diferente, favor de validar.');
        }
        $user->password = bcrypt($passwordNew);
        $user->password2 = $passwordNew;
        $user->save();
        return $this->successResponseJson($user);

    }

    public function successResponseJson($user): JsonResponse
    {
        return response()->json([
            'ServiceName' => 'updatePasswordForUser',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '0',
            'Message' => 'Contraseña actualizada correctamente.',
            'Status' => 'SUCCESSFUL',
            'Data' => [
                'ID' => $user->id,
                'UserName' => $user->name,
                'Email' => $user->email,
                'Avatar' => $user->avatar,
                'Parameters' => $user->configurationflag,
            ]
        ]);
    }
}
