<?php

namespace App\Observers\Security;

use App\Models\Security\Fuctions\SecurityFunction;
use Illuminate\Support\Facades\Cache;

class SecurityFunctionObserver
{
    /**
     * Handle the SecurityFunction "created" event.
     */
    public function created(SecurityFunction $securityFunction): void
    {
        $this->clearSecurityFunctionsCache();
    }

    /**
     * Handle the SecurityFunction "updated" event.
     */
    public function updated(SecurityFunction $securityFunction): void
    {
        $this->clearSecurityFunctionsCache();
    }

    /**
     * Handle the SecurityFunction "deleted" event.
     */
    public function deleted(SecurityFunction $securityFunction): void
    {
        $this->clearSecurityFunctionsCache();
    }

    /**
     * Handle the SecurityFunction "restored" event.
     */
    public function restored(SecurityFunction $securityFunction): void
    {
        $this->clearSecurityFunctionsCache();
    }

    /**
     * Handle the SecurityFunction "force deleted" event.
     */
    public function forceDeleted(SecurityFunction $securityFunction): void
    {
        $this->clearSecurityFunctionsCache();
    }

    /**
     * Clear all security functions cache
     */
    private function clearSecurityFunctionsCache(): void
    {
        $cacheDriver = config('cache.default');

        try {
            // Opción 1: Redis o Memcached con soporte de tags
            if (in_array($cacheDriver, ['redis', 'memcached'])) {
                Cache::tags(['security_functions'])->flush();

                return;
            }

            // Opción 2: File cache o cualquier otro driver sin tags
            // Necesitamos limpiar manualmente las claves que empiezan con 'security_functions_data_'
            $this->clearSecurityFunctionsCacheByPattern();

        } catch (\Exception $e) {
            // Si todo falla, registrar el error pero no romper la aplicación
            \Log::warning('Failed to clear security functions cache: ' . $e->getMessage());

            // Último recurso: intentar limpiar todo el cache
            try {
                Cache::flush();
            } catch (\Exception $e) {
                \Log::error('Failed to flush entire cache: ' . $e->getMessage());
            }
        }
    }

    /**
     * Clear security functions cache by pattern (for file/database drivers)
     */
    private function clearSecurityFunctionsCacheByPattern(): void
    {
        $cacheDriver = config('cache.default');

        if ($cacheDriver === 'file') {
            // Para file cache, intentar limpiar por claves específicas conocidas
            // Laravel hashea las claves, por lo que no podemos buscar por patrón fácilmente
            // Mejor estrategia: intentar olvidar claves específicas comunes
            try {
                // Intentar limpiar algunas combinaciones comunes de filtros
                $commonFilters = [
                    ['moduleid' => '', 'description' => ''],
                    ['moduleid' => null, 'description' => null],
                ];

                foreach ($commonFilters as $filter) {
                    $keys = [
                        'security_functions_data_' . md5(json_encode($filter)),
                        'security_functions_ids_' . md5(json_encode($filter)),
                    ];

                    foreach ($keys as $key) {
                        Cache::forget($key);
                    }
                }

                // Como último recurso, si no podemos limpiar selectivamente, limpiamos todo
                // Esto es aceptable para file cache ya que es local y se regenera rápido
                Cache::flush();
            } catch (\Exception $e) {
                \Log::warning('Failed to clear specific cache keys, flushing all: ' . $e->getMessage());
                Cache::flush();
            }
        } elseif ($cacheDriver === 'database') {
            // Para database cache podemos buscar por patrón en la columna key
            \DB::table(config('cache.stores.database.table', 'cache'))
                ->where(function ($query) {
                    $query->where('key', 'like', '%security_functions_data_%')
                        ->orWhere('key', 'like', '%security_functions_ids_%');
                })
                ->delete();
        } else {
            // Para otros drivers, limpiar todo
            Cache::flush();
        }
    }
}
