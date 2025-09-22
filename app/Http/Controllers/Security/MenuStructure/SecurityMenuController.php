<?php

namespace App\Http\Controllers\Security\MenuStructure;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Security\Menus\CreateNewMenuRequest;
use App\Models\Security\MenuStructure\SecurityMenu;
use App\Traits\Web\Security\QueryMenuStructure;
use Illuminate\Http\Request;

class SecurityMenuController extends BaseController
{
    use QueryMenuStructure;

    public function index()
    {
        $this->authorize('securitymenustructure.index');
        return view('Security.SecurityMenu.index');
    }

    public function getmenustructure()
    {
        $data = $this->getAllMenuStructure();
        return $this->showDataToView($data, 'Security.SecurityMenu.partials.buttons');
    }

    public function create()
    {
        $this->authorize('securitymenustructure.create');
        $centers = $this->getCenterForSelect();
        return view('Security.SecurityMenu.create', compact('centers'));
    }

    public function store(CreateNewMenuRequest $request)
    {
        SecurityMenu::create($request->validated());
        return $this->redirectBackSuccessMessage('Registro agregado con exito.');
    }

    public function edit(SecurityMenu $securitymenustructure)
    {
        $this->authorize('securitymenustructure.edit');
        $status = $this->getStatusForSchema('STS');
        $modules = $this->getModulesForMenu($securitymenustructure);
        $modulesDetails = $securitymenustructure->moduleDetails()->get();
        $functions = $this->getFuctionsForMenu($securitymenustructure);
        $functionsDetails = $securitymenustructure->functiondDetails()->get();
        return view(
            'Security.SecurityMenu.edit',
            compact('securitymenustructure', 'modules', 'modulesDetails', 'status', 'functions', 'functionsDetails')
        );
    }

    public function update(Request $request, SecurityMenu $securitymenustructure)
    {
        $securitymenustructure->update($request->all());
        return $this->redirectBackSuccessMessage('Actualización registrada con exito.');
    }

    public function destroy($id)
    {
        $this->authorize('securitymenustructure.destroy');
        SecurityMenu::controlAccessUser()->where('menustrucid', $id)->delete();
        return $this->redirectBackSuccessMessage('Menu eliminado con exito.');
    }
}
