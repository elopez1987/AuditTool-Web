<?php

namespace App\Http\Controllers\Security\Users;

use App\Http\Controllers\Controller;
use App\Http\Requests\Web\Security\Users\UpdatePasswordUserRequest;
use App\Models\General\StorageMedia;
use App\Traits\Web\General\EvidencePhotoTrait;
use App\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
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
//            $user->email = $request['email'];
            $user->name = $request['name'];
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
