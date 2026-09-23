<?php

namespace App\Http\Controllers\Configuration\EdiConfig;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Configuration\EdiConfigRequest;
use App\Models\Configuration\EDI\EdiConfig;
use Illuminate\Http\Request;

class EdiConfigController extends BaseController
{
    public function index()
    {
        $data = EdiConfig::all();

        return view('Configuration.EdiConfig.index', compact('data'));
    }

    public function create()
    {
        return view('Configuration.EdiConfig.create');
    }

    public function store(EdiConfigRequest $request)
    {
        $config = new EdiConfig($request->validated());
        $config->save();

        return $this->redirectBackSuccess();
    }

    public function edit($id)
    {
        $ediConfig = EdiConfig::findOrFail($id);

        return view('Configuration.EdiConfig.edit', compact('ediConfig'));
    }

    public function update(Request $request, EdiConfig $edi_configuration)
    {
        $edi_configuration->update($request->all());

        return $this->redirectBackSuccess();
    }

    public function destroy(EdiConfig $edi_configuration)
    {
        $edi_configuration->delete();

        return $this->redirectBackSuccess();
    }
}
