<?php

namespace App\Http\Controllers\Maintanance;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\Schemas\SystemStatus;
use App\Traits\Web\General\Responser;
use Illuminate\Http\Request;

class StorageCatalogTruckController extends BaseController
{
    use Responser;

    public function index()
    {
        $this->authorize('storagelifttruckscatalogs');
        $marcas = SystemStatus::where('schemaid', 'MCM')->get();
        $modelos = SystemStatus::where('schemaid', 'MDM')->get();
        $types = SystemStatus::where('schemaid', 'TPM')->get();

        return view('Maintenance.Catalogs.index', compact('marcas', 'modelos', 'types'));
    }

    public function savebrand(Request $request)
    {
        $total = SystemStatus::where('schemaid', 'MCM')->count();
        $total = $total < 10 ? str_pad($total + 1, 2, '0', STR_PAD_LEFT) : $total + 1;
        SystemStatus::create([
            'schemaid' => 'MCM',
            'name' => $request['namemarca'],
            'statusid' => 'MC' . '-' . $total,
        ]);

        return $this->redirectBackSuccess();
    }

    public function savemodel(Request $request)
    {
        $total = SystemStatus::where('schemaid', 'MDM')->count();
        $total = $total < 10 ? str_pad($total + 1, 2, '0', STR_PAD_LEFT) : $total + 1;
        SystemStatus::create([
            'schemaid' => 'MDM',
            'name' => $request['namemodel'],
            'statusid' => 'MD' . '-' . $total,
        ]);

        return $this->redirectBackSuccess();
    }

    public function savetype(Request $request)
    {
        $total = SystemStatus::where('schemaid', 'TPM')->count();
        $total = $total < 10 ? str_pad($total + 1, 2, '0', STR_PAD_LEFT) : $total + 1;
        SystemStatus::create([
            'schemaid' => 'TPM',
            'name' => $request['nametype'],
            'statusid' => "MT-{$total}",
        ]);

        return $this->redirectBackSuccess();
    }
}
