@extends('layouts.app')

@section('title','Inventario')

@push('css-datatable')
<link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" type="text/css">
@endpush

@push('css')
@endpush

@section('content')

<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Existencias y Ubicaciones</h1>

    <x-breadcrumb.template>
        <x-breadcrumb.item :href="route('panel')" content="Inicio" />
        <x-breadcrumb.item active='true' content="Existencias" />
    </x-breadcrumb.template>

    @if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    @if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif

    <div class="mb-4">
        <button type="button"
            class="btn btn-primary"
            data-bs-toggle="modal"
            data-bs-target="#verPlanoModal">
            Ver plano
        </button>
    </div>

    <div class="card">
        <div class="card-header">
            <i class="fas fa-table me-1"></i>
            Tabla inventario
        </div>
        <div class="card-body">
            <table id="datatablesSimple" class="table table-striped fs-6">
                <thead>
                    <tr>
                        <th>Producto</th>
                        <th>Stock</th>
                        <th>Ubicación</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($inventario as $item)
                    @php
                        // Extraer solo el nombre del producto del nombre_completo
                        $partes = explode(' - ', $item->producto->nombre_completo);
                        $nombreProducto = '';
                        
                        foreach ($partes as $parte) {
                            if (!str_contains($parte, 'Código:') && !str_contains($parte, 'Modelo:')) {
                                $nombreProducto = $parte;
                                break;
                            }
                        }
                        
                        // Obtener el nombre de la presentación desde la característica
                        $nombrePresentacion = $item->producto->presentacione->caracteristica->nombre ?? 'Sin Modelo';
                    @endphp
                    <tr>
                        <td>
                            <strong>{{ $nombreProducto }}</strong><br>
                            <small class="text-muted">Modelo: {{ $nombrePresentacion }}</small>
                        </td>
                        <td>
                            {{$item->cantidad}}
                        </td>
                        <td>
                            {{$item->ubicacione->nombre}}
                        </td>
                        <td>
                            <div class="d-flex justify-content-around">
                                <!-- Botón para reubicar -->
                                <button type="button" 
                                    class="btn btn-sm btn-success"
                                    data-bs-toggle="modal" 
                                    data-bs-target="#reubicarModal-{{$item->id}}"
                                    title="Reubicar producto">
                                    Reubicar
                                </button>
                            </div>
                        </td>
                    </tr>

                    <!-- Modal para reubicar -->
                    <div class="modal fade" id="reubicarModal-{{$item->id}}" tabindex="-1" aria-labelledby="reubicarModalLabel-{{$item->id}}" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <form action="{{ route('inventario.reubicar', $item->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="reubicarModalLabel-{{$item->id}}">Reubicar Producto</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        @php
                                            // Extraer solo el nombre del producto para el modal
                                            $partesModal = explode(' - ', $item->producto->nombre_completo);
                                            $nombreProductoModal = '';
                                            
                                            foreach ($partesModal as $parte) {
                                                if (!str_contains($parte, 'Código:') && !str_contains($parte, 'Modelo:')) {
                                                    $nombreProductoModal = $parte;
                                                    break;
                                                }
                                            }
                                            
                                            // Obtener el nombre de la presentación para el modal
                                            $nombrePresentacionModal = $item->producto->presentacione->caracteristica->nombre ?? 'Sin Modelo';
                                        @endphp
                                        <p><strong>Producto:</strong> {{ $nombreProductoModal }}</p>
                                        <p><strong>Modelo:</strong> {{ $nombrePresentacionModal }}</p>
                                        <p><strong>Ubicación actual:</strong> {{$item->ubicacione->nombre}}</p>
                                        <p><strong>Stock actual:</strong> {{$item->cantidad}}</p>
                                        
                                        <div class="mb-3">
                                            <label for="nueva_ubicacion-{{$item->id}}" class="form-label">Nueva ubicación:</label>
                                            <select name="ubicacione_id" id="nueva_ubicacion-{{$item->id}}" class="form-select" required>
                                                <option value="">Seleccione una ubicación</option>
                                                @foreach ($ubicaciones as $ubicacion)
                                                <option value="{{$ubicacion->id}}" {{ $ubicacion->id == $item->ubicacione_id ? 'disabled' : '' }}>
                                                    {{$ubicacion->nombre}}
                                                </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                                        <button type="submit" class="btn btn-primary">Reubicar</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal del plano -->
    <div class="modal fade" id="verPlanoModal"
        tabindex="-1"
        aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Plano de Ubicaciones</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-12 text-center">
                            <img src="{{ asset('assets/img/plano.png')}}" alt="Plano de ubicaciones"
                                class="img-fluid img-thumbail border rounded">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" type="text/javascript"></script>
<script src="{{ asset('js/datatables-simple-demo.js') }}"></script>
@endpush