<?php

namespace App\Console\Commands\General;

use App\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class DeleteUserCommand extends Command
{
    protected $signature = 'system:deleteuser';

    protected $description = 'Elimina los usuario sin inicio de sesion por fechas';

    public function handle(): void
    {
        $now = Carbon::now();

        //  Elimina los usuarios que no tengan registros de inicio de sesion en 45 días
        User::leftJoin('securityuserssessions', 'users.username', '=', 'securityuserssessions.username')
            ->where('users.statusid', 'E')
            ->whereNotNull('securityuserssessions.lastsessiondate')
            ->whereNull('users.deleted_at')
            ->where('securityuserssessions.lastsessiondate', '<=', $now->copy()->subDays(45))
            ->update([
                'users.statusid' => 'D',
                'users.deleted_at' => $now,
            ]);

        //  Elimina el usuario que no iniciaron sesion en 10 días después de la creacion del usuario
        User::leftJoin('securityuserssessions', 'users.username', '=', 'securityuserssessions.username')
            ->where('users.statusid', 'E')
            ->whereNull('securityuserssessions.lastsessiondate')
            ->where('users.created_at', '<=', $now->copy()->subDays(10))
            ->update([
                'users.statusid' => 'D',
                'users.deleted_at' => $now,
            ]);
    }
}
