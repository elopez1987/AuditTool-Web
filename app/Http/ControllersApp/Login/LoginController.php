<?php

namespace App\Http\ControllersApp\Login;

use App\Http\Controllers\Controller;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Configuration\WebService\WsLog;
use App\Models\Security\Modules\ModelHasRole;
use App\Models\Security\Users\SecurityUsersSession;
use App\Models\Security\Users\StorageUserDevice;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;


class LoginController extends Controller
{
    public function loginApp(Request $request)
    {
        $logws = WsLog::createLog('User', 'loginApp', $request->all());
        $validatedData = $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);
        $username = filter_var($validatedData['username']);
        $password = filter_var($validatedData['password']);
        $userValidation = User::where('username', '=', $username)->first();
        if (empty($userValidation)) {
            return $this->responseErrorJson('El usuario no existe, favor de validar');
        }
        if ($userValidation->statusid == 'D') {
            return $this->responseErrorJson('El usuario se encuentra inactivo, favor de validar');
        }
        if (auth()->attempt(['username' => $username, 'password' => $password])) {
            $token = auth()->user()->createToken('YMS')->accessToken;
            $paramters = $this->getParameterSystem();
            $this->updateLastSession();
            $this->registerDevice($request['serialnumber']);
            if (empty($token)) {
                $response = $this->responseErrorJson('Error al generar el Token, intente mas tarde');
            } else {
                $response = $this->responseSuccessJson($token, $paramters);
            }
        } else {
            $response = $this->responseErrorJson('La contraseña es incorrecta, favor de validar.');
        }
        $logws->response = $response;
        $logws->save();
        return $response;
    }

    public function getParameterSystem()
    {
        $parameterWare = SystemWarehouse::where('warehouseid', '=', Auth::user()->access->warehouseid)->value('configurationflag');
        $parameterCenter = SystemCenterDistribution::where('centerid', Auth::user()->access->centerid)->value('auxiliary05');
        $parameterWare = is_array($parameterWare) ? $parameterWare : (array)$parameterWare;
        $parameterCenter = is_array($parameterCenter) ? $parameterCenter : (array)$parameterCenter;
        if ($parameterWare !== null && $parameterCenter !== null) {
            $parameters = array_merge($parameterWare, $parameterCenter);
        } else {
            $parameters = [];
            if ($parameterWare !== null) {
                $parameters = $parameterWare;
            }
            if ($parameterCenter !== null) {
                $parameters = array_merge($parameters, $parameterCenter);
            }
        }
        return $parameters;
    }

    public function updateLastSession(): void
    {
        SecurityUsersSession::updateOrCreate(
            ['username' => Auth::user()->username],
            ['lastsessiondate' => Carbon::now(),]
        );
    }

    /**
     * @return array
     */
    public function getPermissions(): array
    {
        
//        return DB::table('role_has_permissions')
//            ->whereIn('role_id', (function ($query) {
//                $query->from('model_has_roles')
//                    ->select('role_id')
//                    ->where('model_id', '=', Auth::id());
//            }))->pluck('permission_id')->toArray();

        $modelId = ModelHasRole::where('role_id', '=', Auth::user()->roles->first()->id)->value('role_id');
        return DB::table('permissions')
            ->select(DB::raw("CAST(id AS NVARCHAR(MAX)) AS Resultado"))
            ->whereIn('id', function ($query) use ($modelId) {
                $query->select('permission_id')
                    ->from('role_has_permissions')
                    ->where('role_id', $modelId);
            })
            ->unionAll(
                DB::table('permissions')
                    ->select(DB::raw("CAST(name AS NVARCHAR(MAX)) AS Resultado"))
                    ->whereIn('id', function ($query) use ($modelId) {
                        $query->select('permission_id')
                            ->from('role_has_permissions')
                            ->where('role_id', $modelId);
                    })
            )
            ->pluck('Resultado')->toArray();

    }

    /**
     * @param $serialnumber
     * @return void
     */
    public function registerDevice($serialnumber): void
    {
        if ($serialnumber != '') {
            StorageUserDevice::create([
                'username' => Auth::id(),
                'serialnumber' => $serialnumber,
                'created_at' => Carbon::now()
            ]);
        }
    }

    public function responseErrorJson($message): JsonResponse
    {
        return response()->json([
            'ServiceName' => 'loginApp',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '1',
            'Message' => $message,
        ]);
    }

    /**
     * @param string $token
     * @param array $paramters
     * @return JsonResponse
     */
    public function responseSuccessJson(string $token, array $paramters): JsonResponse
    {
        return response()->json([
            'token' => $token,
            'user' => Auth::user()->name,
            'centerid' => Auth::user()->access->centerid,
            'centername' => Auth::user()->access->center->name,
            'role' => 'OP',
            'ServiceName' => 'loginApp',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '0',
            'Permissions' => $this->getPermissions(),
            'Parameters' => $paramters,
            'Message' => 'Sesión iniciada correctamente.',
        ]);
    }


}
