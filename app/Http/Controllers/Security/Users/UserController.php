<?php

namespace App\Http\Controllers\Security\Users;

use App\Http\Controllers\BaseController;
use App\Http\Requests\Web\Security\Users\CreateUserRequest;
use App\Http\Requests\Web\Security\Users\UpdateUserRequest;
use App\Http\Services\Security\ServiceUser;
use App\Traits\Web\Security\QueryUser;
use App\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Redirect;
use Yajra\DataTables\Facades\DataTables;

class UserController extends BaseController
{
    use QueryUser;

    private ServiceUser $serviceuser;

    public function __construct(ServiceUser $serviceUser)
    {
        $this->serviceuser = $serviceUser;
    }

    public function index()
    {
        $this->authorize('securityusers.index');
        $status = $this->getStatusSTS();
        $roles = $this->getRoles();
        return view('Security.Users.index', compact('roles', 'status'));
    }

    public function getDataUsers(Request $request)
    {
        $data = $this->getAllUsers($request);
        return DataTables::of($data)
            ->addColumn('btn', 'Security.Users.partials.buttons')
            ->rawColumns(['btn'])->toJson();
    }

    public function create()
    {
        $this->authorize('securityusers.create');
        $centers = $this->getCenterForSelect();
        $roles = $this->getRoles();
        $warehouses = $this->getWarehouseForSelect();
        return view('Security.Users.create', compact('roles', 'centers', 'warehouses'));
    }

    public function store(CreateUserRequest $request)
    {
        $error = null;
        DB::beginTransaction();
        try {
            $user = $this->serviceuser->createNewUser($request);
            $user->syncRoles([$request['role_id']]);
            DB::commit();
            $success = true;
        } catch (Exception $e) {
            $success = false;
            $error = $e->getMessage();
            DB::rollback();
        }
        if ($success) {
            return redirect::back()->with(session()->flash('success', 'Datos agregados correctamente!!'));
        }
        return redirect::back()->withInput()->with(session()->flash('success', 'No se pudieron guardar los datos!!' . $error));
    }

    public function edit($uuiduser)
    {
        $this->authorize('securityusers.edit');
        $user = User::withTrashed()->where('uuiduser', $uuiduser)->firstOrFail();
        $roles = $this->getRoles();
        $access = $this->getRoleForUser($user->id);
        return view('Security.Users.edit', compact('user', 'roles', 'access'));
    }

    public function update(UpdateUserRequest $request, $uuiduser)
    {
        $securityuser = User::where('uuiduser', $uuiduser)->first();
        $securityuser->update($request->validated());
        $securityuser->syncRoles([$request['role_id']]);
        return $this->infoResponse('securityusers.index');
    }

    public function destroy($uuiduser)
    {
        $this->authorize('securityusers.destroy');
        $user = User::withTrashed()->where('uuiduser', $uuiduser)->firstOrFail();
        $user->statusid = 'D';
        $user->save();
        return redirect()->route('securityusers.index')->with(session()
            ->flash('success', 'Registro eliminadó con exito'));
    }

    /**
     * Enviar correo de reinicio de contraseña
     */
    public function sendPasswordReset($id)
    {
        try {
            $user = User::where('id', $id)->firstOrFail();
            if (empty($user->email)) {
                return redirect()->back()->with('error', 'El usuario no tiene un correo electrónico registrado.');
            }
            $status = Password::sendResetLink([
                'email' => $user->email,
            ]);

            // Log de la acción
            Log::info('Correo de reseteo de contraseña enviado', [
                'user_id' => $user->id,
                'user_email' => $user->email,
                'requested_by' => auth()->id(),
                'timestamp' => now(),
            ]);

            if ($status === Password::RESET_LINK_SENT) {
                return redirect()->back()->with('success', 'Se ha enviado un correo de reinicio de contraseña a: ' . $user->email);
            }
            return redirect()->back()->with('error', 'No se pudo enviar el correo de reinicio. Por favor, inténtelo de nuevo.');
        } catch (Exception $e) {
            Log::error('Error al enviar correo de reset de contraseña', [
                'user_uuid' => $uuiduser,
                'name' => $user->name,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()->with('error', 'Ocurrió un error al enviar el correo de reinicio.');
        }
    }
}
