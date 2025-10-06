<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('menumaintenance', function () {
        return view('layouts.Menu.Maintenance.menu');
    })->name('menumaintenance');

    Route::resource('storagelifttrucks', 'Maintanance\StorageLiftTruckController');
    Route::get('getStorageLiftTrucks', 'Maintanance\StorageLiftTruckController@getStorageLiftTrucks')->name('getStorageLiftTrucks');

    Route::resource('storagechecklistlifttrucks', 'Maintanance\StorageCheckListLiftTruckController');
    Route::get('getDataCheckListLiftTrucks', 'Maintanance\StorageCheckListLiftTruckController@getDataCheckListLiftTrucks')->name('getDataCheckListLiftTrucks');
    //    Detalle del check list
    Route::get('addchecklistdetailslifttruck/{id}', 'Maintanance\StorageCheckListDetailLiftTruckController@index')->name('addchecklistdetailslifttruck');
    Route::put('storedetailslifttruck/{id}', 'Maintanance\StorageCheckListDetailLiftTruckController@store')->name('storedetailslifttruck.store');
    Route::put('updatestatusdetaillifttruck/{id}', 'Maintanance\StorageCheckListDetailLiftTruckController@updatestatusdetaillifttruck')->name('updatestatusdetaillifttruck');

    //    Borrar detalle de check list de montacargas
    Route::post('deletedetailchecklistlifttrucks/{id}', 'Maintanance\StorageCheckListDetailLiftTruckController@deletedetailchecklistlifttrucks')->name('deletedetailchecklistlifttrucks');
    //    Actualizar detalle de check list de montacargas
    Route::put('updatestatusdetaillifttruckedit/{id}', 'Maintanance\StorageCheckListDetailLiftTruckController@updatestatusdetaillifttruckedit')->name('updatestatusdetaillifttruckedit');
    Route::resource('storageassignlifttrucks', 'Maintanance\StorageAssignLiftTruckController');
    Route::post('deleteUserAssingTruck', 'Maintanance\StorageAssignLiftTruckController@deleteUserAssingTruck')->name('deleteUserAssingTruck');

    Route::resource('checklistlifttrucks', 'Maintanance\CheckListLiftTruckController');
    Route::get('getCheckListTruck', 'Maintanance\CheckListLiftTruckController@getCheckListTruck')->name('getCheckListTruck');
    Route::get('printFormatToCheckList/{id}', 'Maintanance\CheckListLiftTruckController@printFormatToCheckList')->name('printFormatToCheckList');
    Route::get('dashboardtrucks', 'Maintanance\DashboardController@index')->name('dashboardtrucks');
    Route::get('getTruckAssigned', 'Maintanance\DashboardController@getTruckAssigned')->name('getTruckAssigned');
    Route::get('storagelifttrucksreports', 'Maintanance\DashboardController@storagelifttrucksreports')->name('storagelifttrucksreports');
    Route::get('exportreportmtn', 'Maintanance\DashboardController@exportreportmtn')->name('exportreportmtn');

    Route::get('storagelifttruckscatalogs', 'Maintanance\StorageCatalogTruckController@index')->name('storagelifttruckscatalogs');
    Route::post('savebrand', 'Maintanance\StorageCatalogTruckController@savebrand')->name('savebrand');
    Route::post('savemodel', 'Maintanance\StorageCatalogTruckController@savemodel')->name('savemodel');
    Route::post('savetype', 'Maintanance\StorageCatalogTruckController@savetype')->name('savetype');
});
