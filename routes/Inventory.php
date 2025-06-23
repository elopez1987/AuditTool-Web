<?php

use App\Http\Controllers\Counting\StorageCountDronHeadController;
use App\Http\Controllers\Inventory\InventoryPartNumberCategory01sController;
use App\Http\Controllers\Inventory\InventoryPartNumberCategory02sController;
use App\Http\Controllers\Inventory\InventoryPartNumbers\ImportPartNumbersController;
use App\Http\Controllers\Inventory\InventoryPartNumbers\InventoryPartnumbersController;
use App\Http\Controllers\Inventory\InventoryPartNumbers\ParameterPartNumberController;
use App\Http\Controllers\Inventory\InventoryStockCategoriesController;
use App\Http\Controllers\Inventory\InventoryUnitMeasureController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {

    Route::get('menuinventory', function () {
        return view('layouts.Menu.Inventory.menuinventory');
    })->name('menuinventory');

    //Categoria de Inventario 01
    Route::resource('inventorypartnumbercategory01',InventoryPartNumberCategory01sController::class)->except('destroy');
    Route::get('getDataCategory01',[InventoryPartNumberCategory01sController::class,'getDataCategory01'])->name('getDataCategory01');

    //Categoria de Inventario 02
    Route::resource('inventorypartnumbercategory02',InventoryPartNumberCategory02sController::class)->except('destroy');
    Route::get('getDataCategory02',[InventoryPartNumberCategory02sController::class,'getDataCategory02'])->name('getDataCategory02');

//    Unidades de medida
    Route::resource('inventoryunitmeasure',InventoryUnitMeasureController::class)->except('show');
    Route::get('getInventoryUnits',[InventoryUnitMeasureController::class,'getInventoryUnits'])->name('getInventoryUnits');

    //Categoria de Inventario 02
    Route::resource('inventorystockcategories',InventoryStockCategoriesController::class)->except('destroy');
    Route::get('getDataStockCategory',[InventoryStockCategoriesController::class,'getDataStockCategory'])->name('getDataStockCategory');

    //Maestro de materiales
    Route::resource('inventorypartnumbers', InventoryPartnumbersController::class)->except('destroy');
    Route::get('getDataInventoryNumbers',[InventoryPartnumbersController::class,'getDataInventoryNumbers'])->name('getDataInventoryNumbers');
//    Importar maestro de materiales
    Route::post('uploadfilepartnumbers',[ImportPartNumbersController::class,'importPartNumber'])->name('uploadfilepartnumbers');
//    Parametrizar Skus
    Route::get('inventorypartnumbers/{id}/parameters', [ParameterPartNumberController::class, 'parameters'])->name('inventorypartnumbers.parameters');
    Route::put('inventorypartnumbersaddparameters/{id}', [ParameterPartNumberController::class, 'addparameters'])->name('addparameters');


    Route::resource('storagecountdronhead',StorageCountDronHeadController::class)->only('index', 'show');
    Route::get('getInventoryHeadDron',[StorageCountDronHeadController::class,'getInventoryHeadDron'])->name('getInventoryHeadDron');

    Route::get('getinventoryheaddrondetail',[StorageCountDronHeadController::class,'getInventoryHeadDronDetail'])->name('getinventoryheaddrondetail');
});
