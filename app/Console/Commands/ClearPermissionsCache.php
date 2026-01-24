<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;

class ClearPermissionsCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:clear-permissions 
                            {--stats : Show cache statistics before clearing}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all permissions cache entries';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        if ($this->option('stats')) {
            $this->showCacheStats();
        }

        try {
            Cache::tags(['permissions'])->flush();
            $this->info('✓ Permissions cache cleared successfully!');

            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('✗ Failed to clear permissions cache: ' . $e->getMessage());
            $this->warn('Make sure Redis is running and cache driver is set to "redis"');

            return Command::FAILURE;
        }
    }

    /**
     * Show cache statistics
     */
    private function showCacheStats(): void
    {
        $this->info('Cache Statistics:');
        $this->line('─────────────────────────────────────');

        try {
            $redis = Cache::getRedis();
            $keys = $redis->keys(config('cache.prefix') . ':permissions:*');

            $this->table(
                ['Metric', 'Value'],
                [
                    ['Total Permissions Keys', count($keys)],
                    ['Cache Driver', config('cache.default')],
                    ['Redis Connection', $redis->ping() ? '✓ Connected' : '✗ Disconnected'],
                ]
            );

            if (count($keys) > 0) {
                $this->line("\nSample cached keys:");
                foreach (array_slice($keys, 0, 5) as $key) {
                    $ttl = $redis->ttl($key);
                    $this->line('  • ' . str_replace(config('cache.prefix') . ':', '', $key) . " (TTL: {$ttl}s)");
                }
                if (count($keys) > 5) {
                    $this->line('  ... and ' . (count($keys) - 5) . ' more');
                }
            }

            $this->line('─────────────────────────────────────');
        } catch (\Exception $e) {
            $this->warn('Could not retrieve cache stats: ' . $e->getMessage());
        }
    }
}
