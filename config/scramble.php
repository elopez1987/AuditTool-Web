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
            .'requieren además un access token Bearer de Passport. '
            .'Las operaciones de escritura de productos están registradas, pero no implementadas.',
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
