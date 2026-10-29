<?php

declare(strict_types=1);

namespace App\Console\Commands\General;

use App\Services\Security\LocalSessionService;
use Illuminate\Console\Command;

final class PurgeLocalSessions extends Command
{
    protected $signature = 'auth:purge-local-sessions';

    protected $description = 'Purge local session families seven days after absolute expiry; preserve active refresh history';

    public function handle(LocalSessionService $sessions): int
    {
        $total = 0;
        do {
            $removed = $sessions->purgeExpired();
            $total += $removed;
        } while ($removed === 500);

        $this->info("Removed {$total} expired local session tokens.");

        return self::SUCCESS;
    }
}
