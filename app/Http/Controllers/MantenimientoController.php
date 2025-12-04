<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class MantenimientoController extends Controller
{
    /**
     * Mostrar el panel de mantenimiento
     */
    public function index()
    {
        return view('mantenimiento.index');
    }

    /**
     * Limpiar cache de la aplicación
     */
    public function clearCache(Request $request)
    {
        try {
            // Ejecutar comandos de limpieza
            Artisan::call('cache:clear');
            Artisan::call('config:clear');
            Artisan::call('view:clear');
            Artisan::call('route:clear');

            return response()->json([
                'success' => true,
                'message' => '✅ Cache de la aplicación limpiado exitosamente.',
                'output' => 'Cache, configuración, vistas y rutas limpiadas.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al limpiar cache: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Optimizar la aplicación
     */
    public function optimize(Request $request)
    {
        try {
            // Para Laravel 8+
            Artisan::call('optimize:clear');
            
            // Si es Laravel 10+, también optimizar
            if (version_compare(app()->version(), '10.0', '>=')) {
                Artisan::call('optimize');
            }

            return response()->json([
                'success' => true,
                'message' => '✅ Aplicación optimizada exitosamente.',
                'output' => 'Archivos optimizados para mejor rendimiento.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al optimizar: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Vaciar logs de la aplicación
     */
    public function clearLogs(Request $request)
    {
        try {
            $logPath = storage_path('logs');
            $deletedCount = 0;

            if (File::exists($logPath)) {
                $files = File::files($logPath);
                
                foreach ($files as $file) {
                    if ($file->getExtension() === 'log') {
                        File::put($file->getPathname(), '');
                        $deletedCount++;
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => $deletedCount > 0 
                    ? "✅ Logs vaciados exitosamente. ($deletedCount archivos)"
                    : "✅ No se encontraron archivos de log.",
                'output' => "Archivos vaciados: $deletedCount"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al vaciar logs: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Regenerar autoload de Composer
     */
    public function clearAutoload(Request $request)
    {
        try {
            // Ejecutar composer dump-autoload en segundo plano
            $process = Process::fromShellCommandline('composer dump-autoload');
            $process->setTimeout(60);
            $process->start();

            return response()->json([
                'success' => true,
                'message' => '✅ Autoload de Composer regenerado.',
                'output' => 'El proceso se ejecutó en segundo plano.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al regenerar autoload: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpiar cache de sesiones
     */
    public function clearSessions(Request $request)
    {
        try {
            Artisan::call('session:clear');

            return response()->json([
                'success' => true,
                'message' => '✅ Sesiones limpiadas exitosamente.',
                'output' => 'Todas las sesiones han sido eliminadas.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al limpiar sesiones: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpiar vistas compiladas
     */
    public function clearCompiledViews(Request $request)
    {
        try {
            Artisan::call('view:clear');

            // También limpiar directorio de vistas compiladas
            $compiledPath = storage_path('framework/views');
            if (File::exists($compiledPath)) {
                $files = File::allFiles($compiledPath);
                foreach ($files as $file) {
                    File::delete($file);
                }
            }

            return response()->json([
                'success' => true,
                'message' => '✅ Vistas compiladas limpiadas.',
                'output' => 'Vistas Blade compiladas eliminadas.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al limpiar vistas: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener estado del sistema
     */
    public function systemStatus(Request $request)
    {
        try {
            $status = [
                'php_version' => phpversion(),
                'laravel_version' => app()->version(),
                'php_memory_limit' => ini_get('memory_limit'),
                'php_max_execution_time' => ini_get('max_execution_time'),
                'storage_writable' => is_writable(storage_path()) ? '✅' : '❌',
                'cache_writable' => is_writable(storage_path('framework/cache')) ? '✅' : '❌',
                'log_writable' => is_writable(storage_path('logs')) ? '✅' : '❌',
                'env_exists' => File::exists(base_path('.env')) ? '✅' : '❌',
                'app_debug' => config('app.debug') ? 'Habilitado' : 'Deshabilitado',
                'app_env' => config('app.env'),
                'timezone' => config('app.timezone'),
                'cache_driver' => config('cache.default'),
                'session_driver' => config('session.driver'),
            ];

            return response()->json([
                'success' => true,
                'message' => '✅ Estado del sistema obtenido.',
                'data' => $status
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al obtener estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Ejecutar mantenimiento completo
     */
    public function fullMaintenance(Request $request)
    {
        try {
            $actions = [];
            
            // 1. Limpiar cache
            Artisan::call('cache:clear');
            $actions[] = 'Cache limpiado';
            
            // 2. Limpiar config
            Artisan::call('config:clear');
            $actions[] = 'Configuración limpiada';
            
            // 3. Limpiar vistas
            Artisan::call('view:clear');
            $actions[] = 'Vistas limpiadas';
            
            // 4. Limpiar rutas
            Artisan::call('route:clear');
            $actions[] = 'Rutas limpiadas';
            
            // 5. Optimizar
            Artisan::call('optimize:clear');
            $actions[] = 'Optimización completada';
            
            // 6. Limpiar sesiones
            Artisan::call('session:clear');
            $actions[] = 'Sesiones limpiadas';

            return response()->json([
                'success' => true,
                'message' => '✅ Mantenimiento completo ejecutado.',
                'output' => implode(' | ', $actions)
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error en mantenimiento completo: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpiar cache de imágenes
     */
    public function clearImageCache(Request $request)
    {
        try {
            $cachePath = public_path('cache');
            $deletedCount = 0;

            if (File::exists($cachePath)) {
                $files = File::allFiles($cachePath);
                foreach ($files as $file) {
                    File::delete($file);
                    $deletedCount++;
                }
            }

            return response()->json([
                'success' => true,
                'message' => $deletedCount > 0 
                    ? "✅ Cache de imágenes limpiado. ($deletedCount archivos)"
                    : "✅ No se encontraron archivos en cache de imágenes.",
                'output' => "Archivos eliminados: $deletedCount"
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al limpiar cache de imágenes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpiar cache de eventos
     */
    public function clearEventCache(Request $request)
    {
        try {
            Artisan::call('event:clear');

            return response()->json([
                'success' => true,
                'message' => '✅ Cache de eventos limpiado.',
                'output' => 'Cache de eventos y listeners eliminado.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al limpiar cache de eventos: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpiar cache de paquetes
     */
    public function clearPackageCache(Request $request)
    {
        try {
            $cachePath = base_path('bootstrap/cache/packages.php');
            if (File::exists($cachePath)) {
                File::delete($cachePath);
            }

            return response()->json([
                'success' => true,
                'message' => '✅ Cache de paquetes limpiado.',
                'output' => 'Archivo de cache de paquetes eliminado.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al limpiar cache de paquetes: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Limpiar cache de servicios
     */
    public function clearServiceCache(Request $request)
    {
        try {
            $cachePath = base_path('bootstrap/cache/services.php');
            if (File::exists($cachePath)) {
                File::delete($cachePath);
            }

            return response()->json([
                'success' => true,
                'message' => '✅ Cache de servicios limpiado.',
                'output' => 'Archivo de cache de servicios eliminado.'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => '❌ Error al limpiar cache de servicios: ' . $e->getMessage()
            ], 500);
        }
    }
}