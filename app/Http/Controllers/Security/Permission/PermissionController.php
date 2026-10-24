<?php

namespace App\Http\Controllers\Security\Permission;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Security\Permission\CreatePermissionRequest;
use App\Models\Security\Fuctions\SecurityFunction;
use App\Models\Security\Modules\SecurityModule;
use App\Models\Security\Permission\Permission;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class PermissionController extends BaseController
{
    public function index()
    {
        $this->authorize('securitypermissions.index');
        $modules = SecurityModule::select(['description', 'moduleid'])->get();

        return view('Security.Permissions.index', compact('modules'));
    }

    public function getDataPermissions(Request $request)
    {
        // Generar clave única basada en los parámetros de filtrado
        $cacheKey = 'permissions_data_'.md5(json_encode([
            'moduleid' => $request->get('moduleid'),
            'functionid' => $request->get('functionid'),
            'name' => $request->get('name'),
            'description' => $request->get('description'),
        ]));

        // Cache por 1 hora (3600 segundos)
        // Con serverSide: false, cacheamos toda la colección
        $cacheDriver = config('cache.default');

        // Usar tags solo si el driver lo soporta (Redis/Memcached)
        // Para file/database cache, usar remember simple sin tags
        $data = in_array($cacheDriver, ['redis', 'memcached'])
            ? Cache::tags(['permissions'])->remember($cacheKey, 3600, function () use ($request) {
                return $this->getPermissionsData($request);
            })
            : Cache::remember($cacheKey, 3600, function () use ($request) {
                return $this->getPermissionsData($request);
            });

        // Devolver JSON simple para DataTables con serverSide: false
        return response()->json(['data' => $data]);
    }

    public function create()
    {
        $this->authorize('securitypermissions.create');
        $modules = SecurityModule::select(['description', 'moduleid'])->get();

        return view('Security.Permissions.create', compact('modules'));
    }

    public function store(CreatePermissionRequest $request): RedirectResponse
    {
        Permission::create($request->validated() + ['guard_name' => 'web']);

        return $this->redirectBackSuccess();
    }

    public function edit(Permission $securitypermission)
    {
        $this->authorize('securitypermissions.edit');
        $modules = SecurityModule::select(['description', 'moduleid'])->get();
        $functions = SecurityFunction::select(['description', 'functionid'])->get();

        return view(
            'Security.Permissions.edit',
            compact('securitypermission', 'functions', 'modules'),
        );
    }

    public function update(Request $request, Permission $securitypermission)
    {
        $securitypermission->update($request->all());

        return $this->redirectBackSuccess();
    }

    public function destroy($id)
    {
        $this->authorize('securitypermissions.destroy');
        Permission::destroy($id);

        return $this->redirectBackSuccessMessage('Registro eliminado con exito!!');
    }

    /**
     * Get permissions data with joins and button rendering
     */
    private function getPermissionsData(Request $request)
    {
        return Permission::leftJoin('securityfunctions', 'securityfunctions.functionid', '=', 'permissions.functionid')
            ->leftJoin('securitymodules', 'securitymodules.moduleid', '=', 'securityfunctions.moduleid')
            ->ForModule($request->get('moduleid'))
            ->ForFunction($request->get('functionid'))
            ->Name($request->get('name'))
            ->Description($request->get('description'))
            ->select([
                'permissions.*',
                'securitymodules.description as module',
                'securityfunctions.description as function',
            ])
            ->get()
            ->map(function ($permission) {
                // Agregar botón de acciones a cada registro
                $id = $permission->id;
                $name = $permission->name;
                $permission->btn = view('Security.Permissions.partials.buttons2', compact('id', 'name'))->render();

                return $permission;
            });
    }

    public function show(string $securitypermission) {}
}
