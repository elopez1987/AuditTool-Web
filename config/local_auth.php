<?php

declare(strict_types=1);

return [
    'token_app' => env('TOKEN_APP', ''),
    'client_id' => env('LOCAL_AUTH_PASSPORT_CLIENT_ID'),
    'access_token_minutes' => 720,
    'refresh_idle_days' => 7,
    'absolute_session_days' => 30,
    'limits' => [
        'login_ip' => 30,
        'login_failures_pair' => 5,
        'login_failures_account' => 20,
        'refresh_ip' => 60,
        'refresh_family' => 6,
        'profile_ip' => 300,
        'profile_user' => 120,
        'logout_ip' => 60,
    ],
];
