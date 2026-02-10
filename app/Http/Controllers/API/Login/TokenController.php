<?php

namespace App\Http\Controllers\API\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\GetTokenRequest;
use App\Models\Configuration\WebService\WsLog;
use App\Services\Security\AuthenticationAuditService;
use App\Services\Security\TimingAttackProtectionService;
use App\Services\Security\TokenManagementService;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;

class TokenController extends Controller
{
    public function getToken(GetTokenRequest $request): JsonResponse
    {
        $startTime = microtime(true);
        $auditService = app(AuthenticationAuditService::class);
        $tokenService = app(TokenManagementService::class);

        $validatedData = $request->getSanitizedData();
        $username = $validatedData['Usuario'];
        $password = $validatedData['Password'];

        // Generate device fingerprint
        $deviceFingerprint = $auditService->generateDeviceFingerprint($request);

        // Log del intento con datos sanitizados
        $logws = WsLog::createLog('User', 'getToken', [
            'Usuario' => $username,
            'Password' => '***REDACTED***',
            'device_fingerprint' => substr($deviceFingerprint, 0, 12) . '...',
        ]);

        try {
            // Find user
            $user = User::where('username', $username)->first();

            // Perform authentication with timing attack protection
            $authResult = TimingAttackProtectionService::authenticateWithTimingProtection(
                $user,
                $password,
                $startTime
            );

            // Determine response based on authentication result
            if ($authResult['authenticated']) {
                // Create secure token with enhanced metadata
                $tokenData = $tokenService->createSecureToken($user, $deviceFingerprint, [
                    'login_method' => 'getToken',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                // Log successful authentication
                $auditService->logAuthenticationAttempt(
                    $request,
                    $username,
                    true,
                    'Token generated successfully',
                    $user->id,
                    [
                        'token_type' => 'access_token',
                        'expires_in' => $tokenData['expires_in'],
                    ]
                );

                $response = $this->responseSuccessJson($tokenData['access_token'], $user, $tokenData['expires_at']);
            } else {
                // Determine failure reason
                $reason = $this->getFailureReason($authResult);

                // Log failed authentication
                $auditService->logAuthenticationAttempt(
                    $request,
                    $username,
                    false,
                    $reason,
                    $authResult['user_exists'] ? $user->id : null
                );

                $response = $this->responseErrorJson($reason);
            }

            $logws->response = $response;
            $logws->save();

            return $response;
        } catch (\Exception $e) {
            // Ensure timing consistency even on errors
            TimingAttackProtectionService::ensureMinimumDelay($startTime);

            // Log error
            $auditService->logAuthenticationAttempt(
                $request,
                $username,
                false,
                'System error during authentication',
                null,
                [
                    'error' => $e->getMessage(),
                ]
            );

            $response = $this->responseErrorJson('Error interno del sistema. Intente más tarde.');
            $logws->response = $response;
            $logws->save();

            return $response;
        }
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
     * Get failure reason based on authentication result
     */
    private function getFailureReason(array $authResult): string
    {
        if (! $authResult['user_exists']) {
            return 'El usuario no existe en la base de datos.';
        }
        if (! $authResult['user_active']) {
            return 'El usuario se encuentra inactivo, favor de validar.';
        }
        if (! $authResult['password_valid']) {
            return 'La contraseña es incorrecta, favor de validar.';
        }

        return 'Error de autenticación.';
    }

    public function responseSuccessJson(string $token, User $user, string $expiresAt): JsonResponse
    {
        // Convertir la fecha ISO (UTC) a la zona horaria de la aplicación
        $expiresAtLocal = Carbon::parse($expiresAt)->setTimezone(config('app.timezone'))->format('Y-m-d H:i:s');

        return response()->json([
            'ServiceName' => 'getToken',
            'ProcessDate' => Carbon::now()->format('Y-m-d H:i:s'),
            'Code' => '0',
            'Message' => 'Sesión iniciada correctamente.',
            'Status' => 'SUCCESSFUL',
            'Data' => [
                'Token' => $token,
                'UserName' => strtoupper($user->name),
                'Center' => $user->access->center->name ?? 'N/A',
                'TokenType' => 'Bearer',
                'ExpiresIn' => 86400, // 24 hours in seconds
                'ExpiresAt' => $expiresAtLocal,
            ],
        ]);
    }
}
