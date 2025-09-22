<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    protected string $redirectTo = RouteServiceProvider::HOME;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function username(): string
    {
        return 'login';
    }

    protected function credentials(Request $request): array
    {
        $login = $request->input($this->username());
        $field = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        return [
            $field => $login,
            'password' => $request->input('password'),
        ];
    }

    protected function attemptLogin(Request $request): bool
    {
        $user = User::where('email', $request->input($this->username()))
            ->orWhere('username', $request->input($this->username()))
            ->select(['id', 'statusid', 'username'])->first();
        if (! empty($user)) {
            if ($user->statusid === 'E') {
                SecurityUsersSession::updateOrCreate(
                    ['username' => $user->username],
                    ['lastsessiondate' => Carbon::now()]
                );
                return $this->guard()->attempt($this->credentials($request), $request->filled('remember'));
            }
            throw ValidationException::withMessages([$this->username() => [trans('auth.user')]]);
        } else {
            throw ValidationException::withMessages([$this->username() => [trans('auth.user')]]);
        }
    }
}
