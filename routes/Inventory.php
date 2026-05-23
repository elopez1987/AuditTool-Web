<?php

use App\Http\Controllers\Inventory\InventoryFiscal\InventoryAisleController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryAreaController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryCardController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryHeadController;
use App\Http\Controllers\Inventory\InventoryFiscal\InventoryLocationController;
use App\Http\Controllers\Inventory\InventoryPartNumberCategory01sController;
use App\Http\Controllers\Inventory\InventoryPartNumberCategory02sController;
use App\Http\Controllers\Inventory\InventoryPartNumbers\ImportPartNumbersController;
use App\Http\Controllers\Inventory\InventoryPartNumbers\InventoryPartNumberData01Controller;
use App\Http\Controllers\Inventory\InventoryPartNumbers\InventoryPartnumbersController;
use App\Http\Controllers\Inventory\InventoryPartNumbers\ParameterPartNumberController;
use App\Http\Controllers\Inventory\InventoryStockCategoriesController;
use App\Http\Controllers\Inventory\InventoryUnitMeasureController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('menuinventory', function () {
        return view('layouts.Menu.Inventory.menuinventory');
    })->name('menuinventory');

    // Categoria de Inventario 01
    Route::resource('inventorypartnumbercategory01', InventoryPartNumberCategory01sController::class)->except('destroy');
    Route::get('getDataCategory01', [InventoryPartNumberCategory01sController::class, 'getDataCategory01'])->name('getDataCategory01');

    // Categoria de Inventario 02
    Route::resource('inventorypartnumbercategory02', InventoryPartNumberCategory02sController::class)->except('destroy');
    Route::get('getDataCategory02', [InventoryPartNumberCategory02sController::class, 'getDataCategory02'])->name('getDataCategory02');

    //    Unidades de medida
    Route::resource('inventoryunitmeasure', InventoryUnitMeasureController::class)->except('show');
    Route::get('getInventoryUnits', [InventoryUnitMeasureController::class, 'getInventoryUnits'])->name('getInventoryUnits');

    // Categoria de Inventario 02
    Route::resource('inventorystockcategories', InventoryStockCategoriesController::class)->except('destroy');
    Route::get('getDataStockCategory', [InventoryStockCategoriesController::class, 'getDataStockCategory'])->name('getDataStockCategory');

    // Maestro de materiales
    Route::resource('inventorypartnumbers', InventoryPartnumbersController::class)->except('destroy');
    Route::get('getDataInventoryNumbers', [InventoryPartnumbersController::class, 'getDataInventoryNumbers'])->name('getDataInventoryNumbers');
    //    Importar maestro de materiales
    Route::post('uploadfilepartnumbers', [ImportPartNumbersController::class, 'importPartNumber'])->name('uploadfilepartnumbers');
    //    Parametrizar Skus
    Route::get('inventorypartnumbers/{id}/parameters', [ParameterPartNumberController::class, 'parameters'])->name('inventorypartnumbers.parameters');
    Route::put('inventorypartnumbersaddparameters/{id}', [ParameterPartNumberController::class, 'addparameters'])->name('addparametersinventory');

    //	Agregar informacion adicional para Garrett
    Route::get('inventorypartnumbers/{id}/adddata01', [InventoryPartNumberData01Controller::class, 'index'])->name('inventorypartnumbers.adddata01');
    Route::put('inventorypartnumbersadddata01/{id}', [InventoryPartNumberData01Controller::class, 'store'])->name('inventorypartnumbersadddata01');

    //	Inventario Fiscal
    Route::resource('inventoryareas', InventoryAreaController::class)->except('show');
    Route::get('getInventoryArea', [InventoryAreaController::class, 'getInventoryArea'])->name('getInventoryArea');

    Route::resource('inventoryaisles', InventoryAisleController::class)->except('show');
    Route::get('getInventoryAisle', [InventoryAisleController::class, 'getInventoryAisle'])->name('getInventoryAisle');

    Route::resource('inventorycards', InventoryCardController::class);
    Route::get('getInventoryCard', [InventoryCardController::class, 'getInventoryCard'])->name('getInventoryCard');

    Route::resource('inventorylocations', InventoryLocationController::class);
    Route::put('create-location/{id}', [InventoryLocationController::class, 'createLocation'])->name('create-location');
    Route::get('getLocationForCard/{id}', [InventoryLocationController::class, 'getLocationForCard'])->name('getLocationForCard');

    Route::resource('inventoryheads', InventoryHeadController::class)->except('show');
    Route::get('getInventoryHead', [InventoryHeadController::class, 'getInventoryHead'])->name('getInventoryHead');
    //	Route::get('getInventoryHeadForCard/{id}', [InventoryHeadController::class, 'getInventoryHeadForCard'])->name('getInventoryHeadForCard');
    //	Route::get('getInventoryHeadForCard/{id}/adddata01', [InventoryHeadController::class, 'getInventoryHeadForCardData01'])->name('getInventoryHeadForCard.adddata01');
    //	Route::put('inventoryheadadddata01/{id}', [InventoryHeadController::class, 'addData01'])->name('inventoryheadadddata01');

});
