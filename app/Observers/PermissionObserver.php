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
        $cacheDriver = config('cache.default');

        try {
            // Opción 1: Redis o Memcached con soporte de tags
            if (in_array($cacheDriver, ['redis', 'memcached'])) {
                Cache::tags(['permissions'])->flush();

                return;
            }

            // Opción 2: File cache o cualquier otro driver sin tags
            // Necesitamos limpiar manualmente las claves que empiezan con 'permissions_data_'
            $this->clearPermissionsCacheByPattern();

        } catch (\Exception $e) {
            // Si todo falla, registrar el error pero no romper la aplicación
            \Log::warning('Failed to clear permissions cache: ' . $e->getMessage());

            // Último recurso: intentar limpiar todo el cache
            try {
                Cache::flush();
            } catch (\Exception $e) {
                \Log::error('Failed to flush entire cache: ' . $e->getMessage());
            }
        }
    }

    /**
     * Clear permissions cache by pattern (for file/database drivers)
     */
    private function clearPermissionsCacheByPattern(): void
    {
        $cacheDriver = config('cache.default');

        if ($cacheDriver === 'file') {
            // Para file cache, buscar y eliminar archivos que coincidan con el patrón
            $cachePath = config('cache.stores.file.path', storage_path('framework/cache/data'));

            if (file_exists($cachePath) && is_dir($cachePath)) {
                // Buscar archivos que contengan 'permissions_data_' en su nombre
                $files = glob($cachePath . '/*');

                foreach ($files as $file) {
                    if (is_file($file)) {
                        $content = @file_get_contents($file);
                        // Los archivos de cache de Laravel contienen datos serializados
                        // Buscar si contienen nuestro patrón de clave
                        if ($content && strpos($content, 'permissions_data_') !== false) {
                            @unlink($file);
                        }
                    }
                }
            }
        } elseif ($cacheDriver === 'database') {
            // Para database cache
            \DB::table(config('cache.stores.database.table', 'cache'))
                ->where('key', 'like', '%permissions_data_%')
                ->delete();
        } else {
            // Para otros drivers, intentar limpiar todo
            Cache::flush();
        }
    }
}
