@extends('layouts.app')

@section('title', 'Backups')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .clickable-row {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .clickable-row:hover {
        background-color: #f8f9fa !important;
    }
    .backup-details {
        background-color: #f8f9fa;
        border-left: 4px solid #007bff;
    }
    .arrow-icon {
        transition: transform 0.3s ease;
    }
    .arrow-icon.rotated {
        transform: rotate(90deg);
    }
    .filter-card {
        background-color: #f8f9fa;
        border-left: 4px solid #007bff;
    }
    .file-info {
        font-family: 'Courier New', monospace;
        background-color: #f8f9fa;
        padding: 10px;
        border-radius: 4px;
        font-size: 0.9rem;
    }
    .warning-box {
        background-color: #fff3cd;
        border-left: 4px solid #ffc107;
        padding: 15px;
        border-radius: 4px;
        margin-bottom: 20px;
    }
    /* Estilos para botones de la tabla */
    .btn-download-table {
        color: #0d6efd !important;
        border-color: #0d6efd !important;
        background: transparent !important;
    }
    .btn-download-table:hover {
        background-color: #0d6efd !important;
        color: white !important;
    }
    .btn-restore-table {
        color: #fd7e14 !important;
        border-color: #fd7e14 !important;
        background: transparent !important;
    }
    .btn-restore-table:hover {
        background-color: #fd7e14 !important;
        color: white !important;
    }
    .btn-delete-table {
        color: #dc3545 !important;
        border-color: #dc3545 !important;
        background: transparent !important;
    }
    .btn-delete-table:hover {
        background-color: #dc3545 !important;
        color: white !important;
    }
    /* Ajustes para los botones de la tabla */
    .table-actions .btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.875rem;
        margin: 0 2px;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Gestión de Backups</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Backups</li>
    </ol>

    <!-- Advertencia importante -->
    <div class="warning-box">
        <h5 class="mb-2"><i class="fas fa-exclamation-triangle text-warning me-2"></i>Importante</h5>
        <p class="mb-2">Los backups contienen toda la información del sistema, incluyendo base de datos y archivos.</p>
        <p class="mb-0"><strong>Ubicación:</strong> <code>storage/app/{{ config('backup.backup.name', 'laravel') }}/</code></p>
    </div>

    <!-- Botón para crear nuevo backup (TUS ESTILOS ORIGINALES) -->
    <div class="mb-4 d-flex justify-content-between align-items-center">
        <div>
            <form action="{{ route('backups.create') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary" id="createBackupBtn">
                    <i class="fas fa-plus-circle me-1"></i>Crear Nuevo Backup
                </button>
            </form>

            <!-- Botón para limpiar backups antiguos (TUS ESTILOS ORIGINALES) -->
            <button type="button" class="btn btn-outline-secondary ms-2" id="cleanupBtn">
                <i class="fas fa-broom me-1"></i>Limpiar Backups Antiguos
            </button>
        </div>

        <!-- Contador de backups -->
        <div class="text-muted">
            <i class="fas fa-database me-1"></i>
            <span id="backupCount">{{ count($backups ?? []) }}</span> backups disponibles
        </div>
    </div>

    <!-- Card principal -->
    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Lista de Backups Disponibles
        </div>
        <div class="card-body">
            @if(empty($backups))
                <div class="text-center py-5">
                    <i class="fas fa-database fa-4x text-muted mb-3"></i>
                    <h5 class="text-muted">No hay backups disponibles</h5>
                    <p class="text-muted">Crea tu primer backup usando el botón superior</p>
                </div>
            @else
                <table id="datatablesSimple" class="table table-striped">
                    <thead>
                        <tr>
                            <th width="40"></th>
                            <th>Nombre del Archivo</th>
                            <th width="120">Tamaño</th>
                            <th width="180">Fecha de Creación</th>
                            <th width="150">Estado</th>
                            <th width="180" class="text-center">Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($backups as $index => $backup)
                        <tr class="clickable-row" onclick="toggleDetails({{ $index }})">
                            <td>
                                <i class="fas fa-chevron-right arrow-icon" id="arrow-{{ $index }}"></i>
                            </td>
                            <td>
                                <i class="fas fa-file-archive text-primary me-2"></i>
                                <strong>{{ $backup['filename'] }}</strong>
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $backup['size'] }}</span>
                            </td>
                            <td>
                                <small class="text-muted">{{ $backup['created_at'] }}</small>
                            </td>
                            <td>
                                @php
                                    $isRecent = strtotime($backup['created_at']) > strtotime('-7 days');
                                @endphp
                                <span class="badge rounded-pill text-bg-{{ $isRecent ? 'success' : 'warning' }}">
                                    {{ $isRecent ? 'Reciente' : 'Antiguo' }}
                                </span>
                            </td>
                            <td class="text-center table-actions" onclick="event.stopPropagation();">
                                <div class="d-flex justify-content-center">
                                    <!-- Descargar (CON COLORES) -->
                                    <div>
                                        <a href="{{ route('backups.download', $backup['filename']) }}"
                                           class="btn btn-sm btn-download-table mx-1"
                                           title="Descargar Backup"
                                           onclick="return confirmDownload(event, '{{ $backup['filename'] }}')">
                                            <i class="fas fa-download"></i>
                                        </a>
                                    </div>

                                    <!-- Restaurar (CON COLORES) -->
                                    <div>
                                        <button type="button"
                                                class="btn btn-sm btn-restore-table mx-1"
                                                title="Restaurar Backup"
                                                onclick="confirmRestore('{{ $backup['filename'] }}', '{{ $backup['size'] }}', '{{ $backup['created_at'] }}')">
                                            <i class="fas fa-history"></i>
                                        </button>
                                    </div>

                                    <!-- Eliminar (CON COLORES) -->
                                    <div>
                                        <button type="button"
                                                class="btn btn-sm btn-delete-table mx-1"
                                                title="Eliminar Backup"
                                                onclick="confirmDelete('{{ $backup['filename'] }}', '{{ $backup['size'] }}', '{{ $backup['created_at'] }}')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </td>
                        </tr>

                        <!-- Fila desplegable con detalles -->
                        <tr id="details-{{ $index }}" style="display: none;">
                            <td colspan="6" class="p-0">
                                <div class="backup-details p-3">
                                    <h6 class="mb-3">
                                        <i class="fas fa-info-circle me-2"></i>
                                        Detalles del Backup
                                    </h6>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="file-info mb-3">
                                                <strong><i class="fas fa-file me-2"></i>Información del Archivo:</strong>
                                                <div class="mt-2">
                                                    <p class="mb-1"><strong>Nombre:</strong> {{ $backup['filename'] }}</p>
                                                    <p class="mb-1"><strong>Tamaño:</strong> {{ $backup['size'] }}</p>
                                                    <p class="mb-1"><strong>Creado:</strong> {{ $backup['created_at'] }}</p>
                                                    <p class="mb-0"><strong>Edad:</strong>
                                                        @php
                                                            $created = \Carbon\Carbon::parse($backup['created_at']);
                                                            $diff = $created->diffForHumans();
                                                        @endphp
                                                        {{ $diff }}
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="alert alert-info">
                                                <h6><i class="fas fa-database me-2"></i>Contenido:</h6>
                                                <ul class="mb-0">
                                                    <li>Base de datos completa</li>
                                                    <li>Archivos del sistema</li>
                                                    <li>Configuraciones</li>
                                                    <li>Imágenes y documentos</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="alert alert-warning mt-3">
                                        <i class="fas fa-clock me-2"></i>
                                        <strong>Política de Retención:</strong> Este backup será eliminado automáticamente según:
                                        <ul class="mb-0 mt-2">
                                            <li><strong>7 días:</strong> Se mantienen todos los backups</li>
                                            <li><strong>16 días:</strong> Solo uno diario por backup</li>
                                            <li><strong>8 semanas:</strong> Solo uno semanal</li>
                                            <li><strong>4 meses:</strong> Solo uno mensual</li>
                                            <li><strong>2 años:</strong> Solo uno anual</li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <!-- Información de retención -->
                <div class="alert alert-info mt-3">
                    <div class="row">
                        <div class="col-md-8">
                            <h6><i class="fas fa-info-circle me-2"></i>Información del Sistema de Backups</h6>
                            <p class="mb-2">Los backups se crean automáticamente según la configuración del sistema.</p>
                            <p class="mb-0"><strong>Ubicación física:</strong> <code>{{ storage_path('app/' . config('backup.backup.name', 'laravel')) }}</code></p>
                        </div>
                        <div class="col-md-4 text-end">
                            <p class="mb-1"><strong>Espacio total:</strong>
                                @php
                                    $totalSize = 0;
                                    foreach($backups as $backup) {
                                        $size = floatval(str_replace([' MB', ' GB', ' KB', ' B'], '', $backup['size']));
                                        $unit = substr($backup['size'], -2);
                                        if ($unit === 'GB') $size *= 1024;
                                        if ($unit === 'KB') $size /= 1024;
                                        $totalSize += $size;
                                    }
                                @endphp
                                {{ number_format($totalSize, 2) }} MB
                            </p>
                            <p class="mb-0"><strong>Backups:</strong> {{ count($backups) }}</p>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

<!-- Formularios ocultos para acciones -->
<form id="restoreForm" method="POST" style="display: none;">
    @csrf
    <input type="hidden" name="confirmation" id="restoreConfirmation" value="CONFIRMAR">
</form>

<form id="deleteForm" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Modal para limpieza de backups (MANTENIDO DE TU VERSIÓN) -->
<div class="modal fade" id="cleanupModal" tabindex="-1" aria-labelledby="cleanupModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h1 class="modal-title fs-5" id="cleanupModalLabel">
                    <i class="fas fa-broom me-2"></i>Limpiar Backups Antiguos
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>¿Deseas ejecutar la limpieza automática de backups antiguos?</p>
                <div class="alert alert-warning">
                    <h6><i class="fas fa-clock me-2"></i>Política de Retención Actual:</h6>
                    <ul class="mb-0">
                        <li>Mantener todos los backups por 7 días</li>
                        <li>Mantener backups diarios por 16 días</li>
                        <li>Mantener backups semanales por 8 semanas</li>
                        <li>Mantener backups mensuales por 4 meses</li>
                        <li>Mantener backups anuales por 2 años</li>
                    </ul>
                </div>
                <p class="text-muted">Esta acción eliminará automáticamente los backups que excedan la política de retención.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                <form action="{{ route('backups.create') }}" method="POST">
                    @csrf
                    <input type="hidden" name="cleanup" value="true">
                    <button type="submit" class="btn btn-info">
                        <i class="fas fa-broom me-1"></i>Ejecutar Limpieza
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>

<script>
// Mostrar alertas de SweetAlert2 desde sesión
@if(session('success'))
    Swal.fire({
        icon: 'success',
        title: '¡Éxito!',
        text: '{{ session('success') }}',
        timer: 3000,
        showConfirmButton: false
    });
@endif

@if(session('error'))
    Swal.fire({
        icon: 'error',
        title: 'Error',
        text: '{{ session('error') }}',
        confirmButtonColor: '#d33'
    });
@endif

// Función para mostrar/ocultar detalles
function toggleDetails(index) {
    const detailsRow = document.getElementById(`details-${index}`);
    const arrowIcon = document.getElementById(`arrow-${index}`);

    if (detailsRow.style.display === 'none') {
        detailsRow.style.display = 'table-row';
        arrowIcon.classList.add('rotated');
    } else {
        detailsRow.style.display = 'none';
        arrowIcon.classList.remove('rotated');
    }
}

// Confirmación para crear backup (CON SWEETALERT2)
document.getElementById('createBackupBtn')?.addEventListener('click', function(e) {
    if (this.closest('form').checkValidity()) {
        e.preventDefault();

        Swal.fire({
            title: '¿Crear nuevo backup?',
            text: 'Esta acción puede tomar varios minutos dependiendo del tamaño de la base de datos.',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, crear backup',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                this.closest('form').submit();

                // Mostrar mensaje de carga
                Swal.fire({
                    title: 'Creando backup...',
                    text: 'Por favor espera, esto puede tomar unos minutos.',
                    allowOutsideClick: false,
                    didOpen: () => {
                        Swal.showLoading();
                    }
                });
            }
        });
    }
});

// Confirmación para limpiar backups (MODAL ORIGINAL + SWEETALERT2 OPCIONAL)
document.getElementById('cleanupBtn')?.addEventListener('click', function() {
    // Usamos SweetAlert2 en lugar del modal
    Swal.fire({
        title: 'Limpiar backups antiguos',
        html: `
            <div class="text-start">
                <p>¿Deseas ejecutar la limpieza automática de backups antiguos?</p>
                <div class="alert alert-warning mt-3">
                    <h6><i class="fas fa-clock me-2"></i>Política de Retención:</h6>
                    <ul class="mb-0">
                        <li>Mantener todos los backups por 7 días</li>
                        <li>Mantener backups diarios por 16 días</li>
                        <li>Mantener backups semanales por 8 semanas</li>
                        <li>Mantener backups mensuales por 4 meses</li>
                        <li>Mantener backups anuales por 2 años</li>
                    </ul>
                </div>
                <p class="text-muted">Esta acción eliminará automáticamente los backups que excedan la política de retención.</p>
            </div>
        `,
        icon: 'info',
        showCancelButton: true,
        confirmButtonColor: '#17a2b8',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-broom me-1"></i>Ejecutar Limpieza',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Crear y enviar formulario dinámicamente
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = '{{ route("backups.create") }}';
            form.style.display = 'none';

            const csrfToken = document.createElement('input');
            csrfToken.type = 'hidden';
            csrfToken.name = '_token';
            csrfToken.value = '{{ csrf_token() }}';

            const cleanupField = document.createElement('input');
            cleanupField.type = 'hidden';
            cleanupField.name = 'cleanup';
            cleanupField.value = 'true';

            form.appendChild(csrfToken);
            form.appendChild(cleanupField);
            document.body.appendChild(form);
            form.submit();

            // Mostrar mensaje de carga
            Swal.fire({
                title: 'Limpiando backups...',
                text: 'Eliminando backups antiguos según la política de retención.',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });
        }
    });
});

// Confirmación para descargar backup
function confirmDownload(event, filename) {
    event.preventDefault();

    Swal.fire({
        title: 'Descargar backup',
        text: `¿Deseas descargar el backup "${filename}"?`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#0d6efd',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-download me-1"></i>Descargar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Continuar con la descarga
            window.location.href = event.target.href;
        }
    });

    return false;
}

// Confirmación para restaurar backup
function confirmRestore(filename, size, date) {
    Swal.fire({
        title: '⚠️ RESTAURAR BACKUP ⚠️',
        html: `
            <div class="text-start">
                <div class="alert alert-danger mb-3">
                    <h6><i class="fas fa-skull-crossbones me-2"></i>ADVERTENCIA CRÍTICA</h6>
                    <p class="mb-0">
                        Esta acción <strong>SOBREESCRIBIRÁ</strong> toda la base de datos actual
                        con los datos del backup seleccionado.
                        <strong class="text-danger">TODOS LOS DATOS NUEVOS SE PERDERÁN PERMANENTEMENTE.</strong>
                    </p>
                </div>

                <div class="alert alert-light mb-3">
                    <p class="mb-1"><strong>Backup seleccionado:</strong></p>
                    <p class="mb-1"><strong>Archivo:</strong> ${filename}</p>
                    <p class="mb-1"><strong>Tamaño:</strong> ${size}</p>
                    <p class="mb-0"><strong>Fecha:</strong> ${date}</p>
                </div>

                <p class="text-danger">
                    <i class="fas fa-exclamation-circle me-1"></i>
                    Esta acción no se puede deshacer. Verifica que tienes un backup actual antes de continuar.
                </p>

                <div class="mb-3">
                    <label for="swalConfirmation" class="form-label">
                        Para confirmar, escribe <code class="text-danger">CONFIRMAR</code>:
                    </label>
                    <input type="text"
                           class="form-control text-uppercase"
                           id="swalConfirmation"
                           placeholder="CONFIRMAR">
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#fd7e14',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-history me-1"></i>Restaurar Backup',
        cancelButtonText: 'Cancelar',
        preConfirm: () => {
            const confirmation = document.getElementById('swalConfirmation').value;
            if (confirmation.toUpperCase() !== 'CONFIRMAR') {
                Swal.showValidationMessage('Debes escribir exactamente "CONFIRMAR" para proceder');
                return false;
            }
            return true;
        }
    }).then((result) => {
        if (result.isConfirmed) {
            // Confirmación final
            Swal.fire({
                title: '¿ESTÁS COMPLETAMENTE SEGURO?',
                text: 'Esta es tu última oportunidad para cancelar. La base de datos será SOBREESCRITA completamente.',
                icon: 'error',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'SÍ, RESTAURAR BACKUP',
                cancelButtonText: 'NO, CANCELAR'
            }).then((finalResult) => {
                if (finalResult.isConfirmed) {
                    // Enviar formulario de restauración
                    const form = document.getElementById('restoreForm');
                    form.action = `{{ url('admin/backups/restore/') }}/${filename}`;
                    form.submit();

                    // Mostrar mensaje de carga
                    Swal.fire({
                        title: 'Restaurando backup...',
                        text: 'Por favor espera, esto puede tomar varios minutos.',
                        allowOutsideClick: false,
                        didOpen: () => {
                            Swal.showLoading();
                        }
                    });
                }
            });
        }
    });
}

// Confirmación para eliminar backup
function confirmDelete(filename, size, date) {
    Swal.fire({
        title: 'Eliminar Backup',
        html: `
            <div class="text-start">
                <p>¿Estás seguro de eliminar este backup permanentemente?</p>
                <div class="alert alert-light mb-3">
                    <p class="mb-1"><strong>Archivo:</strong> ${filename}</p>
                    <p class="mb-1"><strong>Tamaño:</strong> ${size}</p>
                    <p class="mb-0"><strong>Fecha:</strong> ${date}</p>
                </div>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    Una vez eliminado, no podrás recuperar este backup.
                </div>
            </div>
        `,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#dc3545',
        cancelButtonColor: '#6c757d',
        confirmButtonText: '<i class="fas fa-trash me-1"></i>Eliminar',
        cancelButtonText: 'Cancelar'
    }).then((result) => {
        if (result.isConfirmed) {
            // Enviar formulario de eliminación
            const form = document.getElementById('deleteForm');
            form.action = `{{ url('admin/backups/delete/') }}/${filename}`;
            form.submit();
        }
    });
}

// Actualizar contador de backups
document.addEventListener('DOMContentLoaded', function() {
    const backupCount = document.getElementById('backupCount');
    if (backupCount) {
        const count = {{ count($backups ?? []) }};
        backupCount.textContent = count;
    }

    // Inicializar DataTables
    if (typeof window.SimpleDataTable !== 'undefined') {
        new window.SimpleDataTable(document.getElementById('datatablesSimple'));
    }
});
</script>
@endpush
