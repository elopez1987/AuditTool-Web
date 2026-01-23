<?php

namespace App\Observers;

use App\Models\Security\Permission\Permission;
use Illuminate\Support\Facades\Cache;

class PermissionObserver
{
    /**
     * Handle the Permission "created" event.
     */
    public function created(Permission $permission): void
    {
        $this->clearPermissionsCache();
    }

    /**
     * Handle the Permission "updated" event.
     */
    public function updated(Permission $permission): void
    {
        $this->clearPermissionsCache();
    }

    /**
     * Handle the Permission "deleted" event.
     */
    public function deleted(Permission $permission): void
    {
        $this->clearPermissionsCache();
    }

    /**
     * Handle the Permission "restored" event.
     */
    public function restored(Permission $permission): void
    {
        $this->clearPermissionsCache();
    }

    /**
     * Handle the Permission "force deleted" event.
     */
    public function forceDeleted(Permission $permission): void
    {
        $this->clearPermissionsCache();
    }

    /**
     * Clear all permissions cache
     */
    private function clearPermissionsCache(): void
    {
        // Limpiar cache con tags (Redis/Memcached)
        if (config('cache.default') === 'redis' || config('cache.default') === 'memcached') {
            Cache::tags(['permissions'])->flush();
        } else {
            // Fallback: limpiar por patrón (menos eficiente)
            // Nota: esto requiere Redis para usar el comando KEYS
            try {
                $keys = Cache::getRedis()->keys('permissions:*');
                if (! empty($keys)) {
                    foreach ($keys as $key) {
                        // Remover el prefijo de Laravel del key
                        $key = str_replace(config('cache.prefix') . ':', '', $key);
                        Cache::forget($key);
                    }
                }
            } catch (\Exception $e) {
                // Si falla, simplemente limpiar todo el cache
                Cache::flush();
            }
        }
    }
}
