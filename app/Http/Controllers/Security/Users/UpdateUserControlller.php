<?php

namespace App\Http\Controllers\Security\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Security\Users\UpdatePasswordUserRequest;
use App\Models\General\StorageMedia;
use App\Traits\Web\General\EvidencePhotoTrait;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        if (!$user) {
            return redirect()->back()->withErrors(['uuiduser' => 'El usuario no fue encontrado.']);
        }

        try {
            // Actualizar información básica
            $user->name = $request['name'];
            $user->email = $request['email'];
            $this->saveAvatarToUser($request, $user);
            
            // Si se proporciona nueva contraseña, actualizarla
            if ($request->filled('password')) {
                $passwordResult = $this->updatePassword(
                    $request['password'],
                    $user,
                    $request['current_password']
                );
                
                if (!$passwordResult['success']) {
                    return redirect()->back()
                        ->withErrors(['password' => $passwordResult['message']])
                        ->withInput();
                }
            } else {
                $user->save();
            }
            
            return redirect()->back()->with('info', 'Datos actualizados con éxito.');
            
        } catch (\Exception $e) {
            Log::error('Error al actualizar perfil de usuario', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()->back()->withErrors(['error' => 'Ocurrió un error al actualizar el perfil.']);
        }
    }


    public function saveAvatarToUser(Request $request, $user): void
    {
        if ($request->hasFile('file')) {
            if (Storage::exists('storage/' . $user->avatar)) {
                Storage::delete('storage/' . $user->avatar);
            }
            $path = $this->addMediaToModel($request, 'ImageProfile');
            $user->avatar = $path;
        }
    }

    /**
     * Actualiza la contraseña del usuario con medidas de seguridad mejoradas
     *
     * @param string $newPassword
     * @param User $user
     * @param string|null $currentPassword
     * @return array
     */
    public function updatePassword(string $newPassword, User $user, ?string $currentPassword = null): array
    {
        try {
            // Validar contraseña actual si se proporciona
            if ($currentPassword && !Hash::check($currentPassword, $user->password)) {
                Log::warning('Intento de cambio de contraseña con contraseña actual incorrecta', [
                    'user_id' => $user->id,
                    'ip' => request()->ip(),
                    'user_agent' => request()->userAgent()
                ]);
                return ['success' => false, 'message' => 'La contraseña actual no es correcta.'];
            }

            // Verificar que la nueva contraseña sea diferente a la actual
            if (Hash::check($newPassword, $user->password)) {
                return ['success' => false, 'message' => 'La nueva contraseña debe ser diferente a la actual.'];
            }

            // Actualizar contraseña
            $user->password = Hash::make($newPassword);
            $user->password_changed_at = now();
            $user->save();

            // Log del cambio exitoso
            Log::info('Contraseña actualizada exitosamente', [
                'user_id' => $user->id,
                'changed_by' => Auth::id(),
                'ip' => request()->ip(),
                'timestamp' => now()
            ]);

            return ['success' => true, 'message' => 'Contraseña actualizada exitosamente.'];

        } catch (\Exception $e) {
            Log::error('Error al actualizar contraseña', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return ['success' => false, 'message' => 'Error interno del servidor.'];
        }
    }
}
