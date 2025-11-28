@extends('layouts.app')

@section('title','Panel Principal')

@push('css')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
    .stat-card {
        border-radius: 15px;
        transition: all 0.3s ease;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        height: 210px; /* Altura fija para todas las cards */
        display: flex;
        flex-direction: column;
    }
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    .stat-card .card-body {
        flex: 1;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
        padding: 1.25rem;
    }
    .stat-card .card-footer {
        padding: 0.75rem 1.25rem;
        background: rgba(255, 255, 255, 0.1);
        border-top: 1px solid rgba(255, 255, 255, 0.2);
    }
    .stat-icon {
        font-size: 2.5rem;
        opacity: 0.9;
        transition: all 0.3s ease;
        flex-shrink: 0;
    }
    .stat-card:hover .stat-icon {
        transform: scale(1.1);
    }
    .stat-number {
        font-size: 2.2rem;
        font-weight: 800;
        margin-bottom: 0;
        line-height: 1;
    }
    .stat-label {
        font-size: 0.9rem;
        opacity: 0.8;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 0.25rem;
    }
    .stat-small {
        font-size: 0.8rem;
        opacity: 0.9;
        margin-top: 0.25rem;
    }
    .alert-card {
        border-left: 5px solid #e74a3b;
        animation: pulse 2s infinite;
    }
    @keyframes pulse {
        0% { box-shadow: 0 0 0 0 rgba(231, 74, 59, 0.4); }
        70% { box-shadow: 0 0 0 10px rgba(231, 74, 59, 0); }
        100% { box-shadow: 0 0 0 0 rgba(231, 74, 59, 0); }
    }
    .chart-card {
        border-radius: 15px;
        border: none;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .chart-card:hover {
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
    }
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    .mini-table {
        font-size: 0.85rem;
    }
    .mini-table .table th {
        border-top: none;
        font-weight: 600;
        color: #6c757d;
    }
    .periodo-selector {
        background: #f8f9fa;
        border-radius: 10px;
        padding: 0.5rem;
    }
    .btn-periodo {
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-periodo.active {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        box-shadow: 0 4px 15px rgba(102, 126, 234, 0.4);
    }
    .trend-up {
        color: #1cc88a;
    }
    .trend-down {
        color: #e74a3b;
    }
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    .bg-gradient-success {
        background: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
    }
    .bg-gradient-info {
        background: linear-gradient(135deg, #36b9cc 0%, #258391 100%);
    }
    .bg-gradient-warning {
        background: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
    }
    .bg-gradient-danger {
        background: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
    }
    .bg-gradient-dark {
        background: linear-gradient(135deg, #5a5c69 0%, #3a3b45 100%);
    }
    .card-hover {
        transition: all 0.3s ease;
        cursor: pointer;
    }
    .card-hover:hover {
        transform: translateY(-3px);
    }
    .card-content {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        width: 100%;
    }
    .card-text-content {
        flex: 1;
        min-width: 0;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <!-- HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <br>
            <h1 class="h3 mb-0 text-gray-800">Panel de Control</h1>
            <p class="text-muted mb-0">Resumen</p>
        </div>
        <div class="text-end">
            <small class="text-muted">Actualizado: {{ now()->format('d/m/Y H:i') }}</small>
        </div>
    </div>

    <!-- ALERTA STOCK CRÍTICO -->
    @if($totalProductosStockBajo > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card alert-card border-left-danger shadow">
                <div class="card-body py-3">
                    <div class="row align-items-center">
                        <div class="col-auto">
                            <i class="fas fa-exclamation-triangle fa-2x text-danger"></i>
                        </div>
                        <div class="col">
                            <h5 class="card-title text-danger mb-1">
                                <i class="fas fa-bell me-2"></i>Alerta del Sistema
                            </h5>
                            <p class="card-text mb-0">
                                <strong>{{ $totalProductosStockBajo }}</strong> productos con stock crítico requieren atención inmediata.
                            </p>
                        </div>
                        <div class="col-auto">
                            <a href="{{ route('productos.index') }}" class="btn btn-sm btn-danger">
                                <i class="fas fa-boxes me-1"></i>Revisar Stock
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- CARDS PRINCIPALES - MISMO TAMAÑO -->
    <div class="row">
        <!-- PRODUCTOS BAJO STOCK POR MODELOS -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card stat-card bg-gradient-danger text-white card-hover">
                <div class="card-body">
                    <div class="card-content">
                        <div class="card-text-content">
                            <div class="stat-label">Stock Bajo</div>
                            <div class="stat-number">{{ $productosBajoStockModelos->count() }}</div>
                            <div class="stat-small">Productos < 10 unidades</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-boxes"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('productos.index') }}" class="text-white text-decoration-none small">
                        Ver detalles <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- PRODUCTOS BAJO STOCK POR CATEGORÍA -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card stat-card bg-gradient-warning text-white card-hover">
                <div class="card-body">
                    <div class="card-content">
                        <div class="card-text-content">
                            <div class="stat-label">Stock Crítico</div>
                            <div class="stat-number">{{ $totalProductosStockBajo }}</div>
                            <div class="stat-small">Productos < 5 unidades</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-exclamation-triangle"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('productos.index') }}" class="text-white text-decoration-none small">
                        Ver detalles <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- ALTA DEMANDA -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card stat-card bg-gradient-success text-white card-hover">
                <div class="card-body">
                    <div class="card-content">
                        <div class="card-text-content">
                            <div class="stat-label">Alta Demanda</div>
                            <div class="stat-number">{{ $productosAltaDemanda->count() }}</div>
                            <div class="stat-small">Últimos 30 días</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-chart-line"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('ventas.index') }}" class="text-white text-decoration-none small">
                        Ver detalles <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- COMPRAS DEL MES -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card stat-card bg-gradient-info text-white card-hover">
                <div class="card-body">
                    <div class="card-content">
                        <div class="card-text-content">
                            <div class="stat-label">Compras Mes</div>
                            <div class="stat-number">{{ $comprasMes }}</div>
                            <div class="stat-small">{{ now()->format('F') }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('compras.index') }}" class="text-white text-decoration-none small">
                        Ver detalles <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- VENTAS DEL DÍA -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card stat-card bg-gradient-primary text-white card-hover">
                <div class="card-body">
                    <div class="card-content">
                        <div class="card-text-content">
                            <div class="stat-label">Ventas Hoy</div>
                            <div class="stat-number">{{ $ventasHoy }}</div>
                            <div class="stat-small">{{ now()->format('d/m/Y') }}</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-cash-register"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('ventas.index') }}" class="text-white text-decoration-none small">
                        Ver detalles <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>

        <!-- ALERTAS TOTAL -->
        <div class="col-xl-2 col-md-4 col-sm-6 mb-4">
            <div class="card stat-card bg-gradient-dark text-white card-hover">
                <div class="card-body">
                    <div class="card-content">
                        <div class="card-text-content">
                            <div class="stat-label">Alertas Activas</div>
                            <div class="stat-number">{{ $totalProductosStockBajo }}</div>
                            <div class="stat-small">Requieren atención</div>
                        </div>
                        <div class="stat-icon">
                            <i class="fas fa-bell"></i>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0">
                    <a href="{{ route('productos.index') }}" class="text-white text-decoration-none small">
                        Ver alertas <i class="fas fa-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- GRÁFICOS PRINCIPALES -->
    <div class="row">
        <!-- GRÁFICO DE VENTAS DINÁMICO -->
        <div class="col-xl-8 mb-4">
            <div class="card chart-card">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-chart-line text-primary me-2"></i>
                            <span id="ventasChartTitle">Análisis de Ventas - Últimos 7 Días</span>
                        </h5>
                        <div class="periodo-selector">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-periodo active" data-periodo="dia" data-tipo="ventas">
                                    7 Días
                                </button>
                                <button type="button" class="btn btn-periodo" data-periodo="mes" data-tipo="ventas">
                                    Mes
                                </button>
                                <button type="button" class="btn btn-periodo" data-periodo="anio" data-tipo="ventas">
                                    Año
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="ventasDinamicoChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- GRÁFICO STOCK CRÍTICO -->
        <div class="col-xl-4 mb-4">
            <div class="card chart-card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle text-warning me-2"></i>
                        Productos con Stock Crítico
                    </h5>
                </div>
                <div class="card-body">
                    @if($stockCritico->count() > 0)
                    <div class="chart-container">
                        <canvas id="stockCriticoChart"></canvas>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-check-circle fa-3x text-success mb-3"></i>
                        <p class="text-muted">No hay productos con stock crítico</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- SEGUNDA FILA DE GRÁFICOS -->
    <div class="row">
        <!-- GRÁFICO DE COMPRAS DINÁMICO -->
        <div class="col-xl-6 mb-4">
            <div class="card chart-card">
                <div class="card-header bg-white py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-shopping-cart text-info me-2"></i>
                            <span id="comprasChartTitle">Análisis de Compras - Últimos 7 Días</span>
                        </h5>
                        <div class="periodo-selector">
                            <div class="btn-group btn-group-sm" role="group">
                                <button type="button" class="btn btn-periodo active" data-periodo="dia" data-tipo="compras">
                                    7 Días
                                </button>
                                <button type="button" class="btn btn-periodo" data-periodo="mes" data-tipo="compras">
                                    Mes
                                </button>
                                <button type="button" class="btn btn-periodo" data-periodo="anio" data-tipo="compras">
                                    Año
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="chart-container">
                        <canvas id="comprasDinamicoChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- MOVIMIENTO DE CAJA -->
        <div class="col-xl-6 mb-4">
            <div class="card chart-card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-money-bill-wave text-success me-2"></i>
                        Movimiento de Caja - Últimos 30 Días
                    </h5>
                </div>
                <div class="card-body">
                    @if($movimientoCaja->count() > 0)
                    <div class="chart-container">
                        <canvas id="movimientoCajaChart"></canvas>
                    </div>
                    @else
                    <div class="text-center py-5">
                        <i class="fas fa-info-circle fa-3x text-info mb-3"></i>
                        <p class="text-muted">No hay datos de movimiento de caja</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- MINITABLAS INFORMATIVAS -->
    <div class="row">
        <!-- ÚLTIMOS PRODUCTOS VENDIDOS -->
        <div class="col-xl-6 mb-4">
            <div class="card chart-card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-clock text-primary me-2"></i>
                        Últimos Productos Vendidos
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mini-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                    <th>Precio</th>
                                    <th>Fecha</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($ultimosProductosVendidos as $producto)
                                <tr>
                                    <td>
                                        <i class="fas fa-cube text-muted me-2"></i>
                                        {{ Str::limit($producto->nombre, 25) }}
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $producto->cantidad }}</span>
                                    </td>
                                    <td>
                                        <strong class="text-success">Bs. {{ number_format($producto->precio_venta, 2) }}</strong>
                                    </td>
                                    <td>
                                        <small class="text-muted">{{ \Carbon\Carbon::parse($producto->created_at)->format('d/m H:i') }}</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted py-3">
                                        <i class="fas fa-info-circle me-2"></i>No hay ventas recientes
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- PRODUCTOS POPULARES -->
        <div class="col-xl-6 mb-4">
            <div class="card chart-card">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star text-warning me-2"></i>
                        Productos Más Populares
                    </h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm mini-table">
                            <thead>
                                <tr>
                                    <th>Producto</th>
                                    <th>Total Vendido</th>
                                    <th>Tendencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($productosPopulares as $producto)
                                <tr>
                                    <td>
                                        <i class="fas fa-fire text-danger me-2"></i>
                                        {{ Str::limit($producto->nombre, 30) }}
                                    </td>
                                    <td>
                                        <strong>{{ $producto->total_vendido }}</strong> unidades
                                    </td>
                                    <td>
                                        <i class="fas fa-arrow-up trend-up"></i>
                                        <small class="text-muted">Alta demanda</small>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center text-muted py-3">
                                        <i class="fas fa-info-circle me-2"></i>No hay datos de productos populares
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
<script>
    // Gráficos principales
    let ventasChart = null;
    let comprasChart = null;

    // Datos para gráficos dinámicos
    const chartData = {
        ventas: {
            'dia': {
                labels: @json($ventasPorPeriodo['dia']->pluck('etiqueta')),
                data: @json($ventasPorPeriodo['dia']->pluck('monto_total')),
                title: 'Ventas - Últimos 7 Días'
            },
            'mes': {
                labels: @json($ventasPorPeriodo['mes']->pluck('etiqueta')),
                data: @json($ventasPorPeriodo['mes']->pluck('monto_total')),
                title: 'Ventas - Últimos 12 Meses'
            },
            'anio': {
                labels: @json($ventasPorPeriodo['anio']->pluck('etiqueta')),
                data: @json($ventasPorPeriodo['anio']->pluck('monto_total')),
                title: 'Ventas - Últimos 5 Años'
            }
        },
        compras: {
            'dia': {
                labels: @json($comprasPorPeriodo['dia']->pluck('etiqueta')),
                data: @json($comprasPorPeriodo['dia']->pluck('monto_total')),
                title: 'Compras - Últimos 7 Días'
            },
            'mes': {
                labels: @json($comprasPorPeriodo['mes']->pluck('etiqueta')),
                data: @json($comprasPorPeriodo['mes']->pluck('monto_total')),
                title: 'Compras - Últimos 12 Meses'
            },
            'anio': {
                labels: @json($comprasPorPeriodo['anio']->pluck('etiqueta')),
                data: @json($comprasPorPeriodo['anio']->pluck('monto_total')),
                title: 'Compras - Últimos 5 Años'
            }
        }
    };

    // Inicializar gráficos
    document.addEventListener('DOMContentLoaded', function() {
        inicializarGraficoVentas('dia');
        inicializarGraficoCompras('dia');

        @if($stockCritico->count() > 0)
        inicializarStockCritico();
        @endif

        @if($movimientoCaja->count() > 0)
        inicializarMovimientoCaja();
        @endif

        inicializarEventListeners();
    });

    // Gráfico de Ventas Dinámico
    function inicializarGraficoVentas(periodo) {
        const ctx = document.getElementById('ventasDinamicoChart').getContext('2d');
        const data = chartData.ventas[periodo];

        if (ventasChart) ventasChart.destroy();

        ventasChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Monto Total (Bs.)',
                    data: data.data,
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderColor: 'rgba(102, 126, 234, 1)',
                    borderWidth: 3,
                    pointBackgroundColor: 'rgba(102, 126, 234, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    tension: 0.4,
                    fill: true
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
                                return 'Bs. ' + value.toLocaleString('es-BO');
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

        document.getElementById('ventasChartTitle').textContent = data.title;
    }

    // Gráfico de Compras Dinámico
    function inicializarGraficoCompras(periodo) {
        const ctx = document.getElementById('comprasDinamicoChart').getContext('2d');
        const data = chartData.compras[periodo];

        if (comprasChart) comprasChart.destroy();

        comprasChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Monto Total (Bs.)',
                    data: data.data,
                    backgroundColor: 'rgba(54, 185, 204, 0.8)',
                    borderColor: 'rgba(54, 185, 204, 1)',
                    borderWidth: 2,
                    borderRadius: 5,
                    borderSkipped: false,
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
                                return 'Bs. ' + value.toLocaleString('es-BO');
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

        document.getElementById('comprasChartTitle').textContent = data.title;
    }

    // Gráfico de Stock Crítico
    function inicializarStockCritico() {
        const ctx = document.getElementById('stockCriticoChart').getContext('2d');
        new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: @json($stockCritico->pluck('nombre')),
                datasets: [{
                    data: @json($stockCritico->pluck('stock')),
                    backgroundColor: [
                        '#e74a3b', '#f6c23e', '#4e73df', '#1cc88a', '#36b9cc',
                        '#6f42c1', '#fd7e14', '#e83e8c', '#20c997', '#6610f2'
                    ],
                    borderWidth: 2,
                    borderColor: '#fff'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true
                        }
                    }
                },
                cutout: '60%'
            }
        });
    }

    // Gráfico de Movimiento de Caja
    function inicializarMovimientoCaja() {
        const ctx = document.getElementById('movimientoCajaChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($movimientoCaja->pluck('fecha')->map(function($fecha) {
                    return $fecha; // Ya viene formateado desde el controller
                })),
                datasets: [{
                    label: 'Movimiento Total (Bs.)',
                    data: @json($movimientoCaja->pluck('total_movimiento')),
                    backgroundColor: 'rgba(28, 200, 138, 0.1)',
                    borderColor: 'rgba(28, 200, 138, 1)',
                    borderWidth: 3,
                    pointBackgroundColor: 'rgba(28, 200, 138, 1)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    tension: 0.4,
                    fill: true
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
                                return 'Bs. ' + value.toLocaleString('es-BO');
                            }
                        }
                    }
                }
            }
        });
    }

    // Event Listeners para botones de período
    function inicializarEventListeners() {
        document.querySelectorAll('.btn-periodo').forEach(button => {
            button.addEventListener('click', function() {
                const tipo = this.getAttribute('data-tipo');
                const periodo = this.getAttribute('data-periodo');

                // Actualizar botones activos
                document.querySelectorAll(`.btn-periodo[data-tipo="${tipo}"]`).forEach(btn => {
                    btn.classList.remove('active');
                });
                this.classList.add('active');

                // Actualizar gráfico correspondiente
                if (tipo === 'ventas') {
                    inicializarGraficoVentas(periodo);
                } else if (tipo === 'compras') {
                    inicializarGraficoCompras(periodo);
                }
            });
        });
    }
</script>
@endpush
