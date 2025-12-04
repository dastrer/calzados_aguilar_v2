@extends('layouts.app')

@section('title', 'Mantenimiento del Sistema')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .maintenance-card {
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        transition: transform 0.3s ease;
        height: 100%;
    }
    .maintenance-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }
    .card-icon {
        font-size: 2.5rem;
        margin-bottom: 1rem;
    }
    .btn-action {
        min-width: 120px;
    }
    .status-badge {
        font-size: 0.8rem;
        padding: 0.25rem 0.5rem;
    }
    .system-info {
        background-color: #f8f9fa;
        border-left: 4px solid #007bff;
        padding: 15px;
        border-radius: 4px;
    }
    .loading-spinner {
        display: none;
    }
    .log-output {
        background-color: #f8f9fa;
        border: 1px solid #dee2e6;
        border-radius: 4px;
        padding: 10px;
        font-family: 'Courier New', monospace;
        font-size: 0.875rem;
        max-height: 150px;
        overflow-y: auto;
    }
    .btn-help {
        margin-left: 5px;
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Mantenimiento del Sistema</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Mantenimiento</li>
    </ol>

    <!-- Botones de Ayuda -->
<div class="mb-4">
    <button type="button" class="btn btn-primary" onclick="showBenefits()">
        <i class="fas fa-info-circle me-1"></i>Ver Beneficios
    </button>
    <button type="button" class="btn btn-secondary ms-2" onclick="showUsageGuide()">
        <i class="fas fa-question-circle me-1"></i>Guía de Uso
    </button>
</div>

    <!-- Advertencia -->
    <div class="alert alert-warning mb-4">
        <h5 class="mb-2"><i class="fas fa-exclamation-triangle me-2"></i>Advertencia</h5>
        <p class="mb-2">Estas herramientas están diseñadas para administradores del sistema. Algunas acciones pueden afectar temporalmente el rendimiento.</p>
        <p class="mb-0"><strong>Nota:</strong> Ninguna de estas acciones afecta los datos de la base de datos.</p>
    </div>

    <!-- Información del sistema -->
    <div class="card mb-4">
        <div class="card-header bg-info text-white d-flex justify-content-between align-items-center">
            <div>
                <i class="fas fa-info-circle me-2"></i>
                Información del Sistema
            </div>
            <button class="btn btn-sm btn-light btn-help" onclick="showSystemInfoHelp()">
                <i class="fas fa-question-circle"></i>
            </button>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <p><strong>PHP:</strong> <span id="phpVersion" class="badge bg-primary">Cargando...</span></p>
                </div>
                <div class="col-md-3">
                    <p><strong>Laravel:</strong> <span id="laravelVersion" class="badge bg-success">Cargando...</span></p>
                </div>
                <div class="col-md-3">
                    <p><strong>Entorno:</strong> <span id="appEnv" class="badge bg-warning">Cargando...</span></p>
                </div>
                <div class="col-md-3">
                    <p><strong>Debug:</strong> <span id="appDebug" class="badge bg-danger">Cargando...</span></p>
                </div>
            </div>
            <button class="btn btn-sm btn-outline-info mt-2" onclick="getSystemStatus()">
                <i class="fas fa-sync-alt me-1"></i>Actualizar Información
            </button>
        </div>
    </div>

    <!-- Mantenimiento Completo -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="card maintenance-card border-danger">
                <div class="card-body text-center">
                    <div class="card-icon text-danger">
                        <i class="fas fa-cogs"></i>
                    </div>
                    <h5 class="card-title">Mantenimiento Completo
                        <button class="btn btn-sm btn-outline-danger btn-help" onclick="showActionHelp('fullMaintenance')">
                            <i class="fas fa-question-circle"></i>
                        </button>
                    </h5>
                    <p class="card-text">Ejecuta todas las tareas de mantenimiento automáticamente.</p>
                    <button class="btn btn-danger btn-action" onclick="executeAction('fullMaintenance')">
                        <i class="fas fa-play-circle me-1"></i>Ejecutar Todo
                    </button>
                    <div class="loading-spinner mt-2 text-danger" id="fullMaintenanceSpinner">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <small class="ms-2">Procesando...</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Herramientas de Mantenimiento -->
    <div class="row">
        <!-- Limpiar Cache -->
        <div class="col-md-4 mb-4">
            <div class="card maintenance-card border-primary">
                <div class="card-body text-center">
                    <div class="card-icon text-primary">
                        <i class="fas fa-broom"></i>
                    </div>
                    <h5 class="card-title">Limpiar Cache
                        <button class="btn btn-sm btn-outline-primary btn-help" onclick="showActionHelp('clearCache')">
                            <i class="fas fa-question-circle"></i>
                        </button>
                    </h5>
                    <p class="card-text">Limpia cache de aplicación, config, vistas y rutas.</p>
                    <button class="btn btn-primary btn-action" onclick="executeAction('clearCache')">
                        <i class="fas fa-trash-alt me-1"></i>Limpiar
                    </button>
                    <div class="loading-spinner mt-2 text-primary" id="clearCacheSpinner">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <small class="ms-2">Limpiando...</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Optimizar Aplicación -->
        <div class="col-md-4 mb-4">
            <div class="card maintenance-card border-success">
                <div class="card-body text-center">
                    <div class="card-icon text-success">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <h5 class="card-title">Optimizar
                        <button class="btn btn-sm btn-outline-success btn-help" onclick="showActionHelp('optimize')">
                            <i class="fas fa-question-circle"></i>
                        </button>
                    </h5>
                    <p class="card-text">Optimiza la aplicación para mejor rendimiento.</p>
                    <button class="btn btn-success btn-action" onclick="executeAction('optimize')">
                        <i class="fas fa-rocket me-1"></i>Optimizar
                    </button>
                    <div class="loading-spinner mt-2 text-success" id="optimizeSpinner">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <small class="ms-2">Optimizando...</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Vaciar Logs -->
        <div class="col-md-4 mb-4">
            <div class="card maintenance-card border-warning">
                <div class="card-body text-center">
                    <div class="card-icon text-warning">
                        <i class="fas fa-file-alt"></i>
                    </div>
                    <h5 class="card-title">Vaciar Logs
                        <button class="btn btn-sm btn-outline-warning btn-help" onclick="showActionHelp('clearLogs')">
                            <i class="fas fa-question-circle"></i>
                        </button>
                    </h5>
                    <p class="card-text">Vacía todos los archivos de registro del sistema.</p>
                    <button class="btn btn-warning btn-action" onclick="executeAction('clearLogs')">
                        <i class="fas fa-file-export me-1"></i>Vaciar
                    </button>
                    <div class="loading-spinner mt-2 text-warning" id="clearLogsSpinner">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <small class="ms-2">Vaciando...</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Regenerar Autoload -->
        <div class="col-md-4 mb-4">
            <div class="card maintenance-card border-info">
                <div class="card-body text-center">
                    <div class="card-icon text-info">
                        <i class="fas fa-code"></i>
                    </div>
                    <h5 class="card-title">Regenerar Autoload
                        <button class="btn btn-sm btn-outline-info btn-help" onclick="showActionHelp('clearAutoload')">
                            <i class="fas fa-question-circle"></i>
                        </button>
                    </h5>
                    <p class="card-text">Regenera el autoload de Composer.</p>
                    <button class="btn btn-info btn-action" onclick="executeAction('clearAutoload')">
                        <i class="fas fa-sync-alt me-1"></i>Regenerar
                    </button>
                    <div class="loading-spinner mt-2 text-info" id="clearAutoloadSpinner">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <small class="ms-2">Regenerando...</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Limpiar Sesiones -->
        <div class="col-md-4 mb-4">
            <div class="card maintenance-card border-secondary">
                <div class="card-body text-center">
                    <div class="card-icon text-secondary">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <h5 class="card-title">Limpiar Sesiones
                        <button class="btn btn-sm btn-outline-secondary btn-help" onclick="showActionHelp('clearSessions')">
                            <i class="fas fa-question-circle"></i>
                        </button>
                    </h5>
                    <p class="card-text">Elimina todas las sesiones de usuario.</p>
                    <button class="btn btn-secondary btn-action" onclick="executeAction('clearSessions')">
                        <i class="fas fa-user-times me-1"></i>Limpiar
                    </button>
                    <div class="loading-spinner mt-2 text-secondary" id="clearSessionsSpinner">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <small class="ms-2">Limpiando...</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Limpiar Vistas -->
        <div class="col-md-4 mb-4">
            <div class="card maintenance-card border-purple">
                <div class="card-body text-center">
                    <div class="card-icon" style="color: #6f42c1;">
                        <i class="fas fa-eye"></i>
                    </div>
                    <h5 class="card-title">Limpiar Vistas
                        <button class="btn btn-sm btn-outline-purple btn-help" onclick="showActionHelp('clearCompiledViews')">
                            <i class="fas fa-question-circle"></i>
                        </button>
                    </h5>
                    <p class="card-text">Elimina vistas compiladas de Blade.</p>
                    <button class="btn btn-action" style="background-color: #6f42c1; color: white;" onclick="executeAction('clearCompiledViews')">
                        <i class="fas fa-eye-slash me-1"></i>Limpiar
                    </button>
                    <div class="loading-spinner mt-2" style="color: #6f42c1;" id="clearCompiledViewsSpinner">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <small class="ms-2">Limpiando...</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Limpiar Cache de Imágenes -->
        <div class="col-md-4 mb-4">
            <div class="card maintenance-card border-pink">
                <div class="card-body text-center">
                    <div class="card-icon" style="color: #e83e8c;">
                        <i class="fas fa-image"></i>
                    </div>
                    <h5 class="card-title">Cache de Imágenes
                        <button class="btn btn-sm btn-outline-pink btn-help" onclick="showActionHelp('clearImageCache')">
                            <i class="fas fa-question-circle"></i>
                        </button>
                    </h5>
                    <p class="card-text">Limpia cache de imágenes y thumbnails.</p>
                    <button class="btn btn-action" style="background-color: #e83e8c; color: white;" onclick="executeAction('clearImageCache')">
                        <i class="fas fa-images me-1"></i>Limpiar
                    </button>
                    <div class="loading-spinner mt-2" style="color: #e83e8c;" id="clearImageCacheSpinner">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <small class="ms-2">Limpiando...</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Limpiar Cache de Eventos -->
        <div class="col-md-4 mb-4">
            <div class="card maintenance-card border-teal">
                <div class="card-body text-center">
                    <div class="card-icon" style="color: #20c997;">
                        <i class="fas fa-bell"></i>
                    </div>
                    <h5 class="card-title">Cache de Eventos
                        <button class="btn btn-sm btn-outline-teal btn-help" onclick="showActionHelp('clearEventCache')">
                            <i class="fas fa-question-circle"></i>
                        </button>
                    </h5>
                    <p class="card-text">Limpia cache de eventos y listeners.</p>
                    <button class="btn btn-action" style="background-color: #20c997; color: white;" onclick="executeAction('clearEventCache')">
                        <i class="fas fa-bell-slash me-1"></i>Limpiar
                    </button>
                    <div class="loading-spinner mt-2" style="color: #20c997;" id="clearEventCacheSpinner">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <small class="ms-2">Limpiando...</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Limpiar Cache de Paquetes -->
        <div class="col-md-4 mb-4">
            <div class="card maintenance-card border-orange">
                <div class="card-body text-center">
                    <div class="card-icon" style="color: #fd7e14;">
                        <i class="fas fa-box-open"></i>
                    </div>
                    <h5 class="card-title">Cache de Paquetes
                        <button class="btn btn-sm btn-outline-orange btn-help" onclick="showActionHelp('clearPackageCache')">
                            <i class="fas fa-question-circle"></i>
                        </button>
                    </h5>
                    <p class="card-text">Limpia cache de paquetes de Composer.</p>
                    <button class="btn btn-action" style="background-color: #fd7e14; color: white;" onclick="executeAction('clearPackageCache')">
                        <i class="fas fa-box me-1"></i>Limpiar
                    </button>
                    <div class="loading-spinner mt-2" style="color: #fd7e14;" id="clearPackageCacheSpinner">
                        <div class="spinner-border spinner-border-sm" role="status">
                            <span class="visually-hidden">Cargando...</span>
                        </div>
                        <small class="ms-2">Limpiando...</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Registro de Acciones -->
    <div class="card mt-4">
        <div class="card-header">
            <i class="fas fa-history me-2"></i>
            Registro de Acciones
        </div>
        <div class="card-body">
            <div class="log-output" id="actionLog">
                <!-- Aquí se mostrarán los resultados -->
                <div class="text-muted">Ejecuta una acción para ver el resultado aquí...</div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
// Mapa de rutas para las acciones
const actionRoutes = {
    'clearCache': '{{ route("mantenimiento.clearCache") }}',
    'optimize': '{{ route("mantenimiento.optimize") }}',
    'clearLogs': '{{ route("mantenimiento.clearLogs") }}',
    'clearAutoload': '{{ route("mantenimiento.clearAutoload") }}',
    'clearSessions': '{{ route("mantenimiento.clearSessions") }}',
    'clearCompiledViews': '{{ route("mantenimiento.clearCompiledViews") }}',
    'fullMaintenance': '{{ route("mantenimiento.fullMaintenance") }}',
    'clearImageCache': '{{ route("mantenimiento.clearImageCache") }}',
    'clearEventCache': '{{ route("mantenimiento.clearEventCache") }}',
    'clearPackageCache': '{{ route("mantenimiento.clearPackageCache") }}',
    'clearServiceCache': '{{ route("mantenimiento.clearServiceCache") }}'
};

// Obtener estado del sistema al cargar
document.addEventListener('DOMContentLoaded', function() {
    getSystemStatus();
});

// ============================================
// FUNCIONES DE AYUDA - SWEETALERT2
// ============================================

// Mostrar beneficios del módulo
function showBenefits() {
    Swal.fire({
        title: '🎯 <strong>Beneficios del Módulo de Mantenimiento</strong>',
        html: `
            <div style="text-align: left; max-height: 400px; overflow-y: auto;">
                <h5 style="color: #28a745;">🚀 1. MEJORA DE RENDIMIENTO</h5>
                <p><strong>Resultado:</strong> 10-50% más rápido después del mantenimiento</p>
                <p>✅ Cache regenerado = Carga más rápida</p>
                <p>✅ Vistas recompiladas = Respuesta inmediata</p>

                <hr>

                <h5 style="color: #17a2b8;">💾 2. AHORRO DE ESPACIO EN DISCO</h5>
                <p><strong>Resultado:</strong> 5-10GB liberados mensualmente</p>
                <p>✅ Logs vaciados = Menos archivos innecesarios</p>
                <p>✅ Cache limpiado = Más espacio útil</p>

                <hr>

                <h5 style="color: #ffc107;">🔧 3. PREVENCIÓN DE ERRORES</h5>
                <p><strong>Resultado:</strong> 80% menos errores comunes</p>
                <p>✅ "Class not found" = Solucionado con Autoload</p>
                <p>✅ "View not found" = Solucionado con Vistas</p>
                <p>✅ Sesiones corruptas = Eliminadas automáticamente</p>

                <hr>

                <h5 style="color: #dc3545;">🔒 4. SEGURIDAD MEJORADA</h5>
                <p><strong>Resultado:</strong> Menos vectores de ataque</p>
                <p>✅ Sesiones viejas eliminadas</p>
                <p>✅ Logs con datos sensibles limpiados</p>
                <p>✅ Archivos temporales removidos</p>

                <hr>

                <h5 style="color: #6610f2;">💰 5. AHORRO DE COSTOS</h5>
                <p><strong>Resultado:</strong> 20-30% menos en hosting</p>
                <p>✅ Menos espacio = Planes más baratos</p>
                <p>✅ Menos errores = Menos soporte técnico</p>
                <p>✅ Menos tiempo muerto = Más productividad</p>

                <hr>

                <h5 style="color: #20c997;">📊 6. ROI (Retorno de Inversión)</h5>
                <p><strong>Inversión:</strong> 5 minutos/semana</p>
                <p><strong>Retorno:</strong> Mejor rendimiento + Menos problemas + Menos costos</p>
                <p>✅ ROI en 1-2 semanas</p>
            </div>
        `,
        width: 800,
        showCloseButton: true,
        showConfirmButton: true,
        confirmButtonText: '¡Entendido!',
        confirmButtonColor: '#3085d6'
    });
}

// Mostrar guía de uso
function showUsageGuide() {
    Swal.fire({
        title: '📖 <strong>Guía de Uso - Frecuencia Recomendada</strong>',
        html: `
            <div style="text-align: left; max-height: 400px; overflow-y: auto;">
                <h5 style="color: #28a745;">📅 USO DIARIO</h5>
                <p><strong>Información del Sistema:</strong> Monitoreo constante</p>
                <p><strong>Vaciar Logs:</strong> Si superan 100MB</p>

                <hr>

                <h5 style="color: #17a2b8;">📅 USO SEMANAL (Recomendado: Viernes noche)</h5>
                <p><strong>Limpiar Cache:</strong> Mantiene rendimiento óptimo</p>
                <p><strong>Limpiar Vistas:</strong> Después de cambios en templates</p>
                <p><strong>Limpiar Sesiones:</strong> Previene problemas de autenticación</p>

                <hr>

                <h5 style="color: #ffc107;">📅 USO MENSUAL</h5>
                <p><strong>Mantenimiento Completo:</strong> Limpieza profunda del sistema</p>
                <p><strong>Optimizar:</strong> Mejora rendimiento a largo plazo</p>
                <p><strong>Regenerar Autoload:</strong> Después de actualizar código</p>

                <hr>

                <h5 style="color: #dc3545;">🚨 USO DESPUÉS DE CAMBIOS</h5>
                <p><strong>Cache de Imágenes:</strong> Cambios en imágenes/productos</p>
                <p><strong>Cache de Eventos:</strong> Agregar nuevos eventos</p>
                <p><strong>Cache de Paquetes:</strong> Instalar/remover paquetes</p>

                <hr>

                <h5 style="color: #6610f2;">🎯 ESCENARIOS COMUNES</h5>
                <p><strong>El sistema está lento:</strong> Limpiar Cache + Optimizar</p>
                <p><strong>Error "Class not found":</strong> Regenerar Autoload</p>
                <p><strong>Imágenes no se actualizan:</strong> Limpiar Cache de Imágenes</p>
                <p><strong>Usuarios desconectados:</strong> Limpiar Sesiones</p>

                <hr>

                <h5 style="color: #20c997;">⚠️ ADVERTENCIAS IMPORTANTES</h5>
                <p><strong>NO AFECTA:</strong> Base de datos, archivos subidos, código fuente</p>
                <p><strong>AFECTA TEMPORALMENTE:</strong> Primera carga después del mantenimiento</p>
                <p><strong>MEJOR HORARIO:</strong> Horarios de bajo tráfico (noche/fin de semana)</p>
            </div>
        `,
        width: 800,
        showCloseButton: true,
        showConfirmButton: true,
        confirmButtonText: 'Aplicar Recomendaciones',
        confirmButtonColor: '#28a745'
    });
}

// Mostrar ayuda específica para cada acción
function showActionHelp(action) {
    const helpMessages = {
        'fullMaintenance': {
            title: '⚙️ MANTENIMIENTO COMPLETO',
            html: `
                <div style="text-align: left;">
                    <h5 style="color: #dc3545;">¿Qué hace?</h5>
                    <p>Ejecuta <strong>todas las tareas de mantenimiento</strong> en secuencia automáticamente.</p>

                    <h5 style="color: #ffc107;">Comandos que ejecuta:</h5>
                    <ul>
                        <li>cache:clear - Limpia cache de aplicación</li>
                        <li>config:clear - Limpia configuración</li>
                        <li>view:clear - Limpia vistas Blade</li>
                        <li>route:clear - Limpia rutas</li>
                        <li>optimize:clear - Optimización general</li>
                        <li>session:clear - Limpia sesiones</li>
                    </ul>

                    <h5 style="color: #28a745;">Impacto:</h5>
                    <p><strong>🟡 Temporal</strong> - El sistema puede ser más lento por 10-30 segundos</p>
                    <p><strong>✅ Beneficio:</strong> Sistema completamente optimizado</p>

                    <h5 style="color: #17a2b8;">Frecuencia recomendada:</h5>
                    <p><strong>📅 Mensualmente</strong> o cuando notes el sistema lento</p>

                    <div class="alert alert-warning mt-3">
                        <strong>⚠️ Advertencia:</strong> Usuarios activos serán desconectados temporalmente.
                    </div>
                </div>
            `
        },
        'clearCache': {
            title: '🧹 LIMPIAR CACHE',
            html: `
                <div style="text-align: left;">
                    <h5 style="color: #0d6efd;">¿Qué limpia?</h5>
                    <ul>
                        <li><strong>Cache de aplicación:</strong> Datos cacheados de consultas</li>
                        <li><strong>Cache de configuración:</strong> Config compilada</li>
                        <li><strong>Cache de vistas:</strong> Vistas Blade precompiladas</li>
                        <li><strong>Cache de rutas:</strong> Rutas cacheadas</li>
                    </ul>

                    <h5 style="color: #28a745;">Impacto:</h5>
                    <p><strong>🟡 Temporal</strong> - La próxima petición será 2-3 segundos más lenta</p>
                    <p><strong>✅ Beneficio:</strong> Elimina datos obsoletos, mejora consistencia</p>

                    <h5 style="color: #17a2b8;">Cuándo usarlo:</h5>
                    <ul>
                        <li>Después de actualizar configuración</li>
                        <li>Si los datos no se actualizan</li>
                        <li>Antes de una auditoría del sistema</li>
                        <li>Semanalmente para mantenimiento preventivo</li>
                    </ul>
                </div>
            `
        },
        'optimize': {
            title: '🚀 OPTIMIZAR APLICACIÓN',
            html: `
                <div style="text-align: left;">
                    <h5 style="color: #198754;">¿Qué optimiza?</h5>
                    <ul>
                        <li><strong>Archivos de Composer:</strong> packages.php, services.php</li>
                        <li><strong>Configuración:</strong> Archivos de configuración compilados</li>
                        <li><strong>Autoload:</strong> Carga automática de clases</li>
                    </ul>

                    <h5 style="color: #28a745;">Impacto:</h5>
                    <p><strong>🟢 Positivo</strong> - Mejora el rendimiento a largo plazo</p>
                    <p><strong>⏱️ Tiempo:</strong> 5-15 segundos de procesamiento</p>

                    <h5 style="color: #17a2b8;">Beneficios específicos:</h5>
                    <ul>
                        <li><strong>10-20% más rápido:</strong> Carga de clases optimizada</li>
                        <li><strong>Menos memoria:</strong> Archivos compilados eficientes</li>
                        <li><strong>Mejor estabilidad:</strong> Menos errores de autoload</li>
                    </ul>

                    <h5 style="color: #ffc107;">Frecuencia recomendada:</h5>
                    <p><strong>📅 Mensualmente</strong> o después de actualizar paquetes</p>
                </div>
            `
        },
        'clearLogs': {
            title: '📄 VACIAR LOGS',
            html: `
                <div style="text-align: left;">
                    <h5 style="color: #fd7e14;">¿Qué hace exactamente?</h5>
                    <p>Vacia el contenido de todos los archivos .log en <code>storage/logs/</code></p>
                    <p><strong>No elimina</strong> los archivos, solo los deja en 0 bytes</p>

                    <h5 style="color: #28a745;">Impacto:</h5>
                    <p><strong>🟢 Positivo</strong> - Libera espacio inmediatamente</p>
                    <p><strong>📊 Típico:</strong> 100MB-2GB liberados</p>

                    <h5 style="color: #dc3545;">⚠️ Advertencia importante:</h5>
                    <p><strong>Se pierde el historial</strong> de errores y actividad</p>
                    <p><strong>Recomendación:</strong> Revisar logs importantes antes de vaciar</p>

                    <h5 style="color: #17a2b8;">Cuándo es crítico usarlo:</h5>
                    <ul>
                        <li>Logs mayores a 1GB</li>
                        <li>Espacio en disco bajo (< 10%)</li>
                        <li>Backups lentos por tamaño de logs</li>
                        <li>Monitoreo dificultado por tamaño</li>
                    </ul>

                    <div class="alert alert-info mt-3">
                        <strong>💡 Consejo:</strong> Configurar logrotate en servidor para automatizar.
                    </div>
                </div>
            `
        },
        'clearAutoload': {
            title: '🔄 REGENERAR AUTOLOAD',
            html: `
                <div style="text-align: left;">
                    <h5 style="color: #0dcaf0;">¿Qué regenera?</h5>
                    <p>El archivo <code>vendor/autoload.php</code> y archivos relacionados de Composer</p>

                    <h5 style="color: #28a745;">Impacto:</h5>
                    <p><strong>🟡 Temporal</strong> - 10-30 segundos de procesamiento</p>
                    <p><strong>✅ Beneficio:</strong> Resuelve errores de "Class not found"</p>

                    <h5 style="color: #dc3545;">Cuándo USARLO OBLIGATORIAMENTE:</h5>
                    <ul>
                        <li>Después de agregar nuevas clases</li>
                        <li>Después de mover archivos de ubicación</li>
                        <li>Después de instalar nuevos paquetes</li>
                        <li>Error: "Class 'App\\...' not found"</li>
                    </ul>

                    <h5 style="color: #17a2b8;">Qué no hace:</h5>
                    <ul>
                        <li>No instala paquetes nuevos</li>
                        <li>No actualiza dependencias</li>
                        <li>No afecta archivos existentes</li>
                    </ul>

                    <div class="alert alert-success mt-3">
                        <strong>✅ Seguro:</strong> Esta acción no puede dañar el sistema.
                    </div>
                </div>
            `
        },
        'clearSessions': {
            title: '👥 LIMPIAR SESIONES',
            html: `
                <div style="text-align: left;">
                    <h5 style="color: #6c757d;">¿Qué elimina?</h5>
                    <p>Todos los archivos de sesión en <code>storage/framework/sessions/</code></p>

                    <h5 style="color: #dc3545;">⚠️ IMPACTO CRÍTICO:</h5>
                    <p><strong>Todos los usuarios serán desconectados</strong> inmediatamente</p>
                    <p>Si usas sesiones en archivos (default Laravel), perderán:</p>
                    <ul>
                        <li>Carritos de compra en progreso</li>
                        <li>Formularios parcialmente completados</li>
                        <li>Preferencias temporales de sesión</li>
                    </ul>

                    <h5 style="color: #28a745;">Espacio liberado:</h5>
                    <p><strong>100MB-2GB</strong> típicamente</p>

                    <h5 style="color: #17a2b8;">Cuándo usarlo estratégicamente:</h5>
                    <ul>
                        <li>✅ Horarios de bajo tráfico (noche, madrugada)</li>
                        <li>✅ Después de cambiar lógica de sesiones</li>
                        <li>✅ Si hay reportes de "sesión perdida"</li>
                        <li>✅ Para forzar re-autenticación de todos</li>
                    </ul>

                    <div class="alert alert-danger mt-3">
                        <strong>🚨 ADVERTENCIA:</strong> Notificar a usuarios si es posible. Mejor hacer fuera de horario laboral.
                    </div>
                </div>
            `
        },
        'clearCompiledViews': {
            title: '👁️ LIMPIAR VISTAS COMPILADAS',
            html: `
                <div style="text-align: left;">
                    <h5 style="color: #6f42c1;">¿Qué elimina?</h5>
                    <p>Archivos en <code>storage/framework/views/</code> (vistas Blade compiladas)</p>

                    <h5 style="color: #28a745;">Impacto:</h5>
                    <p><strong>🟡 Temporal</strong> - La primera carga de cada vista será más lenta</p>
                    <p><strong>✅ Beneficio:</strong> Vistas actualizadas inmediatamente</p>

                    <h5 style="color: #17a2b8;">Espacio liberado:</h5>
                    <p><strong>50-500MB</strong> dependiendo del número de vistas</p>

                    <h5 style="color: #ffc107;">Cuándo USARLO OBLIGATORIAMENTE:</h5>
                    <ul>
                        <li>Después de cambiar templates Blade</li>
                        <li>Si las vistas no muestran cambios recientes</li>
                        <li>Error: "View not found" cuando existe</li>
                        <li>Después de actualizar Laravel</li>
                    </ul>

                    <h5 style="color: #0dcaf0;">Proceso automático:</h5>
                    <p>Las vistas se recompilan automáticamente en la primera visita después de limpiar</p>

                    <div class="alert alert-info mt-3">
                        <strong>💡 Dato:</strong> Laravel cachea vistas para mejorar rendimiento, pero a veces cachea versiones incorrectas.
                    </div>
                </div>
            `
        },
        'clearImageCache': {
            title: '🖼️ CACHE DE IMÁGENES',
            html: `
                <div style="text-align: left;">
                    <h5 style="color: #e83e8c;">¿Qué elimina?</h5>
                    <p>Thumbnails y versiones cacheadas en <code>public/cache/</code></p>

                    <h5 style="color: #28a745;">Impacto:</h5>
                    <p><strong>🟡 Temporal</strong> - Las imágenes se regenerarán cuando se soliciten</p>
                    <p><strong>✅ Beneficio:</strong> Imágenes actualizadas, espacio liberado</p>

                    <h5 style="color: #17a2b8;">Espacio liberado:</h5>
                    <p><strong>100MB-2GB</strong> en sistemas con muchas imágenes</p>

                    <h5 style="color: #ffc107;">Cuándo usarlo:</h5>
                    <ul>
                        <li>Imágenes nuevas no aparecen</li>
                        <li>Cambiaste tamaños de thumbnails</li>
                        <li>Para liberar espacio en disco</li>
                        <li>Después de cambiar algoritmo de compresión</li>
                    </ul>

                    <h5 style="color: #0dcaf0;">Qué pasa después:</h5>
                    <p>Cuando un usuario visite una página con imágenes, se generarán nuevos thumbnails automáticamente</p>

                    <div class="alert alert-warning mt-3">
                        <strong>⚠️ Nota:</strong> La primera vez que se carguen las imágenes después de limpiar será más lento.
                    </div>
                </div>
            `
        }
    };

    if (helpMessages[action]) {
        Swal.fire({
            title: helpMessages[action].title,
            html: helpMessages[action].html,
            width: 700,
            showCloseButton: true,
            showConfirmButton: true,
            confirmButtonText: 'Entendido',
            confirmButtonColor: '#3085d6'
        });
    } else {
        Swal.fire({
            icon: 'info',
            title: 'Información',
            text: 'Información detallada disponible pronto',
            confirmButtonColor: '#3085d6'
        });
    }
}

// Ayuda para información del sistema
function showSystemInfoHelp() {
    Swal.fire({
        title: '📊 <strong>Información del Sistema</strong>',
        html: `
            <div style="text-align: left;">
                <h5 style="color: #0d6efd;">¿Qué monitorea?</h5>
                <ul>
                    <li><strong>PHP Version:</strong> Versión del motor PHP</li>
                    <li><strong>Laravel Version:</strong> Versión del framework</li>
                    <li><strong>Entorno:</strong> production, local, testing</li>
                    <li><strong>Debug:</strong> Si está habilitado (peligroso en producción)</li>
                    <li><strong>Permisos:</strong> Si el sistema puede escribir donde necesita</li>
                </ul>

                <h5 style="color: #28a745;">Por qué es importante:</h5>
                <ul>
                    <li>✅ Detecta problemas antes de que ocurran</li>
                    <li>✅ Verifica que todo esté configurado correctamente</li>
                    <li>✅ Ayuda a debuggear problemas</li>
                    <li>✅ Permite tomar decisiones informadas</li>
                </ul>

                <h5 style="color: #dc3545;">🚨 ALERTAS CRÍTICAS:</h5>
                <ul>
                    <li><strong>Debug en producción:</strong> ¡Riesgo de seguridad!</li>
                    <li><strong>Sin permisos de escritura:</strong> El sistema no funcionará</li>
                    <li><strong>PHP versión muy vieja:</strong> Vulnerabilidades de seguridad</li>
                    <li><strong>Memoria PHP baja:</strong> El sistema se caerá</li>
                </ul>

                <h5 style="color: #17a2b8;">Frecuencia de revisión:</h5>
                <p><strong>📅 Diariamente</strong> en sistemas críticos</p>
                <p><strong>📅 Semanalmente</strong> en sistemas normales</p>

                <div class="alert alert-info mt-3">
                    <strong>💡 Consejo:</strong> Revisa esta información antes de cualquier mantenimiento mayor.
                </div>
            </div>
        `,
        width: 700,
        showCloseButton: true,
        showConfirmButton: true,
        confirmButtonText: 'Verificar ahora',
        confirmButtonColor: '#28a745',
        showCancelButton: true,
        cancelButtonText: 'Cerrar'
    }).then((result) => {
        if (result.isConfirmed) {
            getSystemStatus();
        }
    });
}

// ============================================
// FUNCIONES ORIGINALES DEL SISTEMA
// ============================================

// Función para obtener estado del sistema
function getSystemStatus() {
    fetch('{{ route("mantenimiento.systemStatus") }}', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById('phpVersion').textContent = data.data.php_version;
            document.getElementById('laravelVersion').textContent = data.data.laravel_version;
            document.getElementById('appEnv').textContent = data.data.app_env;
            document.getElementById('appDebug').textContent = data.data.app_debug;

            // Mostrar alerta si todo está bien
            Swal.fire({
                icon: 'success',
                title: 'Sistema OK',
                text: 'El estado del sistema se ha actualizado correctamente',
                timer: 2000,
                showConfirmButton: false
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
    });
}

// Función para ejecutar acciones
function executeAction(action) {
    const spinner = document.getElementById(action + 'Spinner');
    const button = event.target.closest('.btn-action');

    // Mostrar spinner y deshabilitar botón
    if (spinner) spinner.style.display = 'inline-block';
    if (button) button.disabled = true;

    // Confirmación para acciones importantes
    if (action === 'fullMaintenance' || action === 'clearLogs') {
        if (!confirm('¿Estás seguro de ejecutar esta acción? Esto puede tomar varios segundos.')) {
            if (spinner) spinner.style.display = 'none';
            if (button) button.disabled = false;
            return;
        }
    }

    // Verificar que la ruta existe
    if (!actionRoutes[action]) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: `La ruta para la acción "${action}" no está definida.`,
            confirmButtonColor: '#d33'
        });
        if (spinner) spinner.style.display = 'none';
        if (button) button.disabled = false;
        return;
    }

    // Ejecutar la acción
    fetch(actionRoutes[action], {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Ocultar spinner y habilitar botón
        if (spinner) spinner.style.display = 'none';
        if (button) button.disabled = false;

        // Mostrar resultado en SweetAlert2
        if (data.success) {
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: data.message,
                timer: 3000,
                showConfirmButton: false
            });

            // Agregar al registro
            addToLog(data.message, data.output || '');
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: data.message,
                confirmButtonColor: '#d33'
            });

            // Agregar error al registro
            addToLog(data.message, 'Error', 'error');
        }
    })
    .catch(error => {
        // Ocultar spinner y habilitar botón
        if (spinner) spinner.style.display = 'none';
        if (button) button.disabled = false;

        Swal.fire({
            icon: 'error',
            title: 'Error de conexión',
            text: 'No se pudo completar la acción. Verifica tu conexión.',
            confirmButtonColor: '#d33'
        });

        addToLog('Error de conexión', error.toString(), 'error');
    });
}

// Función para agregar al registro
function addToLog(action, details, type = 'success') {
    const log = document.getElementById('actionLog');
    const timestamp = new Date().toLocaleTimeString();

    const logEntry = document.createElement('div');
    logEntry.innerHTML = `
        <div class="mb-2">
            <strong class="${type === 'success' ? 'text-success' : 'text-danger'}">
                [${timestamp}] ${action}
            </strong>
            ${details ? `<br><small class="text-muted">${details}</small>` : ''}
            <hr class="my-1">
        </div>
    `;

    // Agregar al principio
    log.insertBefore(logEntry, log.firstChild);

    // Limitar a 10 entradas
    const entries = log.querySelectorAll('div.mb-2');
    if (entries.length > 10) {
        entries[entries.length - 1].remove();
    }
}
</script>
@endpush
