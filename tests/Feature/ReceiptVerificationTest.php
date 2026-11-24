<?php

declare(strict_types=1);

use App\Models\Receipt\ReceiptVerification;
use Illuminate\Support\Facades\Auth;

it('assigns the authenticated user when creating a receipt verification outside the console', function (): void {
    $isRunningInConsole = $this->app->runningInConsole();
    $consoleProperty = new ReflectionProperty($this->app, 'isRunningInConsole');
    $consoleProperty->setValue($this->app, false);
    ReceiptVerification::clearBootedModels();

    try {
        Auth::shouldReceive('id')->once()->andReturn(2);
        $verification = new ReceiptVerification;

        ReceiptVerification::getEventDispatcher()->until(
            'eloquent.creating: '.ReceiptVerification::class,
            $verification,
        );

        expect($verification->created_by)->toBe(2);
    } finally {
        $consoleProperty->setValue($this->app, $isRunningInConsole);
        ReceiptVerification::clearBootedModels();
    }
});
