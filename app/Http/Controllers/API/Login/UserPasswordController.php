<?php

namespace App\Http\ControllersApp\Login;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\UpdatePasswordRequest;
use App\Traits\Api\General\ResponseApi;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use OpenApi\Attributes as OA;

class UserPasswordController extends Controller
{
    use ResponseApi;

    /**
     * Actualiza la contraseña del usuario con medidas de seguridad avanzadas
     */
    #[OA\Post(
        path: '/updatePasswordForUser',
        operationId: 'updatePasswordForUser',
        summary: 'Update user password',
        description: 'Securely updates a user password with advanced security measures including current password verification, strong password requirements, rate limiting, and comprehensive audit logging. This endpoint implements enterprise-grade security controls to prevent unauthorized password changes and brute force attacks.',
        tags: ['Authentication'],
        security: [['bearerAuth' => []]],
        requestBody: new OA\RequestBody(
            description: 'Password change request with current and new password',
            required: true,
            content: new OA\JsonContent(
                required: ['username', 'oldpassword', 'newpassword', 'newpassword_confirmation'],
                properties: [
                    new OA\Property(
                        property: 'username',
                        type: 'string',
                        description: 'Username of the account to update',
                        example: 'johndoe',
                        minLength: 3,
                        maxLength: 50
                    ),
                    new OA\Property(
                        property: 'oldpassword',
                        type: 'string',
                        description: 'Current password for verification',
                        example: 'CurrentP@ssw0rd123!',
                        minLength: 6,
                        maxLength: 255,
                        format: 'password'
                    ),
                    new OA\Property(
                        property: 'newpassword',
                        type: 'string',
                        description: 'New password meeting complexity requirements: minimum 12 characters, mixed case, numbers, symbols, not compromised',
                        example: 'NewSecur3P@ssw0rd2024!',
                        minLength: 12,
                        maxLength: 255,
                        format: 'password'
                    ),
                    new OA\Property(
                        property: 'newpassword_confirmation',
                        type: 'string',
                        description: 'Confirmation of the new password (must match newpassword)',
                        example: 'NewSecur3P@ssw0rd2024!',
                        minLength: 12,
                        maxLength: 255,
                        format: 'password'
                    ),
                ],
                type: 'object'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Password updated successfully',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'success',
                            summary: 'Successful password update',
                            value: [
                                'ServiceName' => 'updatePasswordForUser',
                                'ProcessDate' => '2025-09-15 22:09:05',
                                'Code' => '0',
                                'Message' => 'Contraseña actualizada correctamente.',
                                'Status' => 'SUCCESSFUL',
                                'Data' => [
                                    'ID' => 123,
                                    'UserName' => 'JOHN DOE',
                                    'Email' => 'john.doe@company.com',
                                    'Avatar' => 'avatars/johndoe.png',
                                    'Parameters' => [
                                        'theme' => 'dark',
                                        'language' => 'es',
                                        'timezone' => 'America/Mexico_City',
                                    ],
                                ],
                            ]
                        ),
                    ],
                    properties: [
                        new OA\Property(property: 'ServiceName', type: 'string', example: 'updatePasswordForUser'),
                        new OA\Property(property: 'ProcessDate', type: 'string', example: '2025-09-15 22:09:05', description: 'Timestamp of the response'),
                        new OA\Property(property: 'Code', type: 'string', example: '0', description: 'Success code (0 = success, 1 = error)'),
                        new OA\Property(property: 'Message', type: 'string', example: 'Contraseña actualizada correctamente.'),
                        new OA\Property(property: 'Status', type: 'string', example: 'SUCCESSFUL', enum: ['SUCCESSFUL', 'ERROR']),
                        new OA\Property(
                            property: 'Data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'ID', type: 'integer', example: 123, description: 'User ID'),
                                new OA\Property(property: 'UserName', type: 'string', example: 'JOHN DOE', description: 'User full name (uppercase)'),
                                new OA\Property(property: 'Email', type: 'string', example: 'john.doe@company.com', description: 'User email address'),
                                new OA\Property(property: 'Avatar', type: 'string', example: 'avatars/johndoe.png', description: 'User avatar path', nullable: true),
                                new OA\Property(
                                    property: 'Parameters',
                                    type: 'object',
                                    description: 'User configuration parameters',
                                    additionalProperties: true,
                                    nullable: true
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: 'Bad request - Various password change errors',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'user_not_found',
                            summary: 'User not found',
                            value: [
                                'ServiceName' => 'updatePasswordForUser',
                                'ProcessDate' => '2025-09-15 22:09:05',
                                'Code' => '1',
                                'Message' => 'Usuario no encontrado.',
                                'Status' => 'ERROR',
                                'Data' => [],
                            ]
                        ),
                        new OA\Examples(
                            example: 'wrong_current_password',
                            summary: 'Current password incorrect',
                            value: [
                                'ServiceName' => 'updatePasswordForUser',
                                'ProcessDate' => '2025-09-15 22:09:05',
                                'Code' => '1',
                                'Message' => 'La contraseña actual no es correcta.',
                                'Status' => 'ERROR',
                                'Data' => [],
                            ]
                        ),
                        new OA\Examples(
                            example: 'same_password',
                            summary: 'New password same as current',
                            value: [
                                'ServiceName' => 'updatePasswordForUser',
                                'ProcessDate' => '2025-09-15 22:09:05',
                                'Code' => '1',
                                'Message' => 'La nueva contraseña debe ser diferente a la actual.',
                                'Status' => 'ERROR',
                                'Data' => [],
                            ]
                        ),
                    ],
                    properties: [
                        new OA\Property(property: 'ServiceName', type: 'string', example: 'updatePasswordForUser'),
                        new OA\Property(property: 'ProcessDate', type: 'string', example: '2025-09-15 22:09:05'),
                        new OA\Property(property: 'Code', type: 'string', example: '1'),
                        new OA\Property(property: 'Message', type: 'string', example: 'Error message describing the issue'),
                        new OA\Property(property: 'Status', type: 'string', example: 'ERROR'),
                        new OA\Property(property: 'Data', type: 'array', items: new OA\Items, example: []),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error - Invalid password format or missing fields',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'validation_errors',
                            summary: 'Password validation failed',
                            value: [
                                'message' => 'The given data was invalid.',
                                'errors' => [
                                    'username' => ['El usuario especificado no existe.'],
                                    'oldpassword' => ['La contraseña actual no es correcta.'],
                                    'newpassword' => [
                                        'La contraseña debe tener al menos 12 caracteres.',
                                        'La contraseña debe contener letras mayúsculas y minúsculas.',
                                        'La contraseña debe contener al menos un número.',
                                        'La contraseña debe contener al menos un símbolo.',
                                        'Esta contraseña aparece en una lista de contraseñas comprometidas.',
                                    ],
                                    'newpassword_confirmation' => ['La confirmación debe coincidir con la nueva contraseña.'],
                                ],
                            ]
                        ),
                    ],
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'The given data was invalid.'),
                        new OA\Property(
                            property: 'errors',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'username',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'El usuario especificado no existe.')
                                ),
                                new OA\Property(
                                    property: 'oldpassword',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'La contraseña actual no es correcta.')
                                ),
                                new OA\Property(
                                    property: 'newpassword',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'La contraseña debe tener al menos 12 caracteres.')
                                ),
                                new OA\Property(
                                    property: 'newpassword_confirmation',
                                    type: 'array',
                                    items: new OA\Items(type: 'string', example: 'La confirmación debe coincidir con la nueva contraseña.')
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 429,
                description: 'Rate limit exceeded - Too many password change attempts',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'rate_limited',
                            summary: 'Rate limit exceeded',
                            value: [
                                'ServiceName' => 'updatePasswordForUser',
                                'ProcessDate' => '2025-09-15 22:09:05',
                                'Code' => '429',
                                'Message' => 'Demasiados intentos de cambio de contraseña. Intente nuevamente en 1 hora.',
                                'Status' => 'RATE_LIMITED',
                                'Data' => [
                                    'retry_after' => 3600,
                                    'max_attempts' => 5,
                                ],
                            ]
                        ),
                    ],
                    properties: [
                        new OA\Property(property: 'ServiceName', type: 'string', example: 'updatePasswordForUser'),
                        new OA\Property(property: 'ProcessDate', type: 'string', example: '2025-09-15 22:09:05'),
                        new OA\Property(property: 'Code', type: 'string', example: '429'),
                        new OA\Property(property: 'Message', type: 'string', example: 'Demasiados intentos de cambio de contraseña. Intente nuevamente en 1 hora.'),
                        new OA\Property(property: 'Status', type: 'string', example: 'RATE_LIMITED'),
                        new OA\Property(
                            property: 'Data',
                            type: 'object',
                            properties: [
                                new OA\Property(property: 'retry_after', type: 'integer', example: 3600, description: 'Seconds until next attempt allowed'),
                                new OA\Property(property: 'max_attempts', type: 'integer', example: 5, description: 'Maximum attempts per hour'),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: 'Internal server error',
                content: new OA\JsonContent(
                    examples: [
                        new OA\Examples(
                            example: 'server_error',
                            summary: 'Internal server error',
                            value: [
                                'ServiceName' => 'updatePasswordForUser',
                                'ProcessDate' => '2025-09-15 22:09:05',
                                'Code' => '1',
                                'Message' => 'Error interno del servidor. Intente nuevamente.',
                                'Status' => 'ERROR',
                                'Data' => [],
                            ]
                        ),
                    ],
                    properties: [
                        new OA\Property(property: 'ServiceName', type: 'string', example: 'updatePasswordForUser'),
                        new OA\Property(property: 'ProcessDate', type: 'string', example: '2025-09-15 22:09:05'),
                        new OA\Property(property: 'Code', type: 'string', example: '1'),
                        new OA\Property(property: 'Message', type: 'string', example: 'Error interno del servidor. Intente nuevamente.'),
                        new OA\Property(property: 'Status', type: 'string', example: 'ERROR'),
                        new OA\Property(property: 'Data', type: 'array', items: new OA\Items, example: []),
                    ]
                )
            ),
        ]
    )]
    public function updatePasswordForUser(UpdatePasswordRequest $request): JsonResponse
    {
        DB::beginTransaction();

        try {
            $username = $request->validated()['username'];
            $oldPassword = $request->validated()['oldpassword'];
            $newPassword = $request->validated()['newpassword'];

            // Buscar usuario
            $user = User::where('username', $username)->first();

            if (! $user) {
                $this->logSecurityEvent('password_change_attempt', [
                    'username' => $username,
                    'result' => 'user_not_found',
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::rollback();

                return $this->getResponseWithErrors(
                    'updatePasswordForUser',
                    'Usuario no encontrado.'
                );
            }

            // Verificar contraseña actual (doble verificación por seguridad)
            if (! Hash::check($oldPassword, $user->password)) {
                $this->logSecurityEvent('password_change_failed', [
                    'user_id' => $user->id,
                    'username' => $username,
                    'result' => 'invalid_current_password',
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::rollback();

                return $this->getResponseWithErrors(
                    'updatePasswordForUser',
                    'La contraseña actual no es correcta.'
                );
            }

            // Verificar que la nueva contraseña sea diferente
            if (Hash::check($newPassword, $user->password)) {
                $this->logSecurityEvent('password_change_failed', [
                    'user_id' => $user->id,
                    'username' => $username,
                    'result' => 'same_password_attempted',
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]);

                DB::rollback();

                return $this->getResponseWithErrors(
                    'updatePasswordForUser',
                    'La nueva contraseña debe ser diferente a la actual.'
                );
            }

            // Actualizar contraseña y metadatos de seguridad
            $user->password = Hash::make($newPassword);
            $user->password_changed_at = Carbon::now();

            // Agregar entrada al log de seguridad del usuario
            $securityLog = $user->password_security_log ?? [];
            $securityLog[] = [
                'changed_at' => Carbon::now()->toISOString(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'method' => 'api_self_change',
            ];

            // Mantener solo los últimos 10 registros
            if (count($securityLog) > 10) {
                $securityLog = array_slice($securityLog, -10);
            }

            $user->password_security_log = $securityLog;
            $user->save();

            // Log de éxito
            $this->logSecurityEvent('password_change_success', [
                'user_id' => $user->id,
                'username' => $username,
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'changed_at' => Carbon::now(),
            ]);

            DB::commit();

            return $this->successResponseJson($user);
        } catch (\Exception $e) {
            DB::rollback();

            Log::error('Error crítico en cambio de contraseña', [
                'username' => $request->input('username'),
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'ip' => $request->ip(),
                'timestamp' => Carbon::now(),
            ]);

            return $this->getResponseWithErrors(
                'updatePasswordForUser',
                'Error interno del servidor. Intente nuevamente.'
            );
        }
    }

    /**
     * Registra eventos de seguridad relacionados con cambios de contraseña
     */
    private function logSecurityEvent(string $event, array $data): void
    {
        Log::channel('security')->info('Password Security Event', [
            'event_type' => $event,
            'service' => 'updatePasswordForUser',
            'timestamp' => Carbon::now(),
            'data' => $data,
        ]);
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
                //                'ID' => $user->id,
                'UserName' => $user->name,
                'Email' => $user->email,
                //                'Avatar' => $user->avatar,
                //                'Parameters' => $user->configurationflag,
            ],
        ]);
    }
}
