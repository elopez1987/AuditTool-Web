<?php

declare(strict_types=1);

namespace App\Console\Commands\Receipt;

use App\Services\Receipt\VerificationBackfillService;
use Illuminate\Console\Command;

final class ReconcileVerificationLines extends Command
{
    protected $signature = 'receipt:reconcile-verification-lines {--asn= : Filter by ASN UUID} {--apply : Persist unambiguous references}';

    protected $description = 'Preview or link historical verifications to an unambiguous expected line without changing quantities or units';

    public function handle(VerificationBackfillService $service): int
    {
        $counts = $service->reconcile($this->option('asn'), (bool) $this->option('apply'));
        $this->table(['Mode', 'Eligible', 'Unresolved', 'Linked'], [[
            $this->option('apply') ? 'apply' : 'dry-run',
            $counts['eligible'], $counts['unresolved'], $counts['linked'],
        ]]);

        return $counts['unresolved'] > 0 ? self::FAILURE : self::SUCCESS;
    }
}
