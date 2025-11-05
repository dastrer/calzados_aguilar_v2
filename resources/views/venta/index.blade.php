@extends('layouts.app')

@section('title','ventas')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush
@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
</style>
@endpush

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Ventas</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Ventas</li>
    </ol>

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

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script>
    // Simple-DataTables
    // https://github.com/fiduswriter/Simple-DataTables/wiki
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
</script>
@endpush
