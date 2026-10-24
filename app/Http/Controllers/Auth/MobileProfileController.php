<?php

declare(strict_types=1);

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\MobileUserResource;
use App\Services\Security\MobileAuthenticationService;
use Illuminate\Http\Request;

final class MobileProfileController extends Controller
{
    public function __construct(
        private readonly MobileAuthenticationService $authentication,
    ) {}

    public function __invoke(Request $request): MobileUserResource
    {
        return new MobileUserResource(
            $this->authentication->profile($request->user('auth0-mobile'))
        );
    }
}
