<?php

namespace App\Http\Controllers\Security\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Security\Users\UpdatePasswordUserRequest;
use App\Models\General\StorageMedia;
use App\Traits\EvidencePhotoTrait;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
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
            $user->email = $request['email'];
            $user->name = $request['name'];
            if ($request->has('password3') && $request->filled('password3')) {
                $validatedData = $request->validate([
                    'password3' => ['required', 'string', Password::min(8)->mixedCase()
                        ->uncompromised()->symbols()->letters()->numbers()],
                ]);
                $session = $this->updatePassword($validatedData['password3'], $user);
                if ($session === '0') {
                    Auth::logout();
                    return Redirect::to('/');
                }
            }
            $this->saveAvatarToUser($request, $user);
            $user->save();
            return redirect()->back()->with('info', 'Datos actualizados con éxito.');
        } catch (\Exception $e) {
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

    public function updatePassword($password, $user): string
    {
        if ($password != '') {
            $user->password = bcrypt($password);
            $user->password2 = $password;
            $session = '0';
        } else {
            $session = '1';
        }
        return $session;
    }
}
