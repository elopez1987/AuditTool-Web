<?php

namespace App\Http\Controllers\Configuration\Printers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\Printer\CreatePrinter;
use App\Models\Configuration\Printers\SystemPrinter;
use Illuminate\Http\Request;

class SystemPrintersController extends BaseController
{
    public function index()
    {
        $this->authorize('systemprinters.index');

        return view('Configuration.SystemPrinters.index');
    }

    public function getDataPrinters()
    {
        $data = SystemPrinter::controlAccessUser()
            ->with([
                'status' => function ($q) {
                    $q->select('statusid', 'name');
                },
                'center' => function ($q) {
                    $q->select('centerid', 'name');
                },
            ])
            ->select('systemprinters.*');

        return $this->showDataToView($data, 'Configuration.SystemPrinters.partials.buttons');
    }

    public function create()
    {
        $this->authorize('systemprinters.create');
        $centers = $this->getCenterForSelect();

        return view('Configuration.SystemPrinters.create', compact('centers'));
    }

    public function store(CreatePrinter $request)
    {
        $printer = new SystemPrinter($request->validated());
        $printer->save();

        return $this->redirectBackSuccess();
    }

    public function edit(SystemPrinter $systemprinter)
    {
        $this->authorize('systemprinters.edit');
        $status = $this->getStatusForSchema('STS');

        return view('Configuration.SystemPrinters.edit', compact('systemprinter', 'status'));
    }

    public function update(Request $request, SystemPrinter $systemprinter)
    {
        $systemprinter->update($request->all());

        return $this->redirectBackInfo();
    }
}
