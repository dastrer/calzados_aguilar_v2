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
    #box-complemento {
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
                        <input required type="text" name="razon_social" id="razon_social" class="form-control" value="{{old('razon_social')}}">
                        @error('razon_social')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-------Nombre completo (Natural)------->
                    <div class="col-12" id="box-nombre-completo">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="nombres" class="form-label">Nombres:</label>
                                <input required type="text" name="nombres" id="nombres" class="form-control solo-letras" value="{{old('nombres')}}">
                                @error('nombres')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="apellido_paterno" class="form-label">Apellido Paterno:</label>
                                <input required type="text" name="apellido_paterno" id="apellido_paterno" class="form-control solo-letras" value="{{old('apellido_paterno')}}">
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
                        <x-forms.input id="email" type='email' labelText='Correo eléctronico' />
                    </div>

                    <!------Telefono---->
                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono:</label>
                        <input type="text" name="telefono" id="telefono" class="form-control" value="{{old('telefono')}}" placeholder="8 dígitos (6, 7 o 2) o + hasta 12 dígitos">
                        @error('telefono')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                        <small class="text-muted">Formato nacional: 8 dígitos comenzando con 6, 7 o 2 | Internacional: + seguido de hasta 12 dígitos</small>
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

                    <!------Número de documento y complemento------>
                    <div class="col-md-3" id="box-numero-documento">
                        <label for="numero_documento" class="form-label">Número de documento:</label>
                        <input required type="text" name="numero_documento" id="numero_documento" class="form-control solo-numeros" value="{{old('numero_documento')}}">
                        @error('numero_documento')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-3" id="box-complemento">
                        <label for="complemento" class="form-label">Complemento:</label>
                        <input type="text" name="complemento" id="complemento" class="form-control solo-letras-complemento" value="{{old('complemento')}}" maxlength="2" placeholder="Opcional (2 letras)">
                        @error('complemento')
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
        let esCI = false;

        // Validación para solo letras
        $('.solo-letras').on('input', function() {
            $(this).val($(this).val().replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, ''));
        });

        // Validación para solo números
        $('.solo-numeros').on('input', function() {
            $(this).val($(this).val().replace(/[^0-9]/g, ''));
        });

        // Validación para complemento (solo letras, máximo 2)
        $('.solo-letras-complemento').on('input', function() {
            $(this).val($(this).val().replace(/[^a-zA-Z]/g, '').toUpperCase().substring(0, 2));
        });

        // Manejar cambio de tipo de proveedor
        $('#tipo').on('change', function() {
            let selectValue = $(this).val();
            
            if (selectValue == 'NATURAL') {
                $('#box-razon-social').hide();
                $('#box-nombre-completo').show();
                $('#razon_social').prop('required', false);
                $('#nombres').prop('required', true);
                $('#apellido_paterno').prop('required', true);
            } else if (selectValue == 'JURIDICA') {
                $('#box-razon-social').show();
                $('#box-nombre-completo').hide();
                $('#razon_social').prop('required', true);
                $('#nombres').prop('required', false);
                $('#apellido_paterno').prop('required', false);
            }
        });

        // Manejar cambio de tipo de documento
        $('#documento_id').on('change', function() {
            esCI = ($(this).val() == '1');
            
            if (esCI) {
                $('#box-complemento').show();
                $('#box-numero-documento').removeClass('col-md-6').addClass('col-md-3');
            } else {
                $('#box-complemento').hide();
                $('#box-numero-documento').removeClass('col-md-3').addClass('col-md-6');
            }
        });

        // Validación y formateo de teléfono
        $('#telefono').on('input', function() {
            let value = $(this).val().replace(/\s/g, '');
            
            if (value.startsWith('+')) {
                if (value.length > 13) value = value.slice(0, 13);
            } else {
                value = value.replace(/\+/g, '');
                if (value.length > 8) value = value.slice(0, 8);
            }
            
            $(this).val(value);
        });

        // Formatear datos antes de enviar - UNIR CAMPOS PARA PERSONA NATURAL
        $('form').on('submit', function() {
            const tipoProveedor = $('#tipo').val();
            const numero = $('#numero_documento').val();
            const complemento = $('#complemento').val();
            const telefono = $('#telefono').val();
            
            // Preparar número de documento completo para CI
            if (esCI && complemento) {
                $('#numero_documento').val(numero + '-' + complemento.toUpperCase());
            }
            
            // Formatear teléfono (eliminar espacios)
            if (telefono) {
                $('#telefono').val(telefono.replace(/\s/g, ''));
            }
            
            // UNIR CAMPOS PARA PERSONA NATURAL
            if (tipoProveedor === 'NATURAL') {
                const nombres = $('#nombres').val().trim();
                const apellidoPaterno = $('#apellido_paterno').val().trim();
                const apellidoMaterno = $('#apellido_materno').val().trim();
                
                // Unir los campos en razon_social
                let razonSocialCompleta = nombres + ' ' + apellidoPaterno;
                if (apellidoMaterno) {
                    razonSocialCompleta += ' ' + apellidoMaterno;
                }
                
                // Asignar al campo razon_social que es el que espera el backend
                $('#razon_social').val(razonSocialCompleta);
            }
            
            return true;
        });

        // Inicializar
        $('#tipo').trigger('change');
        
        @if(old('documento_id'))
            esCI = ("{{ old('documento_id') }}" == '1');
            if (esCI) {
                $('#box-complemento').show();
                $('#box-numero-documento').removeClass('col-md-6').addClass('col-md-3');
            } else {
                $('#box-complemento').hide();
                $('#box-numero-documento').removeClass('col-md-3').addClass('col-md-6');
            }
        @else
            $('#documento_id').trigger('change');
        @endif
    });
</script>
@endpush