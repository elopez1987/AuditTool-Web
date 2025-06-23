<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Console\Command;

class CreateUuidToUserCommand extends Command
{
    protected $signature = 'create:uuid-to-user';

    protected $description = 'Crea un uuid para los usuarios registrados';

    public function handle(): void
    {
        $users = User::withTrashed()->get();
        foreach ($users as $user) {
            $user->uuiduser = \Str::uuid();
            $user->save();
        }
    }
}
