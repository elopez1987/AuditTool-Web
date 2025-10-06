<?php

namespace App\Http\Controllers\Configuration\Folios;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Folios\SystemFolio;
use Illuminate\Http\Request;

class SystemFoliosController extends BaseController
{
    public function index()
    {
        $this->authorize('systemfolios.index');

        return view('Configuration.SystemFolios.index');
    }

    public function getdatafolios(Request $request)
    {
        $data = SystemFolio::controlAccessUser()
            ->with([
                'status' => function ($q) {
                    $q->select('statusid', 'name');
                },
                'center' => function ($q) {
                    $q->select('centerid', 'name');
                },
            ])
            ->Description($request->get('description'))
            ->select([
                'systemfolio.*',
            ]);

        return $this->showDataToView($data, 'Configuration.SystemFolios.partials.buttons');
    }

    public function create()
    {
        $this->authorize('systemfolios.create');
        $status = $this->getStatusForSchema('FOL');
        $centers = $this->getCenterForSelect();

        return view(
            'Configuration.SystemFolios.create',
            compact('centers', 'status')
        );
    }

    public function store(Request $request)
    {
        $folio = new SystemFolio($request->all());
        $folio->save();

        return $this->redirectBackSuccess();
    }

    public function edit(SystemFolio $systemfolio)
    {
        $this->authorize('systemfolios.edit');

        return view('Configuration.SystemFolios.edit', compact('systemfolio'));
    }

    public function update(Request $request, SystemFolio $systemfolio)
    {
        $systemfolio->update($request->all());

        return $this->redirectBackSuccess();
    }
}
