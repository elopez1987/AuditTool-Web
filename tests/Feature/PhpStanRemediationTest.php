<?php

declare(strict_types=1);

use App\Http\Controllers\General\SelectDynamicController;
use App\Http\Controllers\Security\Users\UserController;
use App\Http\Middleware\PermissionWeb;
use App\Models\Configuration\Parameters\SystemParameter;
use App\Services\Configuration\SystemParameterService;
use App\Services\Notifications\PushNotificationService;
use App\User;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Schema;
use Kreait\Firebase\Contract\Messaging;
use Kreait\Firebase\Messaging\CloudMessage;
use Kreait\Laravel\Firebase\Facades\Firebase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

it('redirects guests before checking web permissions', function (): void {
    Auth::shouldReceive('guest')->once()->andReturnTrue();
    $request = Request::create('/protected');
    $next = static function (): never {
        throw new LogicException('Guests must not reach the next middleware.');
    };

    $response = (new PermissionWeb)->handle($request, $next, 'catalog.view');

    expect($response->getStatusCode())->toBe(302);
    expect($response->headers->get('Location'))->toBe(url('/login'));
});

it('denies authenticated requests without an explicit web permission', function (?string $permission): void {
    Auth::shouldReceive('guest')->once()->andReturnFalse();
    $request = Request::create('/protected');
    $next = static function (): never {
        throw new LogicException('Missing permissions must fail closed.');
    };

    try {
        (new PermissionWeb)->handle($request, $next, $permission);
        $this->fail('Expected a forbidden response.');
    } catch (HttpException $exception) {
        expect($exception->getStatusCode())->toBe(403);
    }
})->with([null, '', '   ']);

it('checks the supplied web permission before continuing', function (bool $allowed): void {
    Auth::shouldReceive('guest')->once()->andReturnFalse();
    $user = Mockery::mock(User::class);
    $user->shouldReceive('can')->once()->with('catalog.view')->andReturn($allowed);
    $request = Request::create('/protected');
    $request->setUserResolver(static fn (): User => $user);
    $expected = new Response('Allowed');
    $next = static fn (Request $received): Response => $received === $request
        ? $expected
        : throw new LogicException('Unexpected request.');

    if ($allowed) {
        expect((new PermissionWeb)->handle($request, $next, 'catalog.view'))->toBe($expected);

        return;
    }

    try {
        (new PermissionWeb)->handle($request, $next, 'catalog.view');
        $this->fail('Expected a forbidden response.');
    } catch (HttpException $exception) {
        expect($exception->getStatusCode())->toBe(403);
    }
})->with([true, false]);

it('handles password reset failures without accessing an undefined user', function (bool $existingUser): void {
    $this->prepareAuthenticationSchema();
    $userId = $existingUser ? $this->localUser() : 999;
    if ($existingUser) {
        Password::shouldReceive('sendResetLink')->once()
            ->with(['email' => 'mobile.user@example.test'])
            ->andThrow(new RuntimeException('Mail service unavailable.'));
    } else {
        Password::shouldReceive('sendResetLink')->never();
    }
    Log::shouldReceive('error')->once()->with(
        'Error al enviar correo de reset de contraseña',
        Mockery::on(static fn (array $context): bool => $context['user_id'] === $userId
            && isset($context['error'], $context['trace'])
            && ! array_key_exists('name', $context)),
    );

    $response = app(UserController::class)->sendPasswordReset($userId);

    expect($response->getStatusCode())->toBe(302);
    expect(session('error'))->toBe('Ocurrió un error al enviar el correo de reinicio.');
})->with([false, true]);

it('sends topic notifications using the installed Firebase message API', function (): void {
    $messaging = Mockery::mock(Messaging::class);
    $messaging->shouldReceive('send')->once()->with(Mockery::on(
        static fn (CloudMessage $message): bool => $message->jsonSerialize() === [
            'data' => ['event' => 'updated'],
            'notification' => ['title' => 'Test title', 'body' => 'Test body'],
            'topic' => 'audit-updates',
        ],
    ))->andReturn(['name' => 'test-message']);
    Firebase::shouldReceive('messaging')->once()->andReturn($messaging);

    app(PushNotificationService::class)->sendToTopic(
        'audit-updates', 'Test title', 'Test body', ['event' => 'updated'],
    );
});

it('updates only the supplied system parameter and returns that model', function (): void {
    Schema::create('systemparameters', function (Blueprint $table): void {
        $table->id();
        $table->string('name');
        $table->timestamps();
        $table->softDeletes();
    });
    $targetId = DB::table('systemparameters')->insertGetId(['name' => 'Original']);
    $otherId = DB::table('systemparameters')->insertGetId(['name' => 'Unchanged']);
    $parameter = SystemParameter::query()->findOrFail($targetId);

    $result = app(SystemParameterService::class)->update($parameter, ['name' => 'Updated']);

    expect($result)->toBe($parameter);
    $this->assertDatabaseHas('systemparameters', ['id' => $targetId, 'name' => 'Updated']);
    $this->assertDatabaseHas('systemparameters', ['id' => $otherId, 'name' => 'Unchanged']);
});

it('preserves the column selector payload after removing the duplicate array key', function (): void {
    Schema::create('systemcolumns', function (Blueprint $table): void {
        $table->id();
        $table->string('tableid');
        $table->string('columnid');
    });
    DB::table('systemcolumns')->insert([
        ['tableid' => 'users', 'columnid' => 'username'],
        ['tableid' => 'other', 'columnid' => 'excluded'],
    ]);

    $response = app(SelectDynamicController::class)->getColumnForTable('users');

    expect($response->getData(true))->toBe([
        ['columnid' => 'Seleccione una opción'],
        ['columnid' => 'username'],
    ]);
});
