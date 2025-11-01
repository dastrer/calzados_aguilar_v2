@extends('layouts.app')

@section('title','Editar proveedor')

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
    <h1 class="mt-4 text-center">Editar Proveedor</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('proveedores.index')}}">Proveedores</a></li>
        <li class="breadcrumb-item active">Editar proveedor</li>
    </ol>

    <div class="card text-bg-light">
        <form action="{{ route('proveedores.update',['proveedore'=>$proveedore]) }}" method="post" id="proveedorForm">
            @method('PATCH')
            @csrf
            <!-- Encabezado con el tipo de proveedor -->
            <div class="card-header">
                <p>Tipo de proveedor: <span class="fw-bold">
                        {{ strtoupper($proveedore->persona->tipo->value)}}</span></p>
            </div>
            
            <div class="card-body">
                <div class="row g-3">

                    <!----Tipo de persona (campo oculto para mantener funcionalidad)----->
                    <input type="hidden" 
                           name="tipo" 
                           id="tipo" 
                           value="{{ $proveedore->persona->tipo->value }}">

                    <!-------Razón social (Jurídica)------->
                    <div class="col-12" id="box-razon-social">
                        <label for="razon_social" class="form-label">Nombre de la empresa:</label>
                        <input type="text" 
                               name="razon_social" 
                               id="razon_social" 
                               class="form-control" 
                               value="{{ old('razon_social', $proveedore->persona->razon_social) }}">
                        @error('razon_social')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!-------Nombre completo (Natural)------->
                    <div class="col-12" id="box-nombre-completo">
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label for="nombres" class="form-label">Nombres:</label>
                                <input type="text" 
                                       name="nombres" 
                                       id="nombres" 
                                       class="form-control solo-letras" 
                                       value="{{ old('nombres') }}">
                                @error('nombres')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="apellido_paterno" class="form-label">Apellido Paterno:</label>
                                <input type="text" 
                                       name="apellido_paterno" 
                                       id="apellido_paterno" 
                                       class="form-control solo-letras" 
                                       value="{{ old('apellido_paterno') }}">
                                @error('apellido_paterno')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>
                            <div class="col-md-4">
                                <label for="apellido_materno" class="form-label">Apellido Materno:</label>
                                <input type="text" 
                                       name="apellido_materno" 
                                       id="apellido_materno" 
                                       class="form-control solo-letras" 
                                       value="{{ old('apellido_materno') }}">
                                @error('apellido_materno')
                                <small class="text-danger">{{'*'.$message}}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    <!------Dirección---->
                    <div class="col-12">
                        <label for="direccion" class="form-label">Dirección:</label>
                        <input type="text" 
                               name="direccion" 
                               id="direccion" 
                               class="form-control" 
                               value="{{ old('direccion', $proveedore->persona->direccion) }}">
                        @error('direccion')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!------Email---->
                    <div class="col-md-6">
                        <label for="email" class="form-label">Correo electrónico:</label>
                        <input type="email" 
                               name="email" 
                               id="email" 
                               class="form-control" 
                               value="{{ old('email', $proveedore->persona->email) }}">
                        @error('email')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!------Telefono---->
                    <div class="col-md-6">
                        <label for="telefono" class="form-label">Teléfono:</label>
                        <input type="number" 
                               name="telefono" 
                               id="telefono" 
                               class="form-control" 
                               value="{{ old('telefono', $proveedore->persona->telefono) }}">
                        @error('telefono')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <!--------------Documento------->
                    <div class="col-md-6">
                        <label for="documento_id" class="form-label">Tipo de documento:</label>
                        <select class="form-select" name="documento_id" id="documento_id">
                            @foreach ($documentos as $item)
                            <option value="{{ $item->id }}" 
                                {{ old('documento_id', $proveedore->persona->documento_id) == $item->id ? 'selected' : '' }}>
                                {{ $item->nombre }}
                            </option>
                            @endforeach
                        </select>
                        @error('documento_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label for="numero_documento" class="form-label">Numero de documento:</label>
                        <input required 
                               type="text" 
                               name="numero_documento" 
                               id="numero_documento" 
                               class="form-control" 
                               value="{{ old('numero_documento', $proveedore->persona->numero_documento) }}">
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
        // Obtener el tipo actual del proveedor
        const tipoActual = "{{ $proveedore->persona->tipo->value }}";
        const razonSocialCompleta = "{{ old('razon_social', $proveedore->persona->razon_social) }}";
        
        // Función para dividir el nombre completo
        function dividirNombreCompleto(nombreCompleto) {
            // Dividir por espacios y limpiar elementos vacíos
            const partes = nombreCompleto.split(' ').filter(parte => parte.trim() !== '');
            
            let nombres = '';
            let apellidoPaterno = '';
            let apellidoMaterno = '';
            
            // Lógica para dividir en nombres y apellidos
            if (partes.length === 1) {
                // Solo un nombre
                nombres = partes[0];
            } else if (partes.length === 2) {
                // Nombre + Apellido Paterno
                nombres = partes[0];
                apellidoPaterno = partes[1];
            } else if (partes.length === 3) {
                // Nombre + Apellido Paterno + Apellido Materno
                nombres = partes[0];
                apellidoPaterno = partes[1];
                apellidoMaterno = partes[2];
            } else if (partes.length >= 4) {
                // Múltiples nombres + Apellidos
                // Tomar todos menos los últimos 2 como nombres
                nombres = partes.slice(0, partes.length - 2).join(' ');
                apellidoPaterno = partes[partes.length - 2];
                apellidoMaterno = partes[partes.length - 1];
            }
            
            return {
                nombres: nombres,
                apellidoPaterno: apellidoPaterno,
                apellidoMaterno: apellidoMaterno
            };
        }
        
        // Mostrar/ocultar campos según el tipo
        if (tipoActual === 'NATURAL') {
            $('#box-razon-social').hide();
            $('#box-nombre-completo').show();
            
            // Dividir el nombre completo y llenar los campos
            const nombreDividido = dividirNombreCompleto(razonSocialCompleta);
            
            // Llenar los campos solo si no hay valores old (para respetar la validación)
            if (!{{ old('nombres') ? 'true' : 'false' }}) {
                $('#nombres').val(nombreDividido.nombres);
            }
            if (!{{ old('apellido_paterno') ? 'true' : 'false' }}) {
                $('#apellido_paterno').val(nombreDividido.apellidoPaterno);
            }
            if (!{{ old('apellido_materno') ? 'true' : 'false' }}) {
                $('#apellido_materno').val(nombreDividido.apellidoMaterno);
            }
            
            // Hacer obligatorios los campos de persona natural
            $('#nombres').prop('required', true);
            $('#apellido_paterno').prop('required', true);
            $('#apellido_materno').prop('required', false);
            // Quitar requerido de razón social
            $('#razon_social').prop('required', false);
            
            // Deshabilitar campo de razón social para que no se envíe
            $('#razon_social').prop('disabled', true);
        } else {
            $('#box-razon-social').show();
            $('#box-nombre-completo').hide();
            // Quitar requerido de campos de persona natural
            $('#nombres').prop('required', false);
            $('#apellido_paterno').prop('required', false);
            $('#apellido_materno').prop('required', false);
            // Hacer obligatoria la razón social
            $('#razon_social').prop('required', true);
            
            // Deshabilitar campos de nombres para que no se envíen
            $('#nombres').prop('disabled', true);
            $('#apellido_paterno').prop('disabled', true);
            $('#apellido_materno').prop('disabled', true);
        }

        // Manejar el envío del formulario
        $('#proveedorForm').on('submit', function() {
            const tipo = $('#tipo').val();
            
            if (tipo === 'NATURAL') {
                // Para NATURAL: habilitar campos de nombres y deshabilitar razón social
                $('#nombres').prop('disabled', false);
                $('#apellido_paterno').prop('disabled', false);
                $('#apellido_materno').prop('disabled', false);
                $('#razon_social').prop('disabled', true);
            } else {
                // Para JURIDICA: habilitar razón social y deshabilitar campos de nombres
                $('#razon_social').prop('disabled', false);
                $('#nombres').prop('disabled', true);
                $('#apellido_paterno').prop('disabled', true);
                $('#apellido_materno').prop('disabled', true);
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
    });
</script>
@endpush