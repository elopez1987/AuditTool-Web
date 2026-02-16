<?php

namespace App\Console\Commands;

use App\Http\Controllers\Security\Fuctions\SecurityFunctionsController;
use App\Models\Security\Fuctions\SecurityFunction;
use Illuminate\Console\Command;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class TestSecurityFunctionsPerformance extends Command
{
    protected $signature = 'test:security-functions-performance';

    protected $description = 'Test functionality and performance of SecurityFunctions getDataFunctions method';

    public function handle()
    {
        $this->info('=== PRUEBA DE RENDIMIENTO: getDataFunctions ===');
        $this->newLine();

        // Limpiar cache antes de la prueba
        Cache::flush();
        $this->info('Cache limpiado');
        $this->newLine();

        $controller = new SecurityFunctionsController;

        // ==================== Prueba 1: Sin cache ====================
        $this->info('1. Primera consulta (SIN CACHE):');

        $request = Request::create('/getDataFunctions', 'GET', [
            'moduleid' => '',
            'description' => '',
        ]);

        DB::enableQueryLog();
        $start = microtime(true);

        try {
            $response1 = $controller->getDataFunctions($request);
            $end = microtime(true);
            $time1 = round(($end - $start) * 1000, 2);
            $queries1 = DB::getQueryLog();

            $this->line("   - Tiempo: <fg=yellow>{$time1} ms</>");
            $this->line('   - Queries ejecutados: ' . count($queries1));
            $this->line('   - Response type: ' . get_class($response1));

            // Mostrar queries ejecutados
            if (count($queries1) > 0) {
                $this->line('   - Query principal: ' . substr($queries1[0]['query'], 0, 100) . '...');
            }
        } catch (\Exception $e) {
            $this->error('   Error: ' . $e->getMessage());
            $this->error('   File: ' . $e->getFile() . ':' . $e->getLine());

            return 1;
        }

        DB::disableQueryLog();
        $this->newLine();

        // ==================== Prueba 2: Con cache ====================
        $this->info('2. Segunda consulta (CON CACHE):');

        DB::enableQueryLog();
        $start = microtime(true);

        try {
            $response2 = $controller->getDataFunctions($request);
            $end = microtime(true);
            $time2 = round(($end - $start) * 1000, 2);
            $queries2 = DB::getQueryLog();

            $this->line("   - Tiempo: <fg=green>{$time2} ms</>");
            $this->line('   - Queries ejecutados: ' . count($queries2));
            $this->line('   - Response type: ' . get_class($response2));
        } catch (\Exception $e) {
            $this->error('   Error: ' . $e->getMessage());

            return 1;
        }

        DB::disableQueryLog();
        $this->newLine();

        // ==================== Prueba 3: Con filtros ====================
        $this->info('3. Consulta con filtros (nueva cache key):');

        $request3 = Request::create('/getDataFunctions', 'GET', [
            'moduleid' => '1',
            'description' => 'Usuario',
        ]);

        DB::enableQueryLog();
        $start = microtime(true);

        try {
            $response3 = $controller->getDataFunctions($request3);
            $end = microtime(true);
            $time3 = round(($end - $start) * 1000, 2);
            $queries3 = DB::getQueryLog();

            $this->line("   - Tiempo: <fg=yellow>{$time3} ms</>");
            $this->line('   - Queries ejecutados: ' . count($queries3));
        } catch (\Exception $e) {
            $this->error('   Error: ' . $e->getMessage());

            return 1;
        }

        DB::disableQueryLog();
        $this->newLine();

        // ==================== Resultados ====================
        $this->info('=== RESULTADOS ===');
        $improvement = round((($time1 - $time2) / $time1) * 100, 2);
        $this->line("Mejora de rendimiento con cache: <fg=green>{$improvement}%</>");
        $this->line('Reducción de tiempo: ' . round($time1 - $time2, 2) . ' ms');
        $this->line("Velocidad sin cache: {$time1} ms");
        $this->line("Velocidad con cache: {$time2} ms");
        $this->newLine();

        // ==================== Prueba de Observer ====================
        $this->info('=== PRUEBA DE OBSERVER (Limpieza de Cache) ===');

        // Verificar cache antes
        $cacheKey = 'security_functions_ids_' . md5(json_encode([
            'moduleid' => '',
            'description' => '',
        ]));

        $hasCache = Cache::has($cacheKey);
        $this->line('Cache antes de crear registro: ' . ($hasCache ? '<fg=green>Existe</>' : '<fg=red>No existe</>'));

        // Crear un registro de prueba
        try {
            $this->info('Creando registro de prueba...');
            $function = SecurityFunction::create([
                'moduleid' => 1,
                'description' => 'Test Function Performance ' . time(),
                'auxiliary01' => 'test_performance',
            ]);

            // Verificar cache después
            sleep(1); // Dar tiempo al observer
            $hasCacheAfter = Cache::has($cacheKey);
            $this->line('Cache después de crear registro: ' . ($hasCacheAfter ? '<fg=red>Existe (ERROR)</>' : '<fg=green>Limpiado correctamente</>'));

            // Eliminar registro de prueba
            $function->delete();
            $this->info('Registro de prueba eliminado');
        } catch (\Exception $e) {
            $this->error('Error en prueba de observer: ' . $e->getMessage());
        }

        $this->newLine();

        // ==================== Información de configuración ====================
        $this->info('=== CONFIGURACIÓN DE CACHE ===');
        $cacheDriver = config('cache.default');
        $this->line("Driver de cache: <fg=cyan>{$cacheDriver}</>");

        $supportsKeys = in_array($cacheDriver, ['redis', 'memcached']);
        $this->line('Soporta tags: ' . ($supportsKeys ? 'Sí' : 'No'));

        if ($cacheDriver === 'file') {
            $cachePath = config('cache.stores.file.path', storage_path('framework/cache/data'));
            $this->line("Cache path: {$cachePath}");
            $this->line('Cache path existe: ' . (file_exists($cachePath) ? 'Sí' : 'No'));

            // Contar archivos de cache
            if (file_exists($cachePath)) {
                $files = glob($cachePath . '/*');
                $this->line('Archivos en cache: ' . count($files));
            }
        }

        $this->newLine();
        $this->info('✓ Todas las pruebas completadas');

        return 0;
    }
}
