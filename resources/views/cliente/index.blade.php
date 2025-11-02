@extends('layouts.app')

@section('title','clientes')

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
    .ventas-details {
        background-color: #f8f9fa;
        border-left: 4px solid #28a745;
    }
    .ventas-table {
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
    <h1 class="mt-4 text-center">Clientes</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item active">Clientes</li>
    </ol>

    @can('crear-cliente')
    <div class="mb-4">
        <a href="{{route('clientes.create')}}">
            <button type="button" class="btn btn-primary">Añadir nuevo registro</button>
        </a>
    </div>
    @endcan

    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla clientes
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped fs-6">
                <thead>
                    <tr>
                        <th></th>
                        <th>Nombre</th>
                        <th>Dirección</th>
                        <th>Documento</th>
                        <th>Tipo de persona</th>
                        <th>Ventas</th>
                        <th>Estado</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($clientes as $item)
                    <tr class="clickable-row" onclick="toggleVentas({{ $item->id }})">
                        <td>
                            <i class="fas fa-chevron-right arrow-icon" id="arrow-{{ $item->id }}"></i>
                        </td>
                        <td>
                            {{$item->persona->razon_social}}
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
                                $ventasCount = $item->ventas->count();
                                $totalVentas = $item->ventas->sum('total');
                            @endphp
                            <span class="badge bg-{{ $ventasCount > 0 ? 'success' : 'secondary' }}">
                                {{ $ventasCount }} venta{{ $ventasCount != 1 ? 's' : '' }}
                            </span>
                            @if($ventasCount > 0)
                                <br>
                                <small class="text-muted">Bs. {{ number_format($totalVentas, 2) }}</small>
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
                                        <!-----Editar cliente--->
                                        @can('editar-cliente')
                                        <li><a class="dropdown-item" href="{{route('clientes.edit',['cliente'=>$item])}}">Editar</a></li>
                                        @endcan
                                    </ul>
                                </div>

                                <div> <!----Separador----->
                                    <div class="vr"></div>
                                </div>

                                <div> <!------Eliminar cliente---->
                                    @can('eliminar-cliente')
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

                    <!-- Fila desplegable para ventas -->
                    <tr id="ventas-{{ $item->id }}" style="display: none;">
                        <td colspan="8" class="p-0">
                            <div class="ventas-details p-3">
                                <h6 class="mb-3">
                                    <i class="fas fa-shopping-bag me-2"></i>
                                    Ventas de {{ $item->persona->razon_social }}
                                </h6>
                                
                                @if($item->ventas->count() > 0)
                                    <div class="table-responsive">
                                        <table class="table table-sm ventas-table">
                                            <thead class="table-light">
                                                <tr>
                                                    <th># Venta</th>
                                                    <th>Fecha</th>
                                                    <th>Comprobante</th>
                                                    <th>Total</th>
                                                    <th>Método Pago</th>
                                                    <th>Usuario</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($item->ventas as $venta)
                                                <tr>
                                                    <td><strong>#{{ $venta->id }}</strong></td>
                                                    <td>{{ \Carbon\Carbon::parse($venta->fecha_hora)->format('d/m/Y H:i') }}</td>
                                                    <td>{{ $venta->numero_comprobante }}</td>
                                                    <td><strong>Bs. {{ number_format($venta->total, 2) }}</strong></td>
                                                    <td>
                                                        <span class="badge bg-{{ $venta->metodo_pago === 'EFECTIVO' ? 'success' : 'info' }}">
                                                            {{ $venta->metodo_pago }}
                                                        </span>
                                                    </td>
                                                    <td>{{ $venta->user->name ?? 'N/A' }}</td>
                                                </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                    <div class="mt-2 p-2 bg-white rounded">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <small><strong>Total de ventas:</strong> {{ $item->ventas->count() }}</small>
                                            </div>
                                            <div class="col-md-6">
                                                <small><strong>Monto total:</strong> 
                                                    <span class="text-success">Bs. {{ number_format($item->ventas->sum('total'), 2) }}</span>
                                                </small>
                                            </div>
                                        </div>
                                    </div>
                                @else
                                    <div class="alert alert-info text-center py-2">
                                        <i class="fas fa-info-circle me-2"></i>
                                        No se encontraron ventas para este cliente
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
                                    {{ $item->persona->estado == 1 ? '¿Seguro que quieres eliminar el cliente?' : '¿Seguro que quieres restaurar el cliente?' }}
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                                    <form action="{{ route('clientes.destroy',['cliente'=>$item->persona->id]) }}" method="post">
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
function toggleVentas(clienteId) {
    const ventasRow = document.getElementById(`ventas-${clienteId}`);
    const arrowIcon = document.getElementById(`arrow-${clienteId}`);
    
    if (ventasRow.style.display === 'none') {
        ventasRow.style.display = 'table-row';
        arrowIcon.classList.add('rotated');
    } else {
        ventasRow.style.display = 'none';
        arrowIcon.classList.remove('rotated');
    }
}
</script>
@endpush