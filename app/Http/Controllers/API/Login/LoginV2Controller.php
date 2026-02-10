<?php

namespace App\Http\ControllersApp\Login;

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
use OpenApi\Attributes as OA;

class LoginV2Controller extends Controller
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

    #[OA\Post(
        path: '/loginApp',
        operationId: 'loginApp',
        summary: 'Mobile app authentication',
        description: 'Authenticate mobile app user credentials and return access token with refresh token. This endpoint validates user credentials, registers device information, and generates JWT tokens for mobile API authentication.',
        tags: ['Authentication'],
        requestBody: new OA\RequestBody(
            description: 'User credentials and device information for mobile authentication',
            required: true,
            content: new OA\JsonContent(
                required: ['username', 'password', 'serialnumber'],
                properties: [
                    new OA\Property(
                        property: 'username',
                        type: 'string',
                        description: 'Username for authentication',
                        example: 'john.doe',
                        minLength: 3,
                        maxLength: 50,
                    ),
                    new OA\Property(
                        property: 'password',
                        type: 'string',
                        description: 'Password for authentication',
                        example: 'password123',
                        minLength: 6,
                        maxLength: 100,
                    ),
                    new OA\Property(
                        property: 'serialnumber',
                        type: 'string',
                        description: 'Device serial number for device registration',
                        example: 'ABC123XYZ456',
                    ),
                ],
                type: 'object',
            ),
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Authentication successful',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'success',
                            summary: 'Successful authentication',
                            value: [
                                'token' => 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...',
                                'user' => 'John Doe',
                                'centerid' => 1,
                                'centername' => 'Main Center',
                                'role' => 'OP',
                                'ServiceName' => 'loginApp',
                                'ProcessDate' => '2024-12-15 10:30:45',
                                'Code' => '0',
                                'Permissions' => ['1', '2', 'permission.name'],
                                'Parameters' => [],
                                'ParameterUser' => null,
                                'Message' => 'Sesión iniciada correctamente.',
                                'TokenType' => 'Bearer',
                                'ExpiresIn' => 86400,
                                'ExpiresAt' => '2024-12-16 10:30:45',
                                'refresh_token' => 'def502001234567890abcdef...',
                            ],
                        ),
                    ],
                    properties: [
                        new OA\Property(property: 'token', type: 'string', example: 'eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9...', description: 'JWT access token for API authentication'),
                        new OA\Property(property: 'user', type: 'string', example: 'John Doe', description: 'Full name of authenticated user'),
                        new OA\Property(property: 'centerid', type: 'integer', example: 1, description: 'Center ID assigned to user'),
                        new OA\Property(property: 'centername', type: 'string', example: 'Main Center', description: 'Center name assigned to user'),
                        new OA\Property(property: 'role', type: 'string', example: 'OP', description: 'User role'),
                        new OA\Property(property: 'ServiceName', type: 'string', example: 'loginApp'),
                        new OA\Property(property: 'ProcessDate', type: 'string', example: '2024-12-15 10:30:45', description: 'Timestamp of the response'),
                        new OA\Property(property: 'Code', type: 'string', example: '0', description: 'Success code (0 = success, 1 = error)'),
                        new OA\Property(property: 'Permissions', type: 'array', items: new OA\Items(type: 'string'), description: 'Array of permission IDs and names'),
                        new OA\Property(property: 'Parameters', type: 'object', description: 'System and center configuration parameters'),
                        new OA\Property(property: 'ParameterUser', description: 'User-specific configuration parameters'),
                        new OA\Property(property: 'Message', type: 'string', example: 'Sesión iniciada correctamente.'),
                        new OA\Property(property: 'TokenType', type: 'string', example: 'Bearer', description: 'Token type for authorization header'),
                        new OA\Property(property: 'ExpiresIn', type: 'integer', example: 86400, description: 'Token expiration time in seconds (24 hours)'),
                        new OA\Property(property: 'ExpiresAt', type: 'string', example: '2024-12-16 10:30:45', description: 'Token expiration timestamp in local timezone (America/Mexico_City)'),
                        new OA\Property(property: 'refresh_token', type: 'string', example: 'def502001234567890abcdef...', description: 'Refresh token for obtaining new access tokens'),
                    ],
                ),
            ),
            new OA\Response(
                response: 400,
                description: 'Authentication failed',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'user_not_found',
                            summary: 'User not found',
                            value: [
                                'ServiceName' => 'loginApp',
                                'ProcessDate' => '2024-12-15 10:30:45',
                                'Code' => '1',
                                'Message' => 'El usuario no existe, favor de validar',
                            ],
                        ),
                        new OA\Examples(
                            example: 'wrong_password',
                            summary: 'Wrong password',
                            value: [
                                'ServiceName' => 'loginApp',
                                'ProcessDate' => '2024-12-15 10:30:45',
                                'Code' => '1',
                                'Message' => 'La contraseña es incorrecta, favor de validar.',
                            ],
                        ),
                        new OA\Examples(
                            example: 'user_inactive',
                            summary: 'User inactive',
                            value: [
                                'ServiceName' => 'loginApp',
                                'ProcessDate' => '2024-12-15 10:30:45',
                                'Code' => '1',
                                'Message' => 'El usuario se encuentra inactivo, favor de validar',
                            ],
                        ),
                        new OA\Examples(
                            example: 'token_generation_failed',
                            summary: 'Token generation failed',
                            value: [
                                'ServiceName' => 'loginApp',
                                'ProcessDate' => '2024-12-15 10:30:45',
                                'Code' => '1',
                                'Message' => 'Error al generar el Token, intente mas tarde',
                            ],
                        ),
                    ],
                    properties: [
                        new OA\Property(property: 'ServiceName', type: 'string', example: 'loginApp'),
                        new OA\Property(property: 'ProcessDate', type: 'string', example: '2024-12-15 10:30:45'),
                        new OA\Property(property: 'Code', type: 'string', example: '1'),
                        new OA\Property(property: 'Message', type: 'string', example: 'Error message'),
                    ],
                ),
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'username',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The username field is required.'),
                                ),
                                new OA\Property(
                                    property: 'password',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The password field is required.'),
                                ),
                                new OA\Property(
                                    property: 'serialnumber',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'The serialnumber field is required.'),
                                ),
                            ],
                        ),
                    ],
                ),
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'ServiceName', type: 'string', example: 'loginApp'),
                        new OA\Property(property: 'ProcessDate', type: 'string', example: '2024-12-15 10:30:45'),
                        new OA\Property(property: 'Code', type: 'string', example: '1'),
                        new OA\Property(property: 'Message', type: 'string', example: 'Error interno del sistema, intente mas tarde'),
                    ],
                ),
            ),
        ]
    )]
    public function loginUser(LoginAppRequest $request)
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
        return SystemCenterDistribution::where('centerid', Auth::user()->access->centerid)->value('auxiliary05');
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
            'success' => true,
            'code' => 0,
            'message' => 'Sesión iniciada correctamente',
            'meta' => [
                'service' => 'login-user',
                'processDate' => Carbon::now()->toIso8601String(),
            ],
            'data' => [
                'user' => [
                    'name' => mb_strtoupper(Auth::user()->name, 'UTF-8'),
                    'centerId' => Auth::user()->access->centerid,
                    'centerName' => Auth::user()->access->center->name,
                ],
                'auth' => [
                    'accessToken' => $accessToken,
                    'tokenType' => 'Bearer',
                    'expiresIn' => 86400, // 24 horas
                    'expiresAt' => $expiresAtLocal,
                ],
                'permissions' => $this->getPermissions(),
                'parameters' => [
                    'app' => $parameters,
                    'user' => $parameterUser,
                    'warehouse' => $this->getParameterWarehouse(),
                ],
            ],
        ];

        // Agregar refresh token si existe
        if ($refreshToken) {
            $response['data']['auth']['refreshToken'] = $refreshToken;
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
