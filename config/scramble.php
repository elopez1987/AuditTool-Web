<?php

declare(strict_types=1);

use App\Http\Middleware\AuthenticateLocalApp;
use Dedoc\Scramble\Http\Middleware\RestrictedDocsAccess;
use Dedoc\Scramble\SecurityDocumentation\MiddlewareAuthSecurityStrategy;

return [
    'api_path' => 'api',
    'export_path' => 'api.json',
    'info' => [
        'version' => '1.0.0',
        'description' => 'API de autenticación local y catálogos de inventario. '
            .'Los endpoints de autenticación requieren X-App-Token; el perfil y los catálogos '
            .'requieren un access token Bearer de Passport. '
            .'Los catálogos de productos, ubicaciones, operadores y rutas son de solo lectura.',
    ],
    'ui' => [
        'title' => 'Audittool API',
    ],
    'middleware' => [
        'web',
        RestrictedDocsAccess::class,
    ],
    'security_strategy' => [
        MiddlewareAuthSecurityStrategy::class,
        [
            'middleware' => ['auth', 'auth:*', AuthenticateLocalApp::class],
        ],
    ],
];
