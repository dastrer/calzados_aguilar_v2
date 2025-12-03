@extends('layouts.app')

@section('title','Crear cliente')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
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
    .error-message {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
        display: none;
    }
    .is-invalid {
        border-color: #dc3545;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Cliente</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('clientes.index')}}">Clientes</a></li>
        <li class="breadcrumb-item active">Crear cliente</li>
    </ol>

    <div class="card text-bg-light">
        <form action="{{ route('clientes.store') }}" method="post" id="clienteForm">
            @csrf
            <div class="card-body">
                <div class="row g-3">

                    <!----Tipo de persona----->
                    <div class="col-md-6">
                        <label for="tipo" class="form-label">Tipo de cliente:</label>
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
                        <div class="error-message" id="direccion-error"></div>
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

        // Función para mostrar notificaciones SweetAlert2
        function mostrarNotificacionError(mensaje) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: mensaje,
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer)
                    toast.addEventListener('mouseleave', Swal.resumeTimer)
                }
            });
        }

        // Función para mostrar error en campo
        function mostrarError(campoId, mensaje) {
            const campo = $('#' + campoId);
            const errorDiv = $('#' + campoId + '-error');

            campo.addClass('is-invalid');
            errorDiv.text(mensaje).show();
        }

        // Función para limpiar error
        function limpiarError(campoId) {
            const campo = $('#' + campoId);
            const errorDiv = $('#' + campoId + '-error');

            campo.removeClass('is-invalid');
            errorDiv.text('').hide();
        }

        // Función para validar teléfono
        function validarTelefono(telefono) {
            if (!telefono) return null; // No es obligatorio

            telefono = telefono.replace(/\s/g, '');

            // Validar formato internacional (con +)
            if (telefono.startsWith('+')) {
                const parteNumerica = telefono.substring(1);

                // Verificar que después del + solo haya números
                if (!/^\d+$/.test(parteNumerica)) {
                    return 'Después del + solo se permiten números';
                }

                // Verificar longitud (máximo 12 dígitos después del +)
                if (parteNumerica.length > 12) {
                    return 'Máximo 12 dígitos después del +';
                }

                if (parteNumerica.length < 1) {
                    return 'Debe haber al menos 1 dígito después del +';
                }

                return null; // Válido
            }
            // Validar formato nacional (sin +)
            else {
                // Verificar que solo contenga números
                if (!/^\d+$/.test(telefono)) {
                    return 'Solo se permiten números para formato nacional';
                }

                // Verificar longitud exacta de 8 dígitos
                if (telefono.length !== 8) {
                    return 'El teléfono nacional debe tener exactamente 8 dígitos';
                }

                // Verificar que empiece con 6, 7 o 2
                const primerDigito = telefono.charAt(0);
                if (!['6', '7', '2'].includes(primerDigito)) {
                    return 'El teléfono nacional debe comenzar con 6, 7 o 2';
                }

                return null; // Válido
            }
        }

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

        // Manejar cambio de tipo de cliente
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

        // Validación y formateo de teléfono en tiempo real
        // Validación y formateo de teléfono en tiempo real - SOLO NÚMEROS Y +
$('#telefono').on('input', function() {
    let value = $(this).val();

    // Permitir solo números y el signo + (solo al inicio)
    if (value.startsWith('+')) {
        // Si empieza con +, permitir números después
        value = '+' + value.substring(1).replace(/[^0-9]/g, '');
    } else {
        // Si no empieza con +, permitir solo números
        value = value.replace(/[^0-9]/g, '');
    }

    // Aplicar límites de longitud
    if (value.startsWith('+')) {
        if (value.length > 13) value = value.slice(0, 13); // + y máximo 12 dígitos
    } else {
        if (value.length > 8) value = value.slice(0, 8); // máximo 8 dígitos
    }

    $(this).val(value);
    limpiarError('telefono');
});

// Prevenir entrada de caracteres no permitidos
$('#telefono').on('keydown', function(e) {
    const key = e.key;
    const value = $(this).val();

    // Permitir teclas de control (backspace, delete, tab, etc.)
    if (e.ctrlKey || e.altKey || key === 'Backspace' || key === 'Delete' || key === 'Tab' || key === 'ArrowLeft' || key === 'ArrowRight') {
        return true;
    }

    // Si ya hay un +, solo permitir números
    if (value.startsWith('+')) {
        if (!/^\d$/.test(key)) {
            e.preventDefault();
            return false;
        }
    }
    // Si no hay + aún, permitir números o + (solo si está al inicio)
    else {
        if (value === '' && key === '+') {
            return true; // Permitir + solo al inicio cuando el campo está vacío
        }
        if (!/^\d$/.test(key)) {
            e.preventDefault();
            return false;
        }
    }
});

// Validación de teléfono en tiempo real al perder foco
$('#telefono').on('blur', function() {
    const telefono = $(this).val();
    if (telefono) {
        const error = validarTelefono(telefono);
        if (error) {
            mostrarError('telefono', error);
        } else {
            limpiarError('telefono');
        }
    } else {
        limpiarError('telefono');
    }
});

        // Validación de teléfono en tiempo real al perder foco
        $('#telefono').on('blur', function() {
            const telefono = $(this).val();
            if (telefono) {
                const error = validarTelefono(telefono);
                if (error) {
                    mostrarError('telefono', error);
                } else {
                    limpiarError('telefono');
                }
            } else {
                limpiarError('telefono');
            }
        });

        // Validación de dirección en tiempo real
        $('#direccion').on('blur', function() {
            validarDireccion();
        });

        // Función para validar dirección
        function validarDireccion() {
            const direccion = $('#direccion').val().trim();

            if (!direccion) {
                mostrarError('direccion', 'La dirección es obligatoria');
                return false;
            } else if (direccion.length < 5) {
                mostrarError('direccion', 'La dirección debe tener al menos 5 caracteres');
                return false;
            } else {
                limpiarError('direccion');
                return true;
            }
        }

        // Validación antes de enviar el formulario
        $('#clienteForm').on('submit', function(e) {
            let errores = [];
            let hayError = false;

            // Validar dirección
            const direccion = $('#direccion').val().trim();
            if (!direccion) {
                hayError = true;
                errores.push('• La dirección es obligatoria');
                $('#direccion').addClass('is-invalid');
                mostrarError('direccion', 'La dirección es obligatoria');
            } else if (direccion.length < 5) {
                hayError = true;
                errores.push('• La dirección debe tener al menos 5 caracteres');
                $('#direccion').addClass('is-invalid');
                mostrarError('direccion', 'La dirección debe tener al menos 5 caracteres');
            } else {
                $('#direccion').removeClass('is-invalid');
                limpiarError('direccion');
            }

            // Validar teléfono
            const telefono = $('#telefono').val();
            if (telefono) {
                const errorTelefono = validarTelefono(telefono);
                if (errorTelefono) {
                    hayError = true;
                    errores.push('• ' + errorTelefono);
                    $('#telefono').addClass('is-invalid');
                    mostrarError('telefono', errorTelefono);
                } else {
                    $('#telefono').removeClass('is-invalid');
                    limpiarError('telefono');
                }
            }

            // Validar que se haya seleccionado tipo de cliente
            const tipoCliente = $('#tipo').val();
            if (!tipoCliente) {
                hayError = true;
                errores.push('• Debe seleccionar el tipo de cliente');
                $('#tipo').addClass('is-invalid');
            } else {
                $('#tipo').removeClass('is-invalid');
            }

            // Validar campos según el tipo de cliente
            if (tipoCliente === 'JURIDICA') {
                const razonSocial = $('#razon_social').val();
                if (!razonSocial.trim()) {
                    hayError = true;
                    errores.push('• La razón social es obligatoria para persona jurídica');
                    $('#razon_social').addClass('is-invalid');
                } else {
                    $('#razon_social').removeClass('is-invalid');
                }
            } else if (tipoCliente === 'NATURAL') {
                const nombres = $('#nombres').val();
                const apellidoPaterno = $('#apellido_paterno').val();

                if (!nombres.trim()) {
                    hayError = true;
                    errores.push('• El nombre es obligatorio para persona natural');
                    $('#nombres').addClass('is-invalid');
                } else {
                    $('#nombres').removeClass('is-invalid');
                }

                if (!apellidoPaterno.trim()) {
                    hayError = true;
                    errores.push('• El apellido paterno es obligatorio para persona natural');
                    $('#apellido_paterno').addClass('is-invalid');
                } else {
                    $('#apellido_paterno').removeClass('is-invalid');
                }
            }

            if (hayError) {
                e.preventDefault();

                // Crear mensaje de error formateado
                let mensajeError = 'Por favor corrija los siguientes errores:\n\n';
                mensajeError += errores.join('\n');

                // Mostrar SweetAlert2
                mostrarNotificacionError(mensajeError);
                return false;
            }

            // Si no hay errores, proceder con el formateo normal
            const numero = $('#numero_documento').val();
            const complemento = $('#complemento').val();
            const telefonoFormateado = $('#telefono').val();

            // Preparar número de documento completo para CI
            if (esCI && complemento) {
                $('#numero_documento').val(numero + '-' + complemento.toUpperCase());
            }

            // Formatear teléfono (eliminar espacios) antes de enviar
            if (telefonoFormateado) {
                $('#telefono').val(telefonoFormateado.replace(/\s/g, ''));
            }

            // UNIR CAMPOS PARA PERSONA NATURAL
            if (tipoCliente === 'NATURAL') {
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

        // Mostrar errores del backend si existen
        @if($errors->any())
            @php
                $erroresBackend = [];
                foreach ($errors->all() as $error) {
                    $erroresBackend[] = '• ' . $error;
                }
            @endphp
            mostrarNotificacionError('Por favor corrija los siguientes errores:\n\n{!! implode('\n', $erroresBackend) !!}');
        @endif

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
