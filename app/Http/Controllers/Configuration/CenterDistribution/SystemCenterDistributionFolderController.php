<?php

namespace App\Http\Controllers\Configuration\CenterDistribution;

use App\Http\Controllers\BaseController;
use App\Models\Configuration\CenterDistribution\SystemCenterDistribution;
use App\Models\Configuration\Parameters\SystemDetailParameter;
use Illuminate\Http\Request;
use Storage;

class SystemCenterDistributionFolderController extends BaseController
{

    public function index($id)
    {
        $this->authorize('systemcenterdistributions.folder');
        $center = SystemCenterDistribution::findOrFail($id);
        $folders = $this->getDetailParameters();
        $directories = Storage::directories('public/' . $id);
        return view('Configuration.SystemCenterDistributions.Folders.create',
            compact('center', 'folders', 'directories'));
    }

    public function systemcenterdistributionfolder(Request $request, $id)
    {
        $folders = $request['folder'];
        if (!empty($folders)) {
            $this->createDirectories($id, $folders);
        }
        return $this->redirectBackSuccessMessage('Directorios agregados con exito.');
    }

    private function createDirectories($id, array $folders)
    {
        foreach ($folders as $folder) {
            Storage::makeDirectory("public/$id/$folder");
        }
    }

    public function getDetailParameters()
    {
        return SystemDetailParameter::where('parameterid', '=', 'FOLDERS')
            ->select(['id', 'value01', 'value02'])->get();
    }


}
