@extends('layouts.app')

@section('title','Kardex')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
@endpush

@push('css')
@endpush

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Inventario</h1>

    <x-breadcrumb.template>
        <x-breadcrumb.item :href="route('panel')" content="Inicio" />
        <x-breadcrumb.item active='true' content="Inventario" />
    </x-breadcrumb.template>

    <div class="mb-3">
        <form action="{{route('kardex.index')}}" method="get">
            <div class="row gy-2 align-items-end">
                <!-- Filtro por Producto -->
                <div class="col-sm-3">
                    <label for="producto_id" class="form-label">Producto</label>
                    <select name="producto_id" id="producto_id"
                        class="form-control selectpicker"
                        data-live-search='true' data-size='3' title='Seleccione producto'>
                        <option value="">Todos los productos</option>
                        @foreach ($productos as $item)
                        <option value="{{$item->id}}" {{$item->id == $producto_id ? 'selected': ''}}>
                            {{$item->nombre}} @if($item->presentacione && $item->presentacione->caracteristica)- {{$item->presentacione->caracteristica->nombre}}@endif
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Modelo (Presentación) -->
                <div class="col-sm-3">
                    <label for="presentacione_id" class="form-label">Modelo</label>
                    <select name="presentacione_id" id="presentacione_id"
                        class="form-control selectpicker"
                        data-live-search='true' data-size='3' title='Seleccione modelo'>
                        <option value="">Todos los modelos</option>
                        @foreach ($presentaciones as $presentacione)
                        <option value="{{$presentacione->id}}" {{$presentacione->id == $presentacione_id ? 'selected': ''}}>
                            @if($presentacione->caracteristica)
                                {{ $presentacione->caracteristica->nombre }}
                            @else
                                {{ $presentacione->nombre ?? 'N/A' }}
                            @endif
                        </option>
                        @endforeach
                    </select>
                </div>

                <!-- Filtro por Tipo de Transacción -->
                <div class="col-sm-3">
                    <label for="tipo_transaccion" class="form-label">Tipo de Transacción</label>
                    <select name="tipo_transaccion" id="tipo_transaccion"
                        class="form-control selectpicker"
                        data-size='3' title='Seleccione tipo'>
                        <option value="">Todos los tipos</option>
                        <option value="compra" {{$tipo_transaccion == 'compra' ? 'selected': ''}}>Compra</option>
                        <option value="venta" {{$tipo_transaccion == 'venta' ? 'selected': ''}}>Venta</option>
                        <option value="apertura" {{$tipo_transaccion == 'apertura' ? 'selected': ''}}>Apertura</option>
                    </select>
                </div>

                <!-- Botones de búsqueda y limpiar -->
                <div class="col-sm-3">
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary flex-fill">
                            <i class="fas fa-search me-1"></i>Buscar
                        </button>
                        <a href="{{route('kardex.index')}}" class="btn btn-secondary flex-fill">
                            <i class="fas fa-times me-1"></i>Limpiar
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>

    @if ($kardex->count())
    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Inventario de productos
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table-striped fs-6">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Modelo</th>
                        <th>Fecha y Hora</th>
                        <th>Transacción</th>
                        <th>Descripción </th>
                        <th>Entrada</th>
                        <th>Salida</th>
                        <th>Stock</th>
                        <th>Costo unitario</th>
                        <th>Costo total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($kardex as $item)
                    <tr>
                        <td>
                            @if($item->producto)
                                {{ $item->producto->nombre }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            @if($item->producto && $item->producto->presentacione && $item->producto->presentacione->caracteristica)
                                {{ $item->producto->presentacione->caracteristica->nombre }}
                            @else
                                <span class="text-muted">N/A</span>
                            @endif
                        </td>
                        <td>
                            {{$item->fecha}} - {{$item->hora}}
                        </td>
                        <td>
                            {{$item->tipo_transaccion}}
                        </td>
                        <td>
                            {{$item->descripcion_transaccion}}
                        </td>
                        <td>
                            {{$item->entrada}}
                        </td>
                        <td>
                            {{$item->salida}}
                        </td>
                        <td>
                            {{$item->saldo}}
                        </td>
                        <td>
                            {{$item->costo_unitario}}
                        </td>
                        <td>
                            {{$item->costo_total}}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>

        </div>
    </div>
    @else
    <p class="text-center my-5">Sin datos</p>
    @endif

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
@endpush