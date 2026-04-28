<?php

namespace App\Http\Controllers\Security\Activos;

use App\Http\Controllers\BaseController;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\Token;

class ActiveUsersController extends BaseController
{
    public function index()
    {
        return view('Security.ActiveUsers.index');
    }

    public function getactiveusers()
    {
        $data = DB::table('oauth_access_tokens as a')
            ->select(
                'a.id',
                'a.user_id',
                'u.username',
                'u.name',
                'a.created_at',
                'a.expires_at',
            )
            ->leftJoin('users as u', 'a.user_id', '=', 'u.id')
            ->where('a.revoked', false)
            ->get();

        return $this->showDataToView($data, 'Security.ActiveUsers.partials.buttons');
    }

    public function destroy($id)
    {
        DB::table('oauth_access_tokens')->where('user_id', $id)->delete();

        return $this->redirectBackSuccessMessage('Registro eliminado con exito');
    }

    public function clearsessions()
    {
        Token::where('expires_at', '<', now())
            ->where('revoked', 0)
            ->delete();

        return $this->redirectBackSuccessMessage('Sesiones eliminadas con exito');
    }
}
