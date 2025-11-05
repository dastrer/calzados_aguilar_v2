@extends('layouts.app')

@section('title','proveedores')

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
    .compras-details {
        background-color: #f8f9fa;
        border-left: 4px solid #007bff;
    }
    .compras-table {
        font-size: 0.875rem;
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
</style>
@endpush

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Proveedores</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Proveedores</li>
    </ol>

    @can('crear-proveedore')
    <div class="mb-4">
        <a href="{{route('proveedores.create')}}">
            <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
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
            <form action="{{ route('proveedores.index') }}" method="GET" class="row g-3">
                <div class="col-md-4">
                    <label for="tipo_persona" class="form-label">Tipo de Persona</label>
                    <select class="form-select" id="tipo_persona" name="tipo_persona">
                        <option value="">Todos los tipos</option>
                        <option value="JURIDICA" {{ request('tipo_persona') == 'JURIDICA' ? 'selected' : '' }}>Jurídica</option>
                        <option value="NATURAL" {{ request('tipo_persona') == 'NATURAL' ? 'selected' : '' }}>Natural</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="estado" class="form-label">Estado</label>
                    <select class="form-select" id="estado" name="estado">
                        <option value="">Todos los estados</option>
                        <option value="1" {{ request('estado') == '1' ? 'selected' : '' }}>Activo</option>
                        <option value="0" {{ request('estado') == '0' ? 'selected' : '' }}>Eliminado</option>
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <div class="btn-group" role="group">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i> Buscar
                        </button>
                        <a href="{{ route('proveedores.index') }}" class="btn btn-secondary">
                            <i class="fas fa-undo me-1"></i> Limpiar
                        </a>
                    </div>
                </div>
            </form>

            @if(request()->has('tipo_persona') || request()->has('estado'))
            <div class="mt-3">
                <small class="text-muted">
                    <strong>Filtros aplicados:</strong>
                    @if(request('tipo_persona'))
                        Tipo: {{ request('tipo_persona') == 'JURIDICA' ? 'Jurídica' : 'Natural' }}
                    @endif
                    @if(request('estado'))
                        {{ request('tipo_persona') ? ' | ' : '' }}
                        Estado: {{ request('estado') == '1' ? 'Activo' : 'Eliminado' }}
                    @endif
                </small>
            </div>
            @endif
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla proveedores
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped">
                <thead>
                    <tr>
                        <th></th>
                        <th>Razón Social</th>
                        <th>Teléfono</th>
                        <th>Dirección</th>
                        <th>Documento</th>
                        <th>Tipo de persona</th>
                        <th>Compras</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($proveedores as $item)
                    <tr class="clickable-row" onclick="toggleCompras({{ $item->id }})">
                        <td>
                            <i class="fas fa-chevron-right arrow-icon" id="arrow-{{ $item->id }}"></i>
                        </td>
                        <td>
                            {{$item->persona->razon_social}}
                        </td>
                        <td>
                            {{$item->persona->telefono ?? 'N/A'}}
                        </td>
                        <td>
                            {{$item->persona->direccion}}
                        </td>
                        <td>
                            <p class="fw-semibold mb-1">{{$item->persona->documento->nombre}}</p>
                            <p class="text-muted mb-0">{{$item->persona->numero_documento}}</p>
                        </td>
                        <td>
                            {{$item->persona->tipo->value}}
                        </td>
                        <td>
                            @php
                                $comprasCount = $item->compras->count();
                                $totalCompras = $item->compras->sum('total');
                            @endphp
                            <span class="badge bg-{{ $comprasCount > 0 ? 'primary' : 'secondary' }}">
                                {{ $comprasCount }} compra{{ $comprasCount != 1 ? 's' : '' }}
                            </span>
                            @if($comprasCount > 0)
                                <br>
                                <small class="text-muted">Bs. {{ number_format($totalCompras, 2) }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge rounded-pill text-bg-{{ $item->persona->estado ? 'success' : 'danger' }}">
                                {{ $item->persona->estado ? 'Activo' : 'Eliminado'}}</span>
                        </td>
                        <td onclick="event.stopPropagation();">
                            <div class="d-flex justify-content-around">
                                <div>
                                    <button title="Opciones" class="btn btn-datatable btn-icon btn-transparent-dark me-2" data-bs-toggle="dropdown" aria-expanded="false">
                                        <svg class="svg-inline--fa fa-ellipsis-vertical" aria-hidden="true" focusable="false" data-prefix="fas" data-icon="ellipsis-vertical" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 128 512" data-fa-i2svg="">
                                            <path fill="currentColor" d="M56 472a56 56 0 1 1 0-112 56 56 0 1 1 0 112zm0-160a56 56 0 1 1 0-112 56 56 0 1 1 0 112zM0 96a56 56 0 1 1 112 0A56 56 0 1 1 0 96z"></path>
                                        </svg>
                                    </button>
                                    <ul class="dropdown-menu text-bg-light" style="font-size: small;">
                                        <!-----Editar proveedore--->
                                        @can('editar-proveedore')
                                        <li><a class="dropdown-item" href="{{route('proveedores.edit',['proveedore'=>$item])}}">Editar</a></li>
                                        @endcan
                                    </ul>
                                </div>
                                <div>
                                    <!----Separador----->
                                    <div class="vr"></div>
                                </div>
                                <div>
                                    <!------Eliminar proveedore---->
                                    @can('eliminar-proveedore')
                                    @if ($item->persona->estado == 1)
                                    <button title="Eliminar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}" class="btn btn-datatable btn-icon btn-transparent-dark">
                                        <svg class="svg-inline--fa fa-trash-can" aria-hidden="true" focusable="false" data-prefix="far" data-icon="trash-can" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">
                                            <path fill="currentColor" d="M170.5 51.6L151.5 80h145l-19-28.4c-1.5-2.2-4-3.6-6.7-3.6H177.1c-2.7 0-5.2 1.3-6.7 3.6zm147-26.6L354.2 80H368h48 8c13.3 0 24 10.7 24 24s-10.7 24-24 24h-8V432c0 44.2-35.8 80-80 80H112c-44.2 0-80-35.8-80-80V128H24c-13.3 0-24-10.7-24-24S10.7 80 24 80h8H80 93.8l36.7-55.1C140.9 9.4 158.4 0 177.1 0h93.7c18.7 0 36.2 9.4 46.6 24.9zM80 128V432c0 17.7 14.3 32 32 32H336c17.7 0 32-14.3 32-32V128H80zm80 64V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16zm80 0V400c0 8.8-7.2 16-16 16s-16-7.2-16-16V192c0-8.8 7.2-16 16-16s16 7.2 16 16z"></path>
                                        </svg>
                                    </button>
                                    @else
                                    <button title="Restaurar" data-bs-toggle="modal" data-bs-target="#confirmModal-{{$item->id}}" class="btn btn-datatable btn-icon btn-transparent-dark">
                                        <i class="fa-solid fa-rotate"></i>
                                    </button>
                                    @endif
                                    @endcan
                                </div>
                            </div>
                        </td>
                    </tr>

                    <!-- Fila desplegable para compras -->
                    <tr id="compras-{{ $item->id }}" style="display: none;">
                        <td colspan="9" class="p-0">
                            <div class="compras-details p-3">
                                <h6 class="mb-3">
                                    <i class="fas fa-shopping-cart me-2"></i>
                                    Compras de {{ $item->persona->razon_social }}
                                </h6>

                                @if($item->compras->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm compras-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th># Compra</th>
                                                    <th>Fecha</th>
                                                    <th>Comprobante</th>
                                                    <th>Total</th>
                                                    <th>Método Pago</th>
                                                    <th>Usuario</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($item->compras as $compra)
                                                <tr>
                                                    <td><strong>#{{ $compra->id }}</strong></td>
                                                    <td>{{ \Carbon\Carbon::parse($compra->fecha_hora)->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $compra->numero_comprobante }}</td>
                                                    <td><strong>Bs. {{ number_format($compra->total, 2) }}</strong></td>
                                                    <td>
                                                        <span class="badge bg-{{ $compra->metodo_pago === 'EFECTIVO' ? 'success' : 'info' }}">
                                                            {{ $compra->metodo_pago }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $compra->user->name ?? 'N/A' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-2 p-2 bg-white rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small><strong>Total de compras:</strong> {{ $item->compras->count() }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <small><strong>Monto total:</strong>
                                                    <span class="text-success">Bs. {{ number_format($item->compras->sum('total'), 2) }}</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center py-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No se encontraron compras para este proveedor
                                    </div>
                                @endif
                            </div>
                        </td>
                    </tr>

                    <!-- Modal de confirmación-->
                    <div class="modal fade" id="confirmModal-{{$item->id}}" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">Mensaje de confirmación</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    {{ $item->persona->estado == 1 ? '¿Seguro que quieres eliminar el proveedor?' : '¿Seguro que quieres restaurar el proveedor?' }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('proveedores.destroy',['proveedore'=>$item->persona->id]) }}" method="post">
                                        @method('DELETE')
                                        @csrf
                                        <button type="submit" class="btn btn-danger">Confirmar</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>

<script>
function toggleCompras(proveedorId) {
    const comprasRow = document.getElementById(`compras-${proveedorId}`);
    const arrowIcon = document.getElementById(`arrow-${proveedorId}`);

    if (comprasRow.style.display === 'none') {
        comprasRow.style.display = 'table-row';
        arrowIcon.classList.add('rotated');
    } else {
        comprasRow.style.display = 'none';
        arrowIcon.classList.remove('rotated');
    }
}
</script>
@endpush
