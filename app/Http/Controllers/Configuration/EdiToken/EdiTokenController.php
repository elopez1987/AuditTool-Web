<?php

namespace App\Http\Controllers\Configuration\EdiToken;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Configuration\EdiToken\CreateEdiTokenRequest;
use App\Models\Configuration\EDI\EdiToken;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EdiTokenController extends BaseController
{
    public function index()
    {
        $this->authorize('editokens.index');
        return view('Configuration.EdiToken.index');
    }

    public function geteditokens(Request $request)
    {
        $data = EdiToken::get();
        return $this->showDataToView($data, 'Configuration.EdiToken.partials.buttons');
    }

    public function create()
    {
        $this->authorize('editokens.create');
        return view('Configuration.EdiToken.create');
    }

    public function store(CreateEdiTokenRequest $request)
    {
        EdiToken::create($request->validated());
        return $this->redirectBackSuccessMessage('Token registrado con exito.');
    }


    public function edit($id)
    {
        $this->authorize('editokens.edit');
        $token = EdiToken::findOrFail($id);
        return view('Configuration.EdiToken.edit', compact('token'));
    }

    public function update(Request $request, $id)
    {
        $token = EdiToken::findOrFail($id);
        $token->update($request->all());
        return $this->redirectBackSuccessMessage('Token actualizado con exito.');
    }

    public function destroytoken(Request $request)
    {
        $this->authorize('editokens.destroy');
        $token = EdiToken::findOrFail($request->id);
        $token->delete();
        return response()->json([
            'ProcessDate' => Carbon::now()->format('Y-m-d H:m:s'),
            'Code' => '0',
            'Message' => 'Registro eliminado con exito.',
        ], '200');
    }
}
