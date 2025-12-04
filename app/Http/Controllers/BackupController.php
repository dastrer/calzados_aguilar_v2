<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use ZipArchive;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;

class BackupController extends Controller
{
    /**
     * Mostrar lista de backups
     */
    public function index()
    {
        try {
            // Obtener configuración
            $diskName = config('backup.backup.destination.disks')[0] ?? 'local';
            $backupName = config('backup.backup.name', 'calzadosaguilar');

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
                            'path' => $file,
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
     * Crear nuevo backup - VERSIÓN MEJORADA
     */
    public function create(Request $request)
    {
        try {
            // Verificar si es limpieza o creación de backup
            if ($request->has('cleanup')) {
                try {
                    Artisan::call('backup:clean');
                    return back()->with('success', 'Limpieza de backups antiguos ejecutada exitosamente');
                } catch (\Exception $e) {
                    // Si falla el comando, hacer limpieza manual
                    $this->cleanOldBackupsManually();
                    return back()->with('success', 'Limpieza manual de backups ejecutada exitosamente');
                }
            }

            // Intentar crear backup con Artisan
            try {
                $exitCode = Artisan::call('backup:run');

                if ($exitCode === 0) {
                    return back()->with('success', 'Backup creado exitosamente');
                } else {
                    // Si falla Artisan, crear backup manualmente
                    return $this->createBackupManually();
                }

            } catch (\Exception $artisanError) {
                // Crear backup manualmente si falla Artisan
                return $this->createBackupManually();
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Error al crear backup: ' . $e->getMessage());
        }
    }

    /**
     * Método para crear backup manualmente
     */
    private function createBackupManually()
    {
        try {
            $backupName = config('backup.backup.name', 'calzadosaguilar');
            $backupDir = storage_path("app/{$backupName}");

            // Crear directorio si no existe
            if (!file_exists($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            // Nombre del archivo con timestamp
            $timestamp = date('Y-m-d-His');
            $filename = "{$backupName}-{$timestamp}.zip";
            $backupPath = "{$backupDir}/{$filename}";

            // Crear archivo ZIP
            $zip = new ZipArchive();
            if ($zip->open($backupPath, ZipArchive::CREATE) !== TRUE) {
                throw new \Exception('No se pudo crear el archivo ZIP');
            }

            // 1. Backup de la base de datos
            $dbDump = $this->createDatabaseDump();
            if ($dbDump) {
                $zip->addFile($dbDump, 'database.sql');
            }

            // 2. Backup del archivo .env
            $envPath = base_path('.env');
            if (file_exists($envPath)) {
                $zip->addFile($envPath, '.env');
            }

            // 3. Backup de archivos de configuración
            $configDir = base_path('config');
            if (is_dir($configDir)) {
                $this->addDirectoryToZip($zip, $configDir, 'config');
            }

            // 4. Backup de storage/app/public (imágenes, documentos)
            $storagePublic = storage_path('app/public');
            if (is_dir($storagePublic)) {
                $this->addDirectoryToZip($zip, $storagePublic, 'storage');
            }

            // 5. Backup de rutas
            $routesDir = base_path('routes');
            if (is_dir($routesDir)) {
                $this->addDirectoryToZip($zip, $routesDir, 'routes');
            }

            // Cerrar ZIP
            $zip->close();

            // Limpiar dump temporal de DB
            if ($dbDump && file_exists($dbDump)) {
                unlink($dbDump);
            }

            return back()->with('success', "Backup manual creado exitosamente: {$filename}");

        } catch (\Exception $e) {
            throw new \Exception('Backup manual falló: ' . $e->getMessage());
        }
    }

    /**
     * Crear dump de la base de datos
     */
    private function createDatabaseDump()
    {
        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');

            $tempFile = storage_path('app/temp_database_' . time() . '.sql');

            // Comando mysqldump
            $command = "mysqldump --user={$username} --password={$password} --host={$host} {$database} > \"{$tempFile}\" 2>&1";

            exec($command, $output, $exitCode);

            if ($exitCode === 0 && file_exists($tempFile) && filesize($tempFile) > 0) {
                return $tempFile;
            }

            // Método alternativo si mysqldump falla
            return $this->createDatabaseDumpWithPHP();

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Método alternativo para dump de DB con PHP
     */
    private function createDatabaseDumpWithPHP()
    {
        try {
            $tempFile = storage_path('app/temp_database_php_' . time() . '.sql');
            $handle = fopen($tempFile, 'w');

            // Obtener todas las tablas
            $tables = DB::select('SHOW TABLES');

            foreach ($tables as $table) {
                $tableName = reset($table);

                // Obtener estructura de la tabla
                $createTable = DB::select("SHOW CREATE TABLE `{$tableName}`")[0];
                fwrite($handle, "-- Table: {$tableName}\n");
                fwrite($handle, "{$createTable->{'Create Table'}};\n\n");

                // Obtener datos de la tabla
                $rows = DB::table($tableName)->get();

                foreach ($rows as $row) {
                    $columns = [];
                    $values = [];

                    foreach ((array)$row as $column => $value) {
                        $columns[] = "`{$column}`";
                        $values[] = "'" . addslashes($value) . "'";
                    }

                    $insertSQL = "INSERT INTO `{$tableName}` (" . implode(', ', $columns) . ") VALUES (" . implode(', ', $values) . ");\n";
                    fwrite($handle, $insertSQL);
                }

                fwrite($handle, "\n");
            }

            fclose($handle);
            return $tempFile;

        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Añadir directorio al ZIP
     */
    private function addDirectoryToZip($zip, $directory, $relativePath)
    {
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($directory),
            RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();
                $relativeFilePath = $relativePath . '/' . substr($filePath, strlen($directory) + 1);

                // Excluir archivos grandes o innecesarios
                if (filesize($filePath) < 10485760) { // 10MB máximo por archivo
                    $zip->addFile($filePath, $relativeFilePath);
                }
            }
        }
    }

    /**
     * Limpiar backups antiguos manualmente
     */
    private function cleanOldBackupsManually()
    {
        try {
            $backupName = config('backup.backup.name', 'calzadosaguilar');
            $backupDir = storage_path("app/{$backupName}");

            if (!is_dir($backupDir)) {
                return;
            }

            $files = glob($backupDir . '/*.zip');
            $now = time();
            $daysToKeep = 30; // Mantener backups por 30 días

            foreach ($files as $file) {
                if (is_file($file)) {
                    // Eliminar archivos más antiguos de $daysToKeep días
                    if ($now - filemtime($file) >= $daysToKeep * 24 * 60 * 60) {
                        unlink($file);
                    }
                }
            }

        } catch (\Exception $e) {
            // Silenciar errores de limpieza
        }
    }

    /**
     * Descargar backup
     */
    public function download($filename)
    {
        try {
            $diskName = config('backup.backup.destination.disks')[0] ?? 'local';
            $disk = Storage::disk($diskName);

            $backupName = config('backup.backup.name', 'calzadosaguilar');
            $filePath = $backupName . '/' . $filename;

            if (!$disk->exists($filePath)) {
                return back()->with('error', 'Archivo no encontrado: ' . $filename);
            }

            // Verificar que sea un archivo ZIP
            if (pathinfo($filename, PATHINFO_EXTENSION) !== 'zip') {
                return back()->with('error', 'El archivo no es un backup válido');
            }

            return $disk->download($filePath);

        } catch (\Exception $e) {
            return back()->with('error', 'Error al descargar: ' . $e->getMessage());
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
            // Ruta completa del backup
            $backupName = config('backup.backup.name', 'calzadosaguilar');
            $backupPath = storage_path("app/{$backupName}/{$filename}");

            if (!file_exists($backupPath)) {
                return back()->with('error', 'El archivo de backup no existe');
            }

            // Verificar que sea un archivo ZIP
            if (pathinfo($filename, PATHINFO_EXTENSION) !== 'zip') {
                return back()->with('error', 'El archivo no es un backup válido');
            }

            // Método 1: Intentar con comando Artisan
            try {
                Artisan::call('backup:restore', [
                    '--filename' => $filename,
                    '--yes' => true,
                ]);

                return back()->with('success', 'Backup restaurado exitosamente');

            } catch (\Exception $artisanError) {
                // Método 2: Restauración manual
                return $this->restoreBackupManually($backupPath);
            }

        } catch (\Exception $e) {
            return back()->with('error', 'Error al restaurar: ' . $e->getMessage());
        }
    }

    /**
     * Restaurar backup manualmente
     */
    private function restoreBackupManually($backupPath)
    {
        try {
            // Directorio temporal para extraer
            $tempDir = storage_path('app/backup-temp/' . uniqid());
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }

            // Extraer archivo ZIP
            $zip = new ZipArchive();
            if ($zip->open($backupPath) !== TRUE) {
                throw new \Exception('No se pudo abrir el archivo ZIP');
            }

            $zip->extractTo($tempDir);
            $zip->close();

            // Restaurar base de datos si existe dump
            $dbDumpPath = $tempDir . '/database.sql';
            if (file_exists($dbDumpPath)) {
                $this->restoreDatabase($dbDumpPath);
            }

            // Restaurar archivos .env
            $envPath = $tempDir . '/.env';
            if (file_exists($envPath)) {
                copy($envPath, base_path('.env.backup-restored-' . date('Y-m-d-His')));
            }

            // Limpiar directorio temporal
            $this->deleteDirectory($tempDir);

            return back()->with('success', 'Backup restaurado manualmente exitosamente');

        } catch (\Exception $e) {
            // Limpiar directorio temporal en caso de error
            if (isset($tempDir) && is_dir($tempDir)) {
                $this->deleteDirectory($tempDir);
            }
            throw new \Exception('Restauración manual falló: ' . $e->getMessage());
        }
    }

    /**
     * Restaurar base de datos desde dump
     */
    private function restoreDatabase($dumpPath)
    {
        try {
            $database = config('database.connections.mysql.database');
            $username = config('database.connections.mysql.username');
            $password = config('database.connections.mysql.password');
            $host = config('database.connections.mysql.host');

            // Primero, eliminar todas las tablas (cuidado - esto es destructivo)
            $tables = DB::select('SHOW TABLES');

            DB::statement('SET FOREIGN_KEY_CHECKS=0');

            foreach ($tables as $table) {
                $tableName = reset($table);
                DB::statement("DROP TABLE IF EXISTS `{$tableName}`");
            }

            DB::statement('SET FOREIGN_KEY_CHECKS=1');

            // Ahora ejecutar el dump SQL
            $sql = file_get_contents($dumpPath);

            // Ejecutar cada consulta por separado
            $queries = array_filter(explode(';', $sql));

            foreach ($queries as $query) {
                if (trim($query) !== '') {
                    DB::statement($query);
                }
            }

            return true;

        } catch (\Exception $e) {
            throw new \Exception('Error al restaurar la base de datos: ' . $e->getMessage());
        }
    }

    /**
     * Eliminar directorio recursivamente
     */
    private function deleteDirectory($dir)
    {
        if (!file_exists($dir)) return true;

        if (!is_dir($dir)) return unlink($dir);

        foreach (scandir($dir) as $item) {
            if ($item == '.' || $item == '..') continue;

            if (!$this->deleteDirectory($dir . DIRECTORY_SEPARATOR . $item)) {
                return false;
            }
        }

        return rmdir($dir);
    }

    /**
     * Eliminar backup
     */
    public function destroy($filename)
    {
        try {
            $diskName = config('backup.backup.destination.disks')[0] ?? 'local';
            $disk = Storage::disk($diskName);

            $backupName = config('backup.backup.name', 'calzadosaguilar');
            $filePath = $backupName . '/' . $filename;

            if (!$disk->exists($filePath)) {
                return back()->with('error', 'Archivo no encontrado');
            }

            $disk->delete($filePath);

            return back()->with('success', 'Backup eliminado exitosamente');

        } catch (\Exception $e) {
            return back()->with('error', 'Error al eliminar: ' . $e->getMessage());
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
