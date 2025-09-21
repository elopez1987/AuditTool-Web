<?php

namespace App\Console\Commands\General;

use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteUserCommand extends Command
{
    protected $signature = 'system:deleteuser';

    protected $description = 'Elimina los usuario sin inicio de sesion por fechas';

    public function handle(): void
    {
//        Elimina los usuarios que no tengan registros de inicio de sesion en 45 días
        $users = \App\User::with('sessions')
            ->select(
                'users.id as id',
                'name',
                'email',
                'users.username as username',
                'statusid',
                'created_at',
                'updated_at',
                'securityuserssessions.lastsessiondate as lastsessiondate'
            )
            ->leftJoin('securityuserssessions', 'users.username', '=', 'securityuserssessions.username')
            ->where('statusid', 'E')
            ->whereNotNull('securityuserssessions.lastsessiondate')
            ->whereNull('users.deleted_at')
            ->get();

        foreach ($users as $user) {
            $lastSessionDate = Carbon::parse($user->sessions->lastsessiondate);
            $now = Carbon::now();
            $differenceInDays = $lastSessionDate->diffInDays($now);
            if ($differenceInDays >= 45) {
                $user->statusid = 'D';
                $user->save();
                $user->delete();
            }
        }

//        Elimina el usuario que no iniciaron sesion en 10 días después de la creacion del usuario
        $users = \App\User::with('sessions')
            ->select(
                'users.id as id',
                'name',
                'email',
                'users.username as username',
                'statusid',
                'created_at',
                'updated_at',
                'deleted_at',
                'securityuserssessions.lastsessiondate as lastsessiondate'
            )
            ->leftJoin('securityuserssessions', 'users.username', '=', 'securityuserssessions.username')
            ->where('statusid', 'E')
            ->whereNull('securityuserssessions.lastsessiondate')
            ->get();

        foreach ($users as $user) {
            $createdAt = Carbon::parse($user->created_at);
            $now = Carbon::now();
            $differenceInDays = $createdAt->diffInDays($now);
            if ($differenceInDays >= 10) {
                $user->statusid = 'D';
                $user->save();
                $user->delete();
            }
        }
    }
}
