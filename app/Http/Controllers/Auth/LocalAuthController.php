<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LocalLoginRequest;
use App\Http\Requests\Auth\LocalRefreshRequest;
use App\Http\Resources\LocalAuthResponse;
use App\Services\Security\LocalLoginService;
use App\Services\Security\LocalSessionService;
use App\ViewModels\Security\LocalLoginViewModel;
use Dedoc\Scramble\Attributes\HeaderParameter;
use Dedoc\Scramble\Attributes\Response;
use Illuminate\Http\Request;

/**
 * Autenticación local para aplicaciones.
 *
 * Todos los endpoints requieren el encabezado X-App-Token.
 *
 * @tags Autenticación local
 */
final class LocalAuthController extends Controller
{
    public function __construct(
        private readonly LocalLoginService $service,
        private readonly LocalLoginViewModel $viewModel,
        private readonly LocalSessionService $sessions,
    ) {}

    /**
     * Iniciar sesión.
     *
     * Valida las credenciales locales y emite access y refresh tokens de Passport.
     * Requiere X-App-Token, pero no un token Bearer previo.
     * serialnumber es opcional; si se omite, device.warning informa que no se registró dispositivo.
     * Respuestas de error: 401 por credenciales o token de aplicación inválidos,
     * 403 por cuenta o centro no disponibles, 422 por validación, 429 por límite
     * de intentos y 503 si el servicio no está disponible.
     * Las respuestas usan success, code, message, meta y data; no deben almacenarse en caché.
     *
     * @unauthenticated
     *
     * @operationId loginLocalApp
     *
     * @response array{
     *     success: true,
     *     code: 200,
     *     message: string,
     *     meta: array{version: string, service: string, processDate: string},
     *     data: array{
     *         user: array{name: string, centerId: string, centerName: string},
     *         permissions: list<string>,
     *         parameters: array{app: list<string>, user: list<string>, warehouse: array<string, list<string>>},
     *         device: array{registered: bool, is_new: bool, warning: string|null},
     *         auth: array{accessToken: string, tokenType: string, expiresIn: int, expiresAt: string, refreshToken: string}
     *     }
     * }
     */
    #[HeaderParameter('X-App-Token', description: 'Token de aplicación proporcionado por el administrador.', required: true, type: 'string')]
    #[Response(401, 'Credenciales o token de aplicación inválidos.', type: 'array{success: false, code: 401, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(403, 'El usuario no tiene un centro de distribución activo asignado.', type: 'array{success: false, code: 403, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(422, 'Los datos proporcionados no son válidos.', type: 'array{success: false, code: 422, message: string, meta: array{version: string, service: string, processDate: string}, data: array{errors: array<string, list<string>>}}')]
    #[Response(429, 'Límite de solicitudes excedido.', type: 'array{success: false, code: 429, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(503, 'El servicio de autenticación local no está disponible.', type: 'array{success: false, code: 503, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    public function login(LocalLoginRequest $request): LocalAuthResponse
    {
        $result = $this->service->login($request->validated(), ['ip_address' => $request->ip() ?? 'unknown']);
        $request->attributes->set('local_auth_user_id', (int) $result['user']->id);
        $request->attributes->set('local_auth_username', (string) $result['user']->username);
        $request->attributes->set('local_auth_family_id', $result['session_family_id']);

        return new LocalAuthResponse(200, 'Sesión iniciada correctamente',
            $this->viewModel->forLogin($result));
    }

    /**
     * Renovar sesión.
     *
     * Intercambia refreshToken por un nuevo par de tokens y devuelve el contexto del usuario.
     * Requiere X-App-Token, pero no un access token Bearer vigente.
     * Respuestas de error: 401 por token inválido, 403 por cuenta o centro no
     * disponibles, 422 por validación, 429 por límite de solicitudes y 503 por
     * indisponibilidad del servicio. meta.service es refresh-user.
     *
     * @unauthenticated
     *
     * @operationId refreshLocalAppSession
     *
     * @response array{
     *     success: true,
     *     code: 200,
     *     message: string,
     *     meta: array{version: string, service: string, processDate: string},
     *     data: array{
     *         user: array{name: string, centerId: string, centerName: string},
     *         permissions: list<string>,
     *         parameters: array{app: list<string>, user: list<string>, warehouse: array<string, list<string>>},
     *         device: array{registered: bool, is_new: bool, warning: string|null},
     *         auth: array{accessToken: string, tokenType: string, expiresIn: int, expiresAt: string, refreshToken: string}
     *     }
     * }
     */
    #[HeaderParameter('X-App-Token', description: 'Token de aplicación proporcionado por el administrador.', required: true, type: 'string')]
    #[Response(401, 'Token de renovación o token de aplicación inválidos.', type: 'array{success: false, code: 401, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(403, 'El usuario no tiene un centro de distribución activo asignado.', type: 'array{success: false, code: 403, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(422, 'Los datos proporcionados no son válidos.', type: 'array{success: false, code: 422, message: string, meta: array{version: string, service: string, processDate: string}, data: array{errors: array<string, list<string>>}}')]
    #[Response(429, 'Límite de solicitudes excedido.', type: 'array{success: false, code: 429, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(503, 'El servicio de autenticación local no está disponible.', type: 'array{success: false, code: 503, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    public function refresh(LocalRefreshRequest $request): LocalAuthResponse
    {
        $result = $this->service->refresh($request->validated('refreshToken'), ['ip_address' => $request->ip() ?? 'unknown']);
        $request->attributes->set('local_auth_user_id', (int) $result['user']->id);
        $request->attributes->set('local_auth_username', (string) $result['user']->username);
        $request->attributes->set('local_auth_family_id', $result['session_family_id']);

        return new LocalAuthResponse(200, 'Sesión renovada correctamente',
            $this->viewModel->forLogin($result), 'refresh-user');
    }

    /**
     * Consultar el usuario autenticado.
     *
     * Requiere X-App-Token y Authorization: Bearer con un access token de la aplicación local.
     * Devuelve usuario, permisos y parámetros; no emite tokens ni incluye data.auth.
     * Respuestas de error: 401 por token ausente o inválido, 403 por cuenta o
     * centro no disponibles, 429 por límite de solicitudes y 503 por
     * indisponibilidad del servicio. meta.service es current-user.
     *
     * @operationId getLocalAppProfile
     *
     * @response array{
     *     success: true,
     *     code: 200,
     *     message: string,
     *     meta: array{version: string, service: string, processDate: string},
     *     data: array{
     *         user: array{name: string, centerId: string, centerName: string},
     *         permissions: list<string>,
     *         parameters: array{app: list<string>, user: list<string>, warehouse: array<string, list<string>>},
     *         device: array{registered: bool, is_new: bool, warning: string|null}
     *     }
     * }
     */
    #[HeaderParameter('X-App-Token', description: 'Token de aplicación proporcionado por el administrador.', required: true, type: 'string')]
    #[Response(401, 'Access token ausente o inválido, o token de aplicación inválido.', type: 'array{success: false, code: 401, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(403, 'El usuario no tiene un centro de distribución activo asignado.', type: 'array{success: false, code: 403, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(429, 'Límite de solicitudes excedido.', type: 'array{success: false, code: 429, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    #[Response(503, 'El servicio de autenticación local no está disponible.', type: 'array{success: false, code: 503, message: string, meta: array{version: string, service: string, processDate: string}, data: null}')]
    public function me(Request $request): LocalAuthResponse
    {
        return new LocalAuthResponse(200, 'Usuario autenticado correctamente',
            $this->viewModel->forLogin($this->service->profile($request->user('local-app'))), 'current-user');
    }

    /**
     * Cerrar la sesión actual y revocar todos sus tokens de renovación.
     *
     * @operationId logoutLocalAppSession
     */
    #[HeaderParameter('X-App-Token', description: 'Token de aplicación.', required: true, type: 'string')]
    public function logout(Request $request): LocalAuthResponse
    {
        $user = $request->user('local-app');
        $this->sessions->revokeSession($user, $user->token()->id);

        return new LocalAuthResponse(200, 'Sesión cerrada correctamente.', null, 'logout-user');
    }

    /**
     * Cerrar todas las sesiones locales del usuario autenticado.
     *
     * @operationId logoutAllLocalAppSessions
     */
    #[HeaderParameter('X-App-Token', description: 'Token de aplicación.', required: true, type: 'string')]
    public function logoutAll(Request $request): LocalAuthResponse
    {
        $this->sessions->revokeUser((int) $request->user('local-app')->id);

        return new LocalAuthResponse(200, 'Sesiones cerradas correctamente.', null, 'logout-all-user');
    }
}
