@extends('layouts.app')

@section('title','Crear proveedor')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<style>
    #box-nombre-completo {
        display: none;
    }
    #box-razon-social {
        display: none;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Proveedor</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('proveedores.index')}}">Proveedor</a></li>
        <li class="breadcrumb-item active">Crear proveedor</li>
    </ol>

    <div class="card text-bg-light">
        <form action="{{ route('proveedores.store') }}" method="post">
            @csrf
            <div class="card-body">
                <div class="row g-3">

                    <!----Tipo de persona----->
                    <div class="col-md-6">
                        <label for="tipo" class="form-label">Tipo de proveedor:</label>
                        <select class="form-select" name="tipo" id="tipo">
                            <option value="" selected disabled>Seleccione una opción</option>
                            @foreach ($optionsTipoPersona as $item)
                            <option value="{{$item->value}}" {{ old('tipo') == $item->value ? 'selected' : '' }}>{{$item->name}}</option>
                            @endforeach
                        </select>
                        @error('tipo')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-------Razón social (Jurídica)------->
                    <div class="col-12" id="box-razon-social">
                        <label for="razon_social" class="form-label">Nombre de la empresa:</label>
                        <input type="text" name="razon_social" id="razon_social" class="form-control" value="{{old('razon_social')}}">
                        @error('razon_social')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-------Nombre completo (Natural)------->
                    <div class="col-12" id="box-nombre-completo">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="nombres" class="form-label">Nombres:</label>
                                <input type="text" name="nombres" id="nombres" class="form-control solo-letras" value="{{old('nombres')}}">
                                @error('nombres')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="apellido_paterno" class="form-label">Apellido Paterno:</label>
                                <input type="text" name="apellido_paterno" id="apellido_paterno" class="form-control solo-letras" value="{{old('apellido_paterno')}}">
                                @error('apellido_paterno')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="apellido_materno" class="form-label">Apellido Materno:</label>
                                <input type="text" name="apellido_materno" id="apellido_materno" class="form-control solo-letras" value="{{old('apellido_materno')}}">
                                @error('apellido_materno')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!------Dirección---->
                    <div class="col-12">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" name="direccion" id="direccion" class="form-control" value="{{old('direccion')}}">
                        @error('direccion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!------Email---->
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo electrónico:</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{old('email')}}">
                        @error('email')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!------Telefono---->
                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono:</label>
                        <input type="number" name="telefono" id="telefono" class="form-control" value="{{old('telefono')}}">
                        @error('telefono')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!--------------Documento------->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label">Tipo de documento:</label>
                        <select class="form-select" name="documento_id" id="documento_id">
                            <option value="" selected disabled>Seleccione una opción</option>
                            @foreach ($documentos as $item)
                            <option value="{{$item->id}}" {{ old('documento_id') == $item->id ? 'selected' : '' }}>{{$item->nombre}}</option>
                            @endforeach
                        </select>
                        @error('documento_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="numero_documento" class="form-label">Numero de documento:</label>
                        <input required type="text" name="numero_documento" id="numero_documento" class="form-control" value="{{old('numero_documento')}}">
                        @error('numero_documento')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>

            </div>
            <div class="card-footer text-center">
                <button type="submit" class="btn btn-primary">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        $('#tipo').on('change', function() {
            let selectValue = $(this).val();
            
            if (selectValue == 'NATURAL') {
                $('#box-razon-social').hide();
                $('#box-nombre-completo').show();
                // Hacer obligatorios los campos de persona natural
                $('#nombres').prop('required', true);
                $('#apellido_paterno').prop('required', true);
                $('#apellido_materno').prop('required', false);
                // Quitar requerido de razón social
                $('#razon_social').prop('required', false);
                // Limpiar valor de razón social
                $('#razon_social').val('');
            } else {
                $('#box-razon-social').show();
                $('#box-nombre-completo').hide();
                // Quitar requerido de campos de persona natural
                $('#nombres').prop('required', false);
                $('#apellido_paterno').prop('required', false);
                $('#apellido_materno').prop('required', false);
                // Hacer obligatoria la razón social
                $('#razon_social').prop('required', true);
                // Limpiar valores de nombre individuales
                $('#nombres').val('');
                $('#apellido_paterno').val('');
                $('#apellido_materno').val('');
            }
        });

        // Validación para solo letras en los campos de nombre
        $('.solo-letras').on('input', function() {
            let value = $(this).val();
            // Remover números y caracteres especiales, mantener solo letras y espacios
            let newValue = value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
            $(this).val(newValue);
        });

        // También prevenir pegar contenido con números
        $('.solo-letras').on('paste', function(e) {
            let pastedData = e.originalEvent.clipboardData.getData('text');
            if (/[0-9]/.test(pastedData)) {
                e.preventDefault();
                alert('No se permiten números en este campo');
            }
        });

        // Trigger change al cargar la página para setear el estado inicial
        $('#tipo').trigger('change');
    });
</script>
@endpush