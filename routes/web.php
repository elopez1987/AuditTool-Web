<?php

use Illuminate\Support\Facades\Route;


//Route::get('/', function () {
////    return view('auth.login');
//});


//Auth::routes();
Route::get('/home', 'HomeController@index')->name('home');
Route::view('/politicaprivacidad', 'auth.policy')->name('politicaprivacidad');


Route::middleware(['auth'])->group(function () {
    Route::get('logout', 'HomeController@logout')->name('logout');
    Route::post('keep-token-alive', function () {
        return 'El token debe haber sido válido y el vencimiento de la sesión se ha extendido.';
    });
    Route::get('getDataStatusShipment', 'HomeController@getDataStatusShipment')->name('getDataStatusShipment');
});

