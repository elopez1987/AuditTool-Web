<?php

namespace App\Http\Controllers\Security\Users\Eventos;

use App\Http\Controllers\BaseController;
use App\Imports\Security\ImportUsers;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
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

    public function importusers(Request $request)
    {
        $file = $request->file('file');

        Log::info('[ImportUsers] Inicio de importación', [
            'filename' => $file?->getClientOriginalName(),
            'size' => $file?->getSize(),
            'user' => optional($request->user())->username,
        ]);

        $import = new ImportUsers;

        try {
            Excel::import($import, $file, '', \Maatwebsite\Excel\Excel::XLSX);
        } catch (Throwable $e) {
            Log::error('[ImportUsers] Excepción global durante la importación', [
                'error' => $e->getMessage(),
                'exception' => get_class($e),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }

        $failures = collect($import->failures())->map(fn ($failure) => [
            'row' => $failure->row(),
            'attribute' => $failure->attribute(),
            'errors' => $failure->errors(),
            'values' => $failure->values(),
        ])->all();

        $errors = collect($import->errors())->map(fn ($error) => [
            'message' => $error->getMessage(),
            'exception' => get_class($error),
        ])->all();

        if (! empty($failures)) {
            Log::warning('[ImportUsers] Filas rechazadas por validación', [
                'failures' => $failures,
            ]);
        }

        if (! empty($errors)) {
            Log::warning('[ImportUsers] Filas con error durante la importación', [
                'errors' => $errors,
            ]);
        }

        Log::info('[ImportUsers] Fin de importación', $import->summary());

        return $this->successResponse('securityusers.index');
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
