<?php

namespace App\Http\Contollers\API\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginAppRequest;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Warehouses\SystemWarehouse;
use App\Models\Configuration\WebService\WsLog;
use App\Models\Security\Modules\ModelHasRole;
use App\Models\Security\Users\SecurityAccessWarehouse;
use App\Models\Security\Users\SecurityUsersSession;
use App\Models\Security\Users\StorageUserDevice;
use App\Services\Security\AuthenticationAuditService;
use App\Services\Security\SecurityValidationService;
use App\Services\Security\TimingAttackProtectionService;
use App\Services\Security\TokenManagementService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class LoginController extends Controller
{
    protected $auditService;

    protected $securityValidationService;

    protected $tokenManagementService;

    protected $timingProtectionService;

    public function __construct(
        AuthenticationAuditService $auditService,
        SecurityValidationService $securityValidationService,
        TokenManagementService $tokenManagementService,
        TimingAttackProtectionService $timingProtectionService,
    ) {
        $this->auditService = $auditService;
        $this->securityValidationService = $securityValidationService;
        $this->tokenManagementService = $tokenManagementService;
        $this->timingProtectionService = $timingProtectionService;
    }

    public function loginApp(LoginAppRequest $request)
    {
        $startTime = microtime(true);

        // Log del intento con datos sanitizados
        $logws = WsLog::createLog('User', 'loginApp', $request->getLoggableData());

        try {
            // Obtener datos validados
            $validatedData = $request->getSanitizedData();
            $username = $validatedData['username'];
            $password = $validatedData['password'];
            $serialnumber = $validatedData['serialnumber'];
            $ipAddress = $request->ip();
            $userAgent = $request->userAgent();

            // Buscar usuario
            $userValidation = User::where('username', '=', $username)->first();

            if (empty($userValidation)) {
                // Simular tiempo de procesamiento para usuario no existente
                $this->timingProtectionService->simulateUserCheck();

                // Auditar intento fallido
                $this->auditService->logFailedAttempt(
                    $username,
                    $ipAddress,
                    $userAgent,
                    'user_not_found',
                    $request->all(),
                );

                $response = $this->responseErrorJson('El usuario no existe, favor de validar');
                $this->completeWithMinimumDelay($startTime);

                return $this->finalizeResponse($logws, $response);
            }

            if ($userValidation->statusid == 'D') {
                // Auditar intento con usuario inactivo
                $this->auditService->logFailedAttempt(
                    $username,
                    $ipAddress,
                    $userAgent,
                    'user_inactive',
                    $request->all(),
                );

                $response = $this->responseErrorJson('El usuario se encuentra inactivo, favor de validar');
                $this->completeWithMinimumDelay($startTime);

                return $this->finalizeResponse($logws, $response);
            }

            // Verificar credenciales con protección contra timing attacks
            $isValidLogin = $this->timingProtectionService->secureCredentialCheck(
                $username,
                $password,
                function ($user, $pass) {
                    return auth()->attempt(['username' => $user, 'password' => $pass]);
                },
            );

            if ($isValidLogin) {
                // Autenticación exitosa
                $user = auth()->user();

                // Generar tokens seguros
                $tokenData = $this->tokenManagementService->createTokens(
                    $user,
                    $ipAddress,
                    $userAgent,
                    $serialnumber,
                );

                if (empty($tokenData['access_token'])) {
                    $this->auditService->logFailedAttempt(
                        $username,
                        $ipAddress,
                        $userAgent,
                        'token_generation_failed',
                        $request->all(),
                    );

                    $response = $this->responseErrorJson('Error al generar el Token, intente mas tarde');
                } else {
                    // Obtener parámetros del sistema
                    $parameters = $this->getParameterSystem();
                    $parameterWarehouse = $this->getParameterWarehouse();
                    $parameterUser = User::where('id', $user->id)->value('configurationflag');

                    // Actualizar última sesión y registrar dispositivo
                    $this->updateLastSession();
                    $this->registerDevice($serialnumber);

                    // Auditar login exitoso
                    $this->auditService->logSuccessfulLogin(
                        $user,
                        $ipAddress,
                        $userAgent,
                        $request->all(),
                        $tokenData,
                    );

                    $response = $this->responseSuccessJsonWithToken(
                        $tokenData['access_token'],
                        $tokenData['refresh_token'],
                        $tokenData['expires_at'],
                        $parameters,
                        $parameterUser,
                    );
                }
            } else {
                // Credenciales incorrectas
                $this->auditService->logFailedAttempt(
                    $username,
                    $ipAddress,
                    $userAgent,
                    'invalid_credentials',
                    $request->all(),
                );

                $response = $this->responseErrorJson('La contraseña es incorrecta, favor de validar.');
            }
        } catch (\Exception $e) {
            // Log del error para debugging
            \Log::error('LoginApp Error: ' . $e->getMessage(), [
                'exception' => $e,
                'request' => $request->all(),
            ]);

            $response = $this->responseErrorJson('Error interno del sistema, intente mas tarde');
        }

        // Asegurar tiempo mínimo de respuesta
        $this->completeWithMinimumDelay($startTime);

        return $this->finalizeResponse($logws, $response);
    }

    public function getParameterSystem()
    {
        $parameterWare = SystemWarehouse::where('warehouseid', '=', Auth::user()->access->warehouseid)->value('configurationflag');
        $parameterCenter = SystemCenterDistribution::where('centerid', Auth::user()->access->centerid)->value('auxiliary05');
        $parameterWare = is_array($parameterWare) ? $parameterWare : (array) $parameterWare;
        $parameterCenter = is_array($parameterCenter) ? $parameterCenter : (array) $parameterCenter;
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
            ['lastsessiondate' => Carbon::now()],
        );
    }

    public function getPermissions(): array
    {
        $modelId = ModelHasRole::where('role_id', '=', Auth::user()->roles->first()->id)->value('role_id');

        return DB::table('permissions')
            ->select(DB::raw('CAST(name AS NVARCHAR(MAX)) AS Resultado'))
            ->whereIn('id', function ($query) use ($modelId) {
                $query->select('permission_id')
                    ->from('role_has_permissions')
                    ->where('role_id', $modelId);
            })->pluck('Resultado')->toArray();
    }

    public function registerDevice($serialnumber): void
    {
        if ($serialnumber != '') {
            StorageUserDevice::create([
                'username' => Auth::id(),
                'serialnumber' => $serialnumber,
                'created_at' => Carbon::now(),
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
     * Response exitoso con tokens de acceso y refresh
     */
    public function responseSuccessJsonWithToken(
        string $accessToken,
        ?string $refreshToken,
        string $expiresAt,
        array $parameters,
        $parameterUser,
    ): JsonResponse {
        // Convertir la fecha ISO (UTC) a la zona horaria de la aplicación
        $expiresAtLocal = Carbon::parse($expiresAt)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

        $response = [
            'token' => $accessToken,
            'user' => strtoupper(Auth::user()->name),
            'centerid' => Auth::user()->access->centerid,
            'centername' => Auth::user()->access->center->name,
            'role' => 'OP',
            'ServiceName' => 'loginApp',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '0',
            'Permissions' => $this->getPermissions(),
            'Parameters' => $parameters,
            'ParameterUser' => $parameterUser,
            'ParameterWarehouse' => $this->getParameterWarehouse(),
            'TokenType' => 'Bearer',
            'ExpiresIn' => 86400, // 24 hours in seconds
            'ExpiresAt' => $expiresAtLocal,
            'Message' => 'Sesión iniciada correctamente.',
        ];

        // Agregar refresh token si está disponible
        if ($refreshToken) {
            $response['refresh_token'] = $refreshToken;
        }

        return response()->json($response);
    }

    /**
     * Finalizar respuesta con logging
     */
    private function finalizeResponse($logws, $response)
    {
        $logws->response = $response;
        $logws->save();

        return $response;
    }

    /**
     * Completar con delay mínimo para protección contra timing attacks
     */
    private function completeWithMinimumDelay(float $startTime): void
    {
        $this->timingProtectionService->ensureMinimumDelayInstance($startTime);
    }

    private function getParameterWarehouse()
    {
        $warehouses = SecurityAccessWarehouse::where('username', Auth::user()->username)
            ->pluck('warehouseid');

        $parameters = SystemWarehouse::whereIn('warehouseid', $warehouses)
            ->pluck('configurationflag', 'warehouseid');

        $result = [];

        foreach ($warehouses as $warehouseid) {
            $result[$warehouseid] = $parameters[$warehouseid] ?? [];
        }

        return $result;
    }
}
