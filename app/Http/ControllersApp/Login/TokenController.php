<?php

namespace App\Http\ControllersApp\Login;

use App\Http\Controllers\Controller;
use App\Models\Configuration\WebService\WsLog;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TokenController extends Controller
{
    public function getToken(Request $request)
    {
        $validatedData = $request->validate([
            'Usuario' => 'required|string',
            'Password' => 'required|string',
        ]);
        $username = filter_var($validatedData['Usuario']);
        $password = filter_var($validatedData['Password']);
        $user = User::where('username', $username)->first();
        $logws = WsLog::createLog('User', 'getToken', $request->all());
        if (empty($user)) {
            return $this->responseErrorJson('El usuario no existe en la base de datos.');
        }
        if ($user->statusid == 'D') {
            return $this->errorResponseJson('El usuario se encuentra inactivo, favor de validar.');
        }
        if (auth()->attempt(['username' => $username, 'password' => $password])) {
            $token = auth()->user()->createToken('YMS')->accessToken;
            $response = $this->responseSuccessJson($token);
        } else {
            $response = $this->responseErrorJson('La contraseña inccorrecta, favor de validar.');
        }
        $logws->response = $response;
        $logws->save();
        return $response;
    }

    public function responseErrorJson($message): JsonResponse
    {
        return response()->json([
            'ServiceName' => 'getToken',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '1',
            'Message' => $message,
            'Status' => 'ERROR',
        ]);
    }

    /**
     * @param string $token
     * @return JsonResponse
     */
    public function responseSuccessJson(string $token): JsonResponse
    {
        return response()->json([
            'ServiceName' => 'getToken',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '0',
            'Message' => 'Sesión iniciada correctamente.',
            'Status' => 'SUCCESSFUL',
            'Data' => [
                'Token' => $token,
                'UserName' => Auth::user()->name,
                'Center' => Auth::user()->access->center->name,
            ]
        ]);
    }
}
