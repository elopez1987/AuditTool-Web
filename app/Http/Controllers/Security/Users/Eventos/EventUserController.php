<?php

namespace App\Http\Controllers\Security\Users\Eventos;

use App\Exports\Security\ExportUsersTemplate;
use App\Http\Controllers\BaseController;
use App\Imports\Security\ImportUsers;
use App\Models\Shipments\OrderDelivery\LogImportFile;
use App\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Throwable;

class EventUserController extends BaseController
{
    public function restoreuser($id)
    {
        $user = User::withTrashed()->where('id', $id)->first();
        $user->statusid = 'E';
        $user->save();
        $user->restore();

        return $this->redirectBackSuccessMessage('Usuario restaurado correctamente');
    }

    public function downloadTemplate(): BinaryFileResponse
    {
        return Excel::download(
            new ExportUsersTemplate,
            'template_import_users.xlsx'
        );
    }

    public function importusers(Request $request): View|RedirectResponse
    {
        ini_set('max_execution_time', 300);

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:xls,xlsx',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse('El formato de archivo no es correcto. Solo se permiten archivos Excel (.xls, .xlsx).');
        }

        try {
            Excel::import(
                new ImportUsers,
                $request->file('file'),
                null,
                \Maatwebsite\Excel\Excel::XLSX
            );
        } catch (Throwable $exception) {
            return $this->errorResponse(
                'No se pudo procesar el archivo de importación: '.$exception->getMessage()
            );
        }

        $data = LogImportFile::query()->orderByDesc('id')->get();

        return view('Configuration.EdiLogs.LogImportFile.index', compact('data'));
    }

    public function parameter($uuiduser)
    {
        $this->authorize('securityusers.parameter');
        $user = User::where('uuiduser', $uuiduser)->withTrashed()->firstOrFail();
        $parameters = $this->getStatusForSchema('USR');

        return view('Security.Users.Parameter.create', compact('user', 'parameters'));
    }

    public function securityusersparameterstore($uuiduser, Request $request)
    {
        $user = User::where('uuiduser', $uuiduser)->withTrashed()->first();
        $user->configurationflag = $request['parameter'];
        $user->save();

        return $this->redirectBackSuccess();
    }
}
