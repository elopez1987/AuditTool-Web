<?php

use App\Http\Controllers\Security\Activos\ActiveUsersController;
use App\Http\Controllers\Security\Fuctions\CreateNewPermissionController;
use App\Http\Controllers\Security\Fuctions\SecurityFunctionsController;
use App\Http\Controllers\Security\Fuctions\SecurityFunctionsMenuController;
use App\Http\Controllers\Security\Logs\LogsLoginController;
use App\Http\Controllers\Security\Logs\LogSuspiciousController;
use App\Http\Controllers\Security\MenuStructure\SecurityDetailMenuController;
use App\Http\Controllers\Security\MenuStructure\SecurityMenuController;
use App\Http\Controllers\Security\Modules\SecurityModulesController;
use App\Http\Controllers\Security\Okta\OktaController;
use App\Http\Controllers\Security\Permission\PermissionController;
use App\Http\Controllers\Security\Roles\ParameterRoleController;
use App\Http\Controllers\Security\Roles\RoleController;
use App\Http\Controllers\Security\Roles\StorageCategoryRoleController;
use App\Http\Controllers\Security\Users\AccessWarehouse\AccessCarrierController;
use App\Http\Controllers\Security\Users\AccessWarehouse\AccessCustomerController;
use App\Http\Controllers\Security\Users\AccessWarehouse\AccessProviderController;
use App\Http\Controllers\Security\Users\AccessWarehouse\LevelWarehouseController;
use App\Http\Controllers\Security\Users\Eventos\EventUserController;
use App\Http\Controllers\Security\Users\SuperUserController;
use App\Http\Controllers\Security\Users\UpdateUserControlller;
use App\Http\Controllers\Security\Users\UserController;
use App\Http\Controllers\Security\Users\UsersForTRansportController;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->group(function () {
    Route::get('menusecurity', function () {
        return view('layouts.Menu.Security.menusecurity');
    })->name('menusecurity');

    //    ----------------------------------------------- Modulo de usuarios---------------------------------------------------------------------
    Route::resource('securityusers', UserController::class)->except('show');
    Route::get('getDataUsers', [UserController::class, 'getDataUsers'])->name('getDataUsers');
    //    Enviar correo de reset de contraseña
    Route::post('securityusers/{uuiduser}/send-password-reset', [UserController::class, 'sendPasswordReset'])->name('securityusers.sendPasswordReset');
    //    Perfil del usuario
    //    Route::get('profile/{id}',[UpdateUserControlller::class,'profile'])->name('profile');
    Route::put('updateprofile/{id}', [UpdateUserControlller::class, 'updateprofile'])->name('updateprofile');
    //    ----------------------------------------------- Eventos de usuarios---------------------------------------------------------------------
    //  Restaurar usuarios eliminados
    Route::put('restoreuser/{id}', [EventUserController::class, 'restoreuser'])->name('restoreuser');

    // //Crear usuario OKTA
    Route::put('createOktaUser/{id}', [OktaController::class, 'createOktaUser'])->name('createOktaUser');
    Route::put('deleteuser/{id}', [OktaController::class, 'deleteuser'])->name('deleteuser');

    //    Importar usuarios masivos
    Route::post('importusers', [EventUserController::class, 'importusers'])->name('importusers');
    //    Parametrizar usuarios
    Route::get('securityusers/{id}/parameter', [EventUserController::class, 'parameter'])->name('securityuser.parameter');
    Route::put('securityusersparameterstore/{id}', [EventUserController::class, 'securityusersparameterstore'])->name('securityusersparameterstore');

    //    ----------------------------------------------- Accesos a bodegade de usuarios---------------------------------------------------------------------
    //    Asignacion de bodegas para acceso
    Route::get('securityusers/{id}/securityaccesswarehouse', [LevelWarehouseController::class, 'createaccess'])->name('securityusers.createaccess');
    Route::put('storeLevelWarehouse/{id}', [LevelWarehouseController::class, 'storeLevelWarehouse'])->name('storeLevelWarehouse');

    //    Acceso por líneas de transporte
    Route::get('securityusers/{id}/securityaccesscarriers', [AccessCarrierController::class, 'index'])->name('securityusers.accesscarriers');
    Route::put('securityuseraddcarrier/{id}', [AccessCarrierController::class, 'update'])->name('securityuseraddcarrier');
    //    Borrar asignacion de línea de transporte
    Route::delete('deleteaccesscarrier/{id}', [AccessCarrierController::class, 'destroy'])->name('deleteaccesscarrier');

    //    Acceso para filtro por clientes
    Route::get('securityusers/{id}/securityaccesscustomer', [AccessCustomerController::class, 'index'])->name('securityuser.securityaccesscustomer');
    Route::put('securityaccesscustomer/{id}', [AccessCustomerController::class, 'update'])->name('securityaccesscustomer');
    //    Borrar asignacion de clientes a usuarios
    Route::delete('deleteaccesscustomer/{id}', [AccessCustomerController::class, 'destroy'])->name('deleteaccesscustomer');

    //    Acceso para filtro por proveedores
    Route::get('securityusers/{id}/providers', [AccessProviderController::class, 'index'])->name('securityuser.providers');
    Route::put('securityaccess-providers/{id}', [AccessProviderController::class, 'update'])->name('securityaccess-providers');
    //    Borrar asignacion de clientes a usuarios
    Route::delete('deleteaccess-providers/{id}', [AccessProviderController::class, 'destroy'])->name('deleteaccess-providers');

    //    ----------------------------------------------- Roles---------------------------------------------------------------------
    //    Perfiles de usuario
    Route::resource('securityroles', RoleController::class)->except('show');
    Route::get('getDataRoles', [RoleController::class, 'getDataRoles'])->name('getDataRoles');
    Route::put('updatePermissionToRole/{securityrole}', [RoleController::class, 'updatePermissionToRole'])->name('updatePermissionToRole');
    //    Categorias de conceptos de ajuste de roles
    Route::get('securityroles/{id}/conceptajust', [StorageCategoryRoleController::class, 'conceptajust'])->name('securityroles.conceptajust');
    Route::post('saveconceptajust/{id}', [StorageCategoryRoleController::class, 'saveconceptajust'])->name('saveconceptajust');
    Route::delete('destroycategoryrole/{id}', [StorageCategoryRoleController::class, 'destroy'])->name('destroycategoryrole');
    //	  Parametrizar roles
    Route::get('securityroles/{id}/parameters', [ParameterRoleController::class, 'parameters'])->name('roles.parameters');
    Route::put('securityroles-parameters/{id}', [ParameterRoleController::class, 'store'])->name('roles.parameters');

    //    Crear un permiso
    Route::put('createNewPermissionForFunction/{securityFunction}', [CreateNewPermissionController::class, 'createNewPermissionForFunction'])->name('createNewPermissionForFunction');
});

Route::group(['middleware' => ['role:ADMINISTRADOR']], function () {
    //    /    ---------------------------------------------- Modulos---------------------------------------------------------------------
    Route::resource('securitymodules', SecurityModulesController::class)->names('securitymodules')->except('show');
    Route::get('getDataModules', [SecurityModulesController::class, 'getDataModules'])->name('getDataModules');
    //    ----------------------------------------------- Funciones---------------------------------------------------------------------
    Route::resource('securityfunctions', SecurityFunctionsController::class)->names('securityfunctions')->except('show');
    Route::get('getDataFunctions', [SecurityFunctionsController::class, 'getDataFunctions'])->name('getDataFunctions');
    //    ----------------------------------------------- Permisos---------------------------------------------------------------------
    Route::resource('securitypermissions', PermissionController::class)->names('securitypermissions');
    Route::get('getDataPermissions', [PermissionController::class, 'getDataPermissions'])->name('getDataPermission');

    //    Estructura de menu
    Route::resource('securitymenustructure', SecurityMenuController::class)->except('show');
    Route::get('getmenustructure', [SecurityMenuController::class, 'getmenustructure'])->name('getmenustructure');

    //    Detalle de modulos sobre menu
    Route::put('savedatailmenustructure/{securitymenustructure}', [SecurityDetailMenuController::class, 'savedatailmenustructure'])->name('savedatailmenustructure');
    Route::post('destroyDetailMenu', [SecurityDetailMenuController::class, 'destroyDetailMenu'])->name('destroyDetailMenu');

    //    Detalle de funciones sobre menu
    Route::put('savefunctionsformenustructure/{securitymenustructure}', [SecurityFunctionsMenuController::class, 'savefunctionsformenustructure'])->name('savefunctionsformenustructure');
    Route::post('deletefunctionformenu', [SecurityFunctionsMenuController::class, 'deletefunctionformenu'])->name('deletefunctionformenu');

    //    Super Usuarios
    Route::resource('superusers', SuperUserController::class)->except(['show', 'edit', 'update']);

    //	Logs de inicio de sesión
    Route::get('logauthentication', [LogsLoginController::class, 'logauthentication'])->name('logauthentication');
    Route::get('logsloginAuthentication', [LogsLoginController::class, 'logsloginAuthentication'])->name('logsloginAuthentication');

    Route::get('logsuspicious', [LogSuspiciousController::class, 'logsuspicious'])->name('logsuspicious');
    Route::get('getLogsuspicious', [LogSuspiciousController::class, 'getLogsuspicious'])->name('getLogsuspicious');

    //	Usuarios por linea de transporte
    Route::get('usersfortransports', [UsersForTRansportController::class, 'index'])->name('usersfortransports');
    Route::get('usersfortransportdata', [UsersForTRansportController::class, 'usersfortransportdata'])->name('usersfortransportdata');

    //	Usuarios activo
    Route::resource('activeusers', ActiveUsersController::class);
    Route::get('getactiveusers', [ActiveUsersController::class, 'getactiveusers'])->name('getactiveusers');
    Route::get('clearsessions', [ActiveUsersController::class, 'clearsessions'])->name('clearsessions');

});
