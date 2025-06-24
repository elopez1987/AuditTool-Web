<?php

namespace App\Http\ControllersApp\Login;

use App\Auth\UserRepository;
use App\Http\Controllers\Controller;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
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
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function loginApp(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'username' => 'required|string',
                'password' => 'required|string',
            ]);
            $username = filter_var($validatedData['username']);
            $password = filter_var($validatedData['password']);
            $user = User::where('username', $username)->where('password2', $password)->first();
            if (empty($user)) {
                return $this->errorResponseJson('El usuario o contraseña son invalidos');
            }
            if ($user->statusid != 'E') {
                return $this->errorResponseJson('El usuario se encuentra inactivo, favor de validar.');
            }
            if (empty($user->auth0)) {
                return $this->errorResponseJson('El usuario no tiene asignado un perfil de usuario en Auth0');
            }
            $sub = $user->auth0;
            $user = $this->userRepository->fromSession([
                'sub' => $sub,
            ]);
            $token = $user->createToken('YMS')->accessToken;
            $paramters = $this->getParameterSystem($user);
            $this->updateLastSession($user);
            $this->registerDevice($request['serialnumber']);
            $permissions = $this->getPermissions($user);
            $response = $this->responseSuccessJson($token, $paramters, $permissions, $user);
            return $response;

        } catch (InvalidTokenException $e) {
            return response()->json(['error' => 'Invalid token'], 401);
        }
    }


    public function responseSuccessJson(string $token, array $paramters, array $permissions, $user): JsonResponse
    {
        return response()->json([
            'token' => $token,
            'user' => $user->name,
            'centerid' => $user->access->centerid,
            'centername' => $user->access->center->name,
            'role' => 'OP',
            'ServiceName' => 'loginApp',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '0',
            'Permissions' => $permissions,
            'Parameters' => $paramters,
            'Message' => 'Sesión iniciada correctamente.',
        ]);
    }


    public function getParameterSystem($user)
    {
        $parameterWare = SystemWarehouse::where('warehouseid', '=', $user->access->warehouseid)->value('configurationflag');
        $parameterCenter = SystemCenterDistribution::where('centerid', $user->access->centerid)->value('auxiliary05');
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


    public function errorResponseJson($message): JsonResponse
    {
        return response()->json([
            'ServiceName' => 'loginApp',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '1',
            'Message' => $message,
            'Status' => 'ERROR',
            'Data' => []
        ]);
    }

    public function updateLastSession($user): void
    {
        SecurityUsersSession::updateOrCreate(
            ['username' => $user->username],
            ['lastsessiondate' => Carbon::now(),]
        );
    }

    /**
     * @return array
     */
    public function getPermissions($user): array
    {
        
//        return DB::table('role_has_permissions')
//            ->whereIn('role_id', (function ($query) use ($user) {
//                $query->from('model_has_roles')
//                    ->select('role_id')
//                    ->where('model_id', '=', $user->id);
//            }))->pluck('permission_id')->toArray();

        $modelId = ModelHasRole::where('role_id', '=', $user->roles->first()->id)->value('role_id');
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
}
