<?php

namespace App\Http\Controllers\Security\Fuctions;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Security\Funciones\CreateFunction;
use App\Models\Security\Fuctions\SecurityFunction;
use App\Models\Security\Fuctions\SecurityFunctionsMenuStructure;
use App\Models\Security\Modules\SecurityModule;
use App\Models\Security\Permission\Permission;
use Illuminate\Http\Request;

class SecurityFunctionsController extends BaseController
{
    public function index()
    {
        $this->authorize('securityfunctions.index');
        $modules = SecurityModule::select(['description', 'moduleid'])->get();

        return view('Security.SecurtiyFunctions.index', compact('modules'));
    }

    public function getDataFunctions(Request $request)
    {
        $data = SecurityFunction::with('module')
            ->Description($request->get('description'))
            ->Module($request->get('moduleid'));

        return $this->showDataToView($data, 'Security.SecurtiyFunctions.partials.buttons');
    }

    public function create()
    {
        $this->authorize('securityfunctions.create');
        $modules = SecurityModule::select(['description', 'moduleid'])->get();

        return view('Security.SecurtiyFunctions.create', compact('modules'));
    }

    public function store(CreateFunction $request)
    {
        $function = new SecurityFunction($request->all());
        $function->save();

        return redirect('securityfunctions/' . $function->functionid . '/edit');
    }

    public function edit(SecurityFunction $securityfunction)
    {
        $this->authorize('securityfunctions.edit');
        $modules = SecurityModule::select(['description', 'moduleid'])->get();
        $permissions = Permission::where('functionid', $securityfunction->functionid)
            ->select(['description', 'id', 'name', 'category', 'moduleid', 'functionid', 'guard_name'])->get();

        return view(
            'Security.SecurtiyFunctions.edit',
            compact('securityfunction', 'modules', 'permissions')
        );
    }

    public function update(Request $request, SecurityFunction $securityfunction)
    {
        $securityfunction->update($request->all());
        $routes = $request->input('route');
        if ($routes !== null) {
            foreach ($routes as $route) {
                $description = $this->getPermissionDescription($route);
                Permission::create([
                    'name' => $request['name'] . '.' . $route,
                    'description' => $description,
                    'moduleid' => $securityfunction->moduleid,
                    'functionid' => $securityfunction->functionid,
                    'guard_name' => $request['typeid'],
                ]);
            }
        }

        return $this->redirectBackInfo();
    }

    public function destroy($id)
    {
        SecurityFunctionsMenuStructure::where('functionid', $id)->delete();
        Permission::where('functionid', $id)->delete();
        SecurityFunction::destroy($id);

        return $this->redirectBackSuccess();
    }

    private function getPermissionDescription(string $route): string
    {
        $descriptions = [
            'index' => 'Listar',
            'create' => 'Agregar',
            'show' => 'Detalle',
            'edit' => 'Modificar',
            'destroy' => 'Eliminar',
        ];

        return $descriptions[$route] ?? '';
    }
}
