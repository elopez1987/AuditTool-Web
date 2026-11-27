<?php

namespace App\Console\Commands\General;

use App\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class DeleteUserCommand extends Command
{
    protected $signature = 'system:deleteuser';

    protected $description = 'Elimina los usuario sin inicio de sesion por fechas';

    public function handle(): void
    {
        $now = now();
        $inactiveSince = $now->copy()->subDays(45);
        $neverLoggedSince = $now->copy()->subDays(10);

        $sessions = fn () => DB::table('securityuserssessions')
            ->whereColumn('securityuserssessions.username', 'users.username')
            ->whereNotNull('securityuserssessions.lastsessiondate');

        $total = 0;

        do {
            // Lote de ids (máx. 1000 para no acercarnos al límite de 2100 parámetros)
            $ids = User::query()
                ->where('users.statusid', 'E')
                ->whereNull('users.deleted_at')
                ->where(function ($q) use ($sessions, $inactiveSince, $neverLoggedSince) {
                    $q->where(function ($q) use ($sessions, $inactiveSince) {
                        $q->whereExists($sessions())
                            ->whereNotExists(
                                $sessions()->where('securityuserssessions.lastsessiondate', '>', $inactiveSince)
                            );
                    })->orWhere(function ($q) use ($sessions, $neverLoggedSince) {
                        $q->where('users.created_at', '<=', $neverLoggedSince)
                            ->whereNotExists($sessions());
                    });
                })
                ->limit(1000)
                ->pluck('users.id');   // ajusta al nombre de tu PK

            if ($ids->isEmpty()) {
                break;
            }

            $total += User::whereIn('id', $ids)->update([
                'statusid' => 'D',
                'deleted_at' => $now,
            ]);
        } while (true);

        Log::info("Limpieza de usuarios: {$total} usuarios dados de baja.");
    }
}
