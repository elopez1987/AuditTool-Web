<?php

namespace App\Http\Controllers\Configuration\Schemas;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\Schemas\CreateSchemaRequest;
use App\Http\Requests\Web\Configuration\Schemas\SearchSchemasRequest;
use App\Models\Configuration\Schemas\SystemSchema;
use App\Services\Configuration\SystemSchemaService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SystemSchemaController extends BaseController
{
    public function __construct(
        private readonly SystemSchemaService $service,
    ) {}

    public function index()
    {
        $this->authorize('systemschemas.index');

        return view('Configuration.SystemSchemas.index');
    }

    public function getDataSchemas(SearchSchemasRequest $request)
    {
        $schemas = $this->service->findAll($request->validated());

        return $this->showDataToView(
            $schemas,
            'Configuration.SystemSchemas.partials.buttons'
        );
    }

    public function create()
    {
        $this->authorize('systemschemas.create');

        return view('Configuration.SystemSchemas.create');
    }

    public function store(CreateSchemaRequest $request): RedirectResponse
    {
        $schema = $this->service->newSchema($request->validated());

        return redirect('systemschemas/' . $schema->schemaid . '/createstatus');
    }

    public function edit(SystemSchema $systemschema)
    {
        $this->authorize('systemschemas.edit');

        return view('Configuration.SystemSchemas.edit', compact('systemschema'));
    }

    public function update(Request $request, SystemSchema $systemschema): RedirectResponse
    {
        $systemschema->update($request->all());

        return $this->infoResponse('systemschemas.index');
    }
}
