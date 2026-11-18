<?php

declare(strict_types=1);

namespace App\Auth;

use League\OAuth2\Server\Grant\PasswordGrant;
use Psr\Http\Message\ServerRequestInterface;

final class LocalPasswordGrant extends PasswordGrant
{
    protected function getRequestParameter(string $parameter, ServerRequestInterface $request, ?string $default = null): ?string
    {
        if ($parameter === 'password') {
            $password = ((array) $request->getParsedBody())['password'] ?? null;

            return is_string($password) && $password !== '' ? $password : null;
        }

        return parent::getRequestParameter($parameter, $request, $default);
    }
}
