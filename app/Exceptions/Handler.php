<?php

namespace App\Exceptions;

use App\Http\Resources\LocalAuthResponse;
use App\Traits\Api\ModernApiResponse;
use Auth0\SDK\Exception\ConfigurationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use League\OAuth2\Server\Exception\OAuthServerException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;
use Throwable;

class Handler extends ExceptionHandler
{
    use ModernApiResponse;

    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [

    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
        'refreshToken',
        'refresh_token',
        'access_token',
        'TOKEN_APP',
    ];

    /**
     * Render an exception into an HTTP response.
     *
     * @param  Request  $request
     *
     * @throws Throwable
     */
    public function render($request, Throwable $exception): Response
    {
        if (($request->is('api/v1/auth/*') || $exception instanceof LocalAuthException)
            && ! ($this->isApiRequest($request) && $exception instanceof HttpExceptionInterface)) {
            return $this->renderLocalAuth($request, $exception);
        }

        // Evitar loop si Auth0 no está configurado
        if ($exception instanceof ConfigurationException && ! $this->isApiRequest($request)) {
            return response('Auth0 configuration error: '.$exception->getMessage(), 500);
        }

        return parent::render($request, $exception);
    }

    public function register(): void
    {
        $this->renderable(function (Throwable $exception, Request $request): ?JsonResponse {
            if (! $this->isApiRequest($request) || $exception instanceof HttpResponseException) {
                return null;
            }

            return $this->renderApiException($request, $exception);
        });
    }

    private function isApiRequest(Request $request): bool
    {
        return $request->is('api', 'api/*')
            || in_array('api', $request->route()?->gatherMiddleware() ?? [], true);
    }

    private function renderApiException(Request $request, Throwable $exception): JsonResponse
    {
        $serviceName = $this->resolveServiceNameFromRequest($request);

        if ($exception instanceof ValidationException) {
            return $this->modernValidationErrorResponse(
                errors: $exception->errors(),
                serviceName: $serviceName,
            );
        }

        $status = match (true) {
            $exception instanceof AuthenticationException,
            $exception instanceof OAuthServerException => 401,
            $exception instanceof HttpExceptionInterface => $exception->getStatusCode(),
            default => 500,
        };
        $headers = $exception instanceof HttpExceptionInterface ? $exception->getHeaders() : [];
        $normalizedHeaders = array_change_key_case($headers, CASE_LOWER);
        $allowedMethods = array_values(array_filter(array_map('trim', explode(',', $normalizedHeaders['allow'] ?? ''))));
        $retryAfter = $normalizedHeaders['retry-after'] ?? null;

        $response = match ($status) {
            400 => $this->modernBadRequestResponse(serviceName: $serviceName),
            401 => $this->modernUnauthorizedResponse(serviceName: $serviceName),
            403 => $this->modernForbiddenResponse(serviceName: $serviceName),
            404 => $this->modernNotFoundResponse(serviceName: $serviceName),
            405 => $this->modernMethodNotAllowedResponse(
                allowedMethods: $allowedMethods,
                serviceName: $serviceName,
            ),
            409 => $this->modernErrorResponse('Conflicto con el estado actual del recurso.', $serviceName, 409),
            422 => $this->modernErrorResponse('No se pudo procesar la solicitud.', $serviceName, 422),
            429 => $this->modernTooManyRequestsResponse(
                retryAfter: is_numeric($retryAfter) ? (int) $retryAfter : null,
                serviceName: $serviceName,
            ),
            500 => $this->modernServerErrorResponse(serviceName: $serviceName),
            502 => $this->modernErrorResponse('Respuesta inválida del servicio externo.', $serviceName, 502),
            503 => $this->modernErrorResponse('El servicio no está disponible temporalmente.', $serviceName, 503),
            504 => $this->modernErrorResponse('El servicio externo no respondió a tiempo.', $serviceName, 504),
            default => $this->modernErrorResponse(
                Response::$statusTexts[$status] ?? 'No se pudo procesar la solicitud.',
                $serviceName,
                $status,
            ),
        };

        $response->headers->add($headers);

        return $response;
    }

    public function report(Throwable $exception): void
    {
        if (app()->bound('request') && request()->is('api/v1/auth/*')) {
            if (! $exception instanceof LocalAuthException && ! $exception instanceof ValidationException
                && ! $exception instanceof OAuthServerException && ! $exception instanceof HttpExceptionInterface
                && ! $exception instanceof HttpResponseException) {
                Log::error('Local authentication request failed.', ['exception_type' => $exception::class]);
            }

            return;
        }

        parent::report($exception);
    }

    private function renderLocalAuth(Request $request, Throwable $exception): LocalAuthResponse
    {
        if ($exception instanceof LocalAuthException) {
            $request->attributes->set('local_auth_failure_reason', $exception->reason);
            if ($exception->userId !== null) {
                $request->attributes->set('local_auth_user_id', $exception->userId);
            }
            if ($exception->familyId !== null) {
                $request->attributes->set('local_auth_family_id', $exception->familyId);
            }
        }
        $service = match ($request->route()?->getName()) {
            'v1.auth.refresh' => 'refresh-user',
            'v1.auth.me' => 'current-user',
            'v1.auth.logout' => 'logout-user',
            'v1.auth.logout-all' => 'logout-all-user',
            'v1.auth.login' => 'login-user',
            default => 'api-service',
        };

        return match (true) {
            $exception instanceof HttpResponseException && $exception->getResponse() instanceof LocalAuthResponse => $exception->getResponse(),
            $exception instanceof LocalAuthException => new LocalAuthResponse(
                $exception->status, $exception->getMessage(), null, $service, $exception->headers
            ),
            $exception instanceof ValidationException => new LocalAuthResponse(
                422, 'Los datos proporcionados no son válidos.', ['errors' => $exception->errors()], $service
            ),
            $exception instanceof OAuthServerException => new LocalAuthResponse(
                401, 'Credenciales o token de renovación inválidos.', null, $service
            ),
            $exception instanceof HttpExceptionInterface => new LocalAuthResponse(
                $exception->getStatusCode(), 'No se pudo procesar la solicitud.', null, $service, $exception->getHeaders()
            ),
            default => new LocalAuthResponse(
                503, 'El servicio de autenticación local no está disponible.', null, $service
            ),
        };
    }
}
