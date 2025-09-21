<?php

namespace App\Http\Controllers\Security\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Security\Users\UpdatePasswordUserRequest;
use App\Traits\Web\General\EvidencePhotoTrait;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UpdateUserControlller extends Controller
{
    use EvidencePhotoTrait;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function profile($uuiduser)
    {
        $user = User::where('uuiduser', $uuiduser)->first();
        return view('Security.Users.profile', compact('user'));
    }

    public function updateprofile(UpdatePasswordUserRequest $request, $uuiduser): RedirectResponse
    {
        $user = User::where('uuiduser', $uuiduser)->first();
        if (! $user) {
            return redirect()->back()->withErrors(['uuiduser' => 'El usuario no fue encontrado.']);
        }
        try {
            // Si se proporciona nueva contraseña, actualizarla
            if ($request->filled('password')) {
                $passwordResult = $this->updatePassword(
                    $request['password'],
                    $user,
                    $request['password_confirmation'],
                );

                if (! $passwordResult['success']) {
                    return redirect()->back()
                        ->withErrors(['password' => $passwordResult['message']])
                        ->withInput();
                }
            } else {
                $user->save();
            }
            return redirect()->back()->with(session()->flash('info', 'Perfil actualizado con exito'));
        } catch (\Exception $e) {
            Log::error('Error al actualizar perfil de usuario', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return redirect()->back()->with(session()->flash('danger', 'Error al actualizar perfil'));
        }
    }

    /**
     * Actualiza la contraseña del usuario con medidas de seguridad mejoradas
     *
     * @param string $newPassword
     * @param User $user
     * @param string|null $currentPassword
     *
     * @return array
     */
    public function updatePassword(string $newPassword, User $user, ?string $password_confirmation = null): array
    {
        try {
            // Validar que la confirmación de contraseña coincida con la nueva contraseña
            if ($password_confirmation !== null && $newPassword !== $password_confirmation) {
                return ['success' => false, 'message' => 'La confirmación de contraseña no coincide con la nueva contraseña.'];
            }

            // Verificar que la nueva contraseña sea diferente a la actual
            if (Hash::check($newPassword, $user->password)) {
                return ['success' => false, 'message' => 'La nueva contraseña debe ser diferente a la actual.'];
            }

            // Actualizar contraseña
            $user->password = Hash::make($newPassword);
            $user->password_changed_at = now();
            $user->save();
            return ['success' => true, 'message' => 'Contraseña actualizada exitosamente.'];
        } catch (\Exception $e) {
            Log::error('Error al actualizar contraseña', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return ['success' => false, 'message' => 'Error interno del servidor.'];
        }
    }
}
