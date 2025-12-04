<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;

class BackupController extends Controller
{
    /**
     * Mostrar lista de backups
     */
    public function index()
    {
        try {
            // Obtener configuración
            $diskName = config('backup.backup.destination.disks')[0];
            $backupName = config('backup.backup.name', 'laravel');

            $disk = Storage::disk($diskName);
            $backups = [];

            // Verificar si existe el directorio
            if ($disk->exists($backupName)) {
                $files = $disk->files($backupName);

                foreach ($files as $file) {
                    if (pathinfo($file, PATHINFO_EXTENSION) === 'zip') {
                        $backups[] = [
                            'filename' => basename($file),
                            'size' => $this->formatBytes($disk->size($file)),
                            'created_at' => date('Y-m-d H:i:s', $disk->lastModified($file)),
                        ];
                    }
                }

                // Ordenar por fecha (más reciente primero)
                usort($backups, function ($a, $b) {
                    return strtotime($b['created_at']) - strtotime($a['created_at']);
                });
            }

            return view('backups.index', compact('backups'));

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Crear nuevo backup
     */
    public function create()
    {
        try {
            Artisan::call('backup:run');

            return back()->with('success', 'Backup creado exitosamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Descargar backup
     */
    public function download($filename)
    {
        try {
            $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
            $backupName = config('backup.backup.name', 'laravel');
            $filePath = $backupName . '/' . $filename;

            if (!$disk->exists($filePath)) {
                return back()->with('error', 'Archivo no encontrado');
            }

            return $disk->download($filePath);

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Restaurar backup
     */
    public function restore(Request $request, $filename)
    {
        $request->validate([
            'confirmation' => 'required|in:CONFIRMAR'
        ]);

        try {
            Artisan::call('backup:restore', [
                '--filename' => $filename,
                '--yes' => true,
            ]);

            return back()->with('success', 'Backup restaurado exitosamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar backup
     */
    public function destroy($filename)
    {
        try {
            $disk = Storage::disk(config('backup.backup.destination.disks')[0]);
            $backupName = config('backup.backup.name', 'laravel');
            $filePath = $backupName . '/' . $filename;

            if (!$disk->exists($filePath)) {
                return back()->with('error', 'Archivo no encontrado');
            }

            $disk->delete($filePath);

            return back()->with('success', 'Backup eliminado');

        } catch (\Exception $e) {
            return back()->with('error', 'Error: ' . $e->getMessage());
        }
    }

    /**
     * Formatear bytes a tamaño legible
     */
    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . ' ' . $units[$pow];
    }
}
