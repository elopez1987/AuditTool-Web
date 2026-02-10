<?php

use App\Http\Controllers\API\Login\TokenController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:api')->group(function () {});

Route::post('getToken', [TokenController::class, 'getToken']);
