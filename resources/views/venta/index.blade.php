@extends('layouts.app')

@section('title','ventas')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush
@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .row-not-space {
        width: 110px;
    }
    .filter-card {
        background-color: #f8f9fa;
        border-left: 4px solid #007bff;
    }
    .clickable-row {
        cursor: pointer;
        transition: background-color 0.2s;
    }
    .clickable-row:hover {
        background-color: #f8f9fa !important;
    }
    .productos-details {
        background-color: #f8f9fa;
        border-left: 4px solid #dc3545;
    }
    .productos-table {
        font-size: 0.875rem;
    }
    .arrow-icon {
        transition: transform 0.3s ease;
    }
    .arrow-icon.rotated {
        transform: rotate(90deg);
    }
    .stat-card {
        border-radius: 10px;
        transition: transform 0.2s;
    }
    .stat-card:hover {
        transform: translateY(-2px);
    }
    .stat-icon {
        font-size: 2rem;
        opacity: 0.8;
    }
    .producto-badge {
        font-size: 0.75rem;
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .chart-container-half {
        position: relative;
        height: 150px;
        width: 100%;
    }
    .chart-card {
        border-radius: 10px;
        box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
        height: 100%;
    }
    .chart-card .card-body {
        padding: 1rem;
    }
    .metodos-pago-card {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        border-left: 4px solid #6c757d;
    }
    .equal-height-row {
        display: flex;
        flex-wrap: wrap;
    }
    .equal-height-row > [class*='col-'] {
        display: flex;
        flex-direction: column;
    }
    .productos-list {
        max-height: 200px;
        overflow-y: auto;
    }
</style>
@endpush

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Ventas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Ventas</li>
    </ol>

    <!-- Sección de Estadísticas -->
    @if(isset($estadisticas))
    <div class="row mb-4">
        <!-- Resumen de Ventas -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Ventas Hoy</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Bs. {{ number_format($estadisticas['ventas_hoy'], 2) }}
                            </div>
                            <small class="text-muted">{{ $estadisticas['cantidad_ventas_hoy'] }} ventas</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-day stat-icon text-primary"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Ventas Semana</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Bs. {{ number_format($estadisticas['ventas_semana'], 2) }}
                            </div>
                            <small class="text-muted">{{ $estadisticas['cantidad_ventas_semana'] }} ventas</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-week stat-icon text-success"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Ventas Mes</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Bs. {{ number_format($estadisticas['ventas_mes'], 2) }}
                            </div>
                            <small class="text-muted">{{ $estadisticas['cantidad_ventas_mes'] }} ventas</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-calendar-alt stat-icon text-info"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card stat-card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Ventas Año</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Bs. {{ number_format($estadisticas['ventas_anio'], 2) }}
                            </div>
                            <small class="text-muted">Total acumulado</small>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-line stat-icon text-warning"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @can('crear-venta')
    <div class="mb-4">
        <a href="{{route('ventas.create')}}">
            <button type="button" class="btn btn-primary">Crear venta</button>
        </a>
         <a href="{{ route('export.excel-ventas-all') }}">
            <button type="button" class="btn btn-success">Exportar en excel</button>
        </a>
    </div>
    @endcan

    <!-- Card de Filtros -->
    <div class="card mb-4 filter-card">
        <div class="card-header">
            <i class="fas fa-filter me-1"></i>
            Filtros de Búsqueda
        </div>
        <div class="card-body">
            <form action="{{ route('ventas.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="fecha" class="form-label">Fecha de Venta</label>
                    <input type="date" class="form-control" id="fecha" name="fecha"
                           value="{{ request('fecha') }}">
                </div>
                <div class="col-md-4">
                    <label for="producto_id" class="form-label">Producto</label>
                    <select class="form-select" id="producto_id" name="producto_id">
                        <option value="">Todos los productos</option>
                        @foreach($productos as $producto)
                            <option value="{{ $producto->id }}"
                                    {{ request('producto_id') == $producto->id ? 'selected' : '' }}>
                                {{ $producto->nombre }} ({{ $producto->codigo }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Buscar
                        </button>
                        <a href="{{ route('ventas.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>

            @if(request()->has('fecha') || request()->has('producto_id'))
            <div class="mt-3">
                <small class="text-muted">
                    <strong>Filtros aplicados:</strong>
                    @if(request('fecha'))
                        Fecha: {{ \Carbon\Carbon::parse(request('fecha'))->format('d/m/Y') }}
                    @endif
                    @if(request('producto_id'))
                        @php
                            $productoSeleccionado = $productos->firstWhere('id', request('producto_id'));
                        @endphp
                        {{ request('fecha') ? ' | ' : '' }}
                        Producto: {{ $productoSeleccionado->nombre ?? 'N/A' }}
                    @endif
                </small>
            </div>
            @endif
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla ventas
            @if($ventas->count() > 0)
                <span class="badge bg-primary ms-2">{{ $ventas->count() }} ventas encontradas</span>
            @endif
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Comprobante</th>
                        <th>Cliente</th>
                        <th>Fecha y hora</th>
                        <th>Vendedor</th>
                        <th>Total</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($ventas as $item)
                    <tr class="clickable-row" onclick="toggleProductosVenta({{ $item->id }})">
                        <td>
                            <i class="fas fa-chevron-right arrow-icon" id="arrow-{{ $item->id }}"></i>
                        </td>
                        <td>
                            <p class="fw-semibold mb-1">
                                {{$item->comprobante->tipo_comprobante}}
                            </p>
                            <p class="text-muted mb-0">
                                {{$item->numero_comprobante}}
                            </p>
                        </td>
                        <td>
                            <p class="fw-semibold mb-1">
                                {{ ucfirst($item->cliente->persona->tipo_persona) }}
                            </p>
                            <p class="text-muted mb-0">
                                {{$item->cliente->persona->razon_social}}
                            </p>
                        </td>
                        <td>
                            <div class="row-not-space">
                                <p class="fw-semibold mb-1">
                                    <span class="m-1"><i class="fa-solid fa-calendar-days"></i></span>
                                    {{$item->fecha}}
                                </p>
                                <p class="fw-semibold mb-0">
                                    <span class="m-1"><i class="fa-solid fa-clock"></i></span>
                                    {{$item->hora}}
                                </p>
                            </div>
                        </td>
                        <td>
                            {{$item->user->name}}
                        </td>
                        <td>
                            <strong>Bs. {{ number_format($item->total, 2) }}</strong>
                        </td>
                        <td onclick="event.stopPropagation();">
                            <div class="btn-group" role="group" aria-label="Basic mixed styles example">

                                @can('mostrar-venta')
                                <form action="{{route('ventas.show', ['venta'=>$item]) }}" method="get">
                                    <button type="submit" class="btn btn-success">
                                        Ver
                                    </button>
                                </form>
                                @endcan

                                <a type="button" class="btn btn-secondary"
                                    href="{{ route('export.pdf-comprobante-venta',['id' => Crypt::encrypt($item->id)]) }}"
                                    target="_blank">
                                    Exportar
                                </a>

                            </div>
                        </td>
                    </tr>

                    <!-- Fila desplegable para productos de la venta -->
                    <tr id="productos-venta-{{ $item->id }}" style="display: none;">
                        <td colspan="7" class="p-0">
                            <div class="productos-details p-3">
                                <h6 class="mb-3">
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    Productos de la Venta #{{ $item->id }}
                                </h6>

                                @if($item->productos->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm productos-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th>Producto</th>
                                                    <th>Código</th>
                                                    <th>Cantidad</th>
                                                    <th>Precio Venta</th>
                                                    <th>Subtotal</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($item->productos as $producto)
                                                <tr>
                                                    <td>
                                                        <strong>{{ $producto->nombre }}</strong>
                                                        @if($producto->marca)
                                                            <br><small class="text-muted">Marca: {{ $producto->marca->caracteristica->nombre }}</small>
                                                        @endif
                                                    </td>
                                                    <td>{{ $producto->codigo }}</td>
                                                    <td>
                                                        <span class="badge bg-primary">{{ $producto->pivot->cantidad }}</span>
                                                    </td>
                                                    <td>Bs. {{ number_format($producto->pivot->precio_venta, 2) }}</td>
                                                    <td>
                                                        <strong>Bs. {{ number_format($producto->pivot->cantidad * $producto->pivot->precio_venta, 2) }}</strong>
                                                    </td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-2 p-2 bg-white rounded">
                                        <div class="row">
                                            <div class="col-md-4">
                                                <small><strong>Total de productos:</strong> {{ $item->productos->count() }}</small>
                                            </div>
                                            <div class="col-md-4">
                                                <small><strong>Método de pago:</strong>
                                                    <span class="badge bg-{{ $item->metodo_pago === 'EFECTIVO' ? 'success' : 'info' }}">
                                                        {{ $item->metodo_pago }}
                                                    </span>
                                                </small>
                                            </div>
                                            <div class="col-md-4">
                                                <small><strong>Total venta:</strong>
                                                    <span class="text-success">Bs. {{ number_format($item->total, 2) }}</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center py-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No se encontraron productos para esta venta
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-4">
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle me-2"></i>
                                @if(request()->has('fecha') || request()->has('producto_id'))
                                    No se encontraron ventas con los filtros aplicados.
                                @else
                                    No hay ventas registradas.
                                @endif
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Gráficos debajo de la tabla -->
    @if(isset($estadisticas))
    <div class="row equal-height-row mb-4">
        <!-- Gráfico de Ventas de los Últimos 7 Días -->
        @if(count($estadisticas['ventas_ultima_semana']) > 0)
        <div class="col-xl-8 col-lg-7 mb-4">
            <div class="card chart-card shadow h-100">
                <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line me-2"></i>
                        Ventas de los Últimos 7 Días
                    </h6>
                    <div class="dropdown no-arrow">
                        <a class="dropdown-toggle" href="#" role="button" id="dropdownMenuLink"
                           data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fas fa-ellipsis-v fa-sm fa-fw text-gray-400"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ventasUltimaSemanaChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Productos Más Vendidos -->
        @if(count($estadisticas['productos_mas_vendidos']) > 0 && !request('producto_id'))
        <div class="col-xl-4 col-lg-5 mb-4">
            <div class="card chart-card shadow h-100">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-star me-2"></i>
                        Productos Más Vendidos
                    </h6>
                </div>
                <div class="card-body d-flex flex-column">
                    <!-- Gráfico más pequeño -->
                    <div class="chart-container-half mb-3">
                        <canvas id="productosMasVendidosChart"></canvas>
                    </div>
                    <!-- Lista de productos con scroll -->
                    <div class="productos-list">
                        @foreach($estadisticas['productos_mas_vendidos'] as $index => $producto)
                        <div class="d-flex justify-content-between align-items-center mb-2 p-2 border-bottom">
                            <div class="d-flex align-items-center">
                                <span class="badge bg-primary me-2">#{{ $index + 1 }}</span>
                                <div>
                                    <div class="small fw-bold">{{ Str::limit($producto->nombre, 20) }}</div>
                                    <div class="text-muted small">Cód: {{ $producto->codigo }}</div>
                                </div>
                            </div>
                            <div class="text-end">
                                <div class="fw-bold text-success">{{ $producto->total_vendido }} und.</div>
                                <div class="text-muted small">Bs. {{ number_format($producto->monto_total, 2) }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
    @endif

    <!-- Métodos de Pago Hoy - Debajo de los gráficos -->
    @if(isset($estadisticas) && ($estadisticas['efectivo_hoy'] > 0 || $estadisticas['qr_hoy'] > 0))
    <div class="card mb-4 metodos-pago-card">
        <div class="card-header">
            <i class="fas fa-credit-card me-1"></i>
            Resumen de Métodos de Pago - Hoy
        </div>
        <div class="card-body">
            <div class="row text-center">
                <div class="col-md-6 mb-3 mb-md-0">
                    <div class="border-end-md">
                        <div class="h4 text-success mb-1">
                            <i class="fas fa-money-bill-wave me-2"></i>
                            Bs. {{ number_format($estadisticas['efectivo_hoy'], 2) }}
                        </div>
                        <div class="text-muted">
                            <span class="badge bg-success me-1">EFECTIVO</span>
                            {{ $estadisticas['efectivo_hoy'] > 0 ? number_format(($estadisticas['efectivo_hoy'] / ($estadisticas['efectivo_hoy'] + $estadisticas['qr_hoy'])) * 100, 1) : 0 }}%
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="h4 text-info mb-1">
                        <i class="fas fa-qrcode me-2"></i>
                        Bs. {{ number_format($estadisticas['qr_hoy'], 2) }}
                    </div>
                    <div class="text-muted">
                        <span class="badge bg-info me-1">QR</span>
                        {{ $estadisticas['qr_hoy'] > 0 ? number_format(($estadisticas['qr_hoy'] / ($estadisticas['efectivo_hoy'] + $estadisticas['qr_hoy'])) * 100, 1) : 0 }}%
                    </div>
                </div>
            </div>
            @if($estadisticas['efectivo_hoy'] > 0 && $estadisticas['qr_hoy'] > 0)
            <div class="mt-3">
                <div class="progress" style="height: 10px;">
                    <div class="progress-bar bg-success" role="progressbar"
                         style="width: {{ ($estadisticas['efectivo_hoy'] / ($estadisticas['efectivo_hoy'] + $estadisticas['qr_hoy'])) * 100 }}%"
                         aria-valuenow="{{ ($estadisticas['efectivo_hoy'] / ($estadisticas['efectivo_hoy'] + $estadisticas['qr_hoy'])) * 100 }}"
                         aria-valuemin="0" aria-valuemax="100">
                    </div>
                    <div class="progress-bar bg-info" role="progressbar"
                         style="width: {{ ($estadisticas['qr_hoy'] / ($estadisticas['efectivo_hoy'] + $estadisticas['qr_hoy'])) * 100 }}%"
                         aria-valuenow="{{ ($estadisticas['qr_hoy'] / ($estadisticas['efectivo_hoy'] + $estadisticas['qr_hoy'])) * 100 }}"
                         aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
                <div class="d-flex justify-content-between mt-1">
                    <small class="text-success">Efectivo</small>
                    <small class="text-info">QR</small>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    // Simple-DataTables
    window.addEventListener('DOMContentLoaded', event => {
        const dataTable = new simpleDatatables.DataTable("#datatablesSimple", {})
    });

    function toggleProductosVenta(ventaId) {
        const productosRow = document.getElementById(`productos-venta-${ventaId}`);
        const arrowIcon = document.getElementById(`arrow-${ventaId}`);

        if (productosRow.style.display === 'none') {
            productosRow.style.display = 'table-row';
            arrowIcon.classList.add('rotated');
        } else {
            productosRow.style.display = 'none';
            arrowIcon.classList.remove('rotated');
        }
    }

    // Gráficos
    document.addEventListener('DOMContentLoaded', function() {
        @if(isset($estadisticas))

        // Gráfico de Productos Más Vendidos (mitad de altura)
        @if(count($estadisticas['productos_mas_vendidos']) > 0 && !request('producto_id'))
        const productosMasVendidosCtx = document.getElementById('productosMasVendidosChart').getContext('2d');
        const productosMasVendidosChart = new Chart(productosMasVendidosCtx, {
            type: 'bar',
            data: {
                labels: [
                    @foreach($estadisticas['productos_mas_vendidos'] as $producto)
                    '{{ Str::limit($producto->nombre, 10) }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Cantidad Vendida',
                    data: [
                        @foreach($estadisticas['productos_mas_vendidos'] as $producto)
                        {{ $producto->total_vendido }},
                        @endforeach
                    ],
                    backgroundColor: [
                        '#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b'
                    ],
                    borderWidth: 0
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + ' unidades';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        },
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 10
                            }
                        }
                    }
                }
            }
        });
        @endif

        // Gráfico de Ventas de los Últimos 7 Días (altura completa)
        @if(count($estadisticas['ventas_ultima_semana']) > 0)
        const ventasSemanaCtx = document.getElementById('ventasUltimaSemanaChart').getContext('2d');
        const ventasSemanaChart = new Chart(ventasSemanaCtx, {
            type: 'line',
            data: {
                labels: [
                    @foreach($estadisticas['ventas_ultima_semana'] as $venta)
                    '{{ \Carbon\Carbon::parse($venta->fecha)->format("d/m") }}',
                    @endforeach
                ],
                datasets: [{
                    label: 'Monto Total (Bs.)',
                    data: [
                        @foreach($estadisticas['ventas_ultima_semana'] as $venta)
                        {{ $venta->monto_total }},
                        @endforeach
                    ],
                    backgroundColor: 'rgba(78, 115, 223, 0.1)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    tension: 0.3,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return 'Bs. ' + context.parsed.y.toLocaleString('es-BO', {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                });
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return 'Bs. ' + value.toLocaleString('es-BO', {
                                    minimumFractionDigits: 0,
                                    maximumFractionDigits: 0
                                });
                            }
                        },
                        grid: {
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });
        @endif

        @endif
    });
</script>
@endpush
