<?php

use App\Http\Controllers\ActivityLogController;
use App\Http\Controllers\CajaController;
use App\Http\Controllers\categoriaController;
use App\Http\Controllers\clienteController;
use App\Http\Controllers\compraController;
use App\Http\Controllers\EmpleadoController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\ExportExcelController;
use App\Http\Controllers\ExportPDFController;
use App\Http\Controllers\homeController;
use App\Http\Controllers\ImportExcelController;
use App\Http\Controllers\InventarioControlller;
use App\Http\Controllers\KardexController;
use App\Http\Controllers\loginController;
use App\Http\Controllers\logoutController;
use App\Http\Controllers\marcaController;
use App\Http\Controllers\MovimientoController;
use App\Http\Controllers\presentacioneController;
use App\Http\Controllers\ProductoController;
use App\Http\Controllers\profileController;
use App\Http\Controllers\proveedorController;
use App\Http\Controllers\roleController;
use App\Http\Controllers\userController;
use App\Http\Controllers\ventaController;
use App\Http\Controllers\BackupController; // ← AÑADE ESTA LÍNEA
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', [homeController::class, 'index'])->name('panel');

// RUTAS PÚBLICAS DEL CATÁLOGO (SIN AUTENTICACIÓN) - SOLO AGREGADAS
// Catálogo público - visualización completa
Route::get('/catalogo-publico', [ProductoController::class, 'catalogoPublico'])->name('catalogo.publico');

// Búsqueda en catálogo público
Route::get('/catalogo-publico/buscar', [ProductoController::class, 'catalogoPublicoBuscar'])->name('catalogo.publico.buscar');

// RUTA PÚBLICA DEL CATÁLOGO ORIGINAL - Solo visualización (MANTENIDA)
Route::get('/catalogo', [ProductoController::class, 'catalogo'])->name('catalogo');

// RUTAS QUE REQUIEREN AUTENTICACIÓN (GRUPO ORIGINAL - SIN CAMBIOS)
Route::group(['middleware' => 'auth', 'prefix' => 'admin'], function () {
    // ... tus rutas existentes ...
    Route::resource('categorias', categoriaController::class)->except('show');
    Route::resource('presentaciones', presentacioneController::class)->except('show');
    Route::resource('marcas', marcaController::class)->except('show');
    Route::resource('productos', ProductoController::class)->except('show', 'destroy');
    Route::resource('clientes', clienteController::class)->except('show');
    Route::resource('proveedores', proveedorController::class)->except('show');
    Route::resource('compras', compraController::class)->except('edit', 'update', 'destroy');
    Route::resource('ventas', ventaController::class)->except('edit', 'update', 'destroy');
    Route::resource('users', userController::class)->except('show');
    Route::resource('roles', roleController::class)->except('show');
    Route::resource('profile', profileController::class)->only('index', 'update');
    Route::resource('activityLog', ActivityLogController::class)->only('index');
    Route::resource('inventario', InventarioControlller::class)->only('index', 'create', 'store');
    Route::resource('kardex', KardexController::class)->only('index');
    Route::resource('empresa', EmpresaController::class)->only('index', 'update');
    Route::resource('empleados', EmpleadoController::class)->except('show');
    Route::resource('cajas', CajaController::class)->except('edit', 'update', 'show');
    Route::resource('movimientos', MovimientoController::class)->except('show', 'edit', 'update', 'destroy');

    // Ruta para reubicar inventario
    Route::put('/inventario/{inventario}/reubicar', [InventarioControlller::class, 'reubicar'])
        ->name('inventario.reubicar');

    // Reportes
    Route::get('/export-pdf-comprobante-venta/{id}', [ExportPDFController::class, 'exportPdfComprobanteVenta'])
        ->name('export.pdf-comprobante-venta');

    Route::get('/export-excel-vental-all', [ExportExcelController::class, 'exportExcelVentasAll'])
        ->name('export.excel-ventas-all');

    Route::post('/importar-excel-empleados', [ImportExcelController::class, 'importExcelEmpleados'])
        ->name('import.excel-empleados');

    // Ruta para obtener compras por proveedor
    Route::get('/compras-por-proveedor/{proveedor}', function ($proveedorId) {
        $compras = DB::table('compras')
            ->where('proveedore_id', $proveedorId)
            ->select('id', 'fecha_hora', 'numero_comprobante', 'total', 'metodo_pago')
            ->orderBy('fecha_hora', 'desc')
            ->get();

        $totalGeneral = $compras->sum('total');

        return response()->json([
            'compras' => $compras,
            'total_general' => $totalGeneral
        ]);
    })->name('compras.por-proveedor');

    Route::post('/notifications/mark-as-read', function () {
        Auth::user()->unreadNotifications->markAsRead();
        return response()->json(['success' => true]);
    })->name('notifications.markAsRead');

    // =================================================================
    // 🔐 RUTAS DE GESTIÓN DE BACKUPS (PROTEGIDAS POR AUTENTICACIÓN)
    // =================================================================
    Route::prefix('backups')->group(function () {
        // Listar todos los backups
        Route::get('/', [BackupController::class, 'index'])->name('backups.index');

        // Crear nuevo backup
        Route::post('/create', [BackupController::class, 'create'])->name('backups.create');

        // Descargar backup
        Route::get('/download/{filename}', [BackupController::class, 'download'])->name('backups.download');

        // Restaurar backup (con confirmación)
        Route::post('/restore/{filename}', [BackupController::class, 'restore'])->name('backups.restore');

        // Eliminar backup
        Route::delete('/delete/{filename}', [BackupController::class, 'destroy'])->name('backups.delete');
    });
    // =================================================================

    Route::get('/logout', [logoutController::class, 'logout'])->name('logout');
});

// LOGIN (RUTAS ORIGINALES - SIN CAMBIOS)
Route::get('/login', [loginController::class, 'index'])->name('login.index');
Route::post('/login', [loginController::class, 'login'])->name('login.login');
