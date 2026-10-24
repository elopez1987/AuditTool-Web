<?php

declare(strict_types=1);

return [
    'enabled' => (bool) env('MOBILE_AUTH_ENABLED', false),
    'domain' => env('MOBILE_AUTH0_DOMAIN', ''),
    'audience' => env('MOBILE_AUTH0_AUDIENCE', ''),
    'allowed_client_ids' => array_values(array_filter(array_map(
        'trim',
        explode(',', (string) env('MOBILE_AUTH0_CLIENT_IDS', ''))
    ))),
];
