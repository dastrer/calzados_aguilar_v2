@extends('layouts.app')

@section('title','Crear empleado')

@push('css')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .error-message {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
        display: none;
    }
    .is-invalid {
        border-color: #dc3545;
    }
    #img-preview {
        max-width: 200px;
        max-height: 200px;
        object-fit: cover;
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Empleado</h1>

    <x-breadcrumb.template>
        <x-breadcrumb.item :href="route('panel')" content="Inicio" />
        <x-breadcrumb.item :href="route('empleados.index')" content="Empleados" />
        <x-breadcrumb.item active='true' content="Crear empleado" />
    </x-breadcrumb.template>

    <x-forms.template :action="route('empleados.store')" method='post' file='true' id="empleadoForm">

        <div class="row g-4">

            {{-- Fila 1: Nombres y Apellido Paterno --}}
            <div class="col-md-6">
                {{-- Razon Social (Nombre) --}}
                <x-forms.input id="razon_social" required='true' labelText='Nombres' />
                <div class="error-message" id="razon_social-error"></div>
            </div>

            <div class="col-md-6">
                {{-- Apellido Paterno --}}
                <x-forms.input id="apellido_paterno" required='true' labelText='Apellido Paterno' />
                <div class="error-message" id="apellido_paterno-error"></div>
            </div>

            {{-- Fila 2: Apellido Materno y Correo --}}
            <div class="col-md-6">
                {{-- Apellido Materno (Opcional) --}}
                <x-forms.input id="apellido_materno" labelText='Apellido Materno (Opcional)' />
                <div class="error-message" id="apellido_materno-error"></div>
            </div>

            <div class="col-md-6">
                {{-- Correo (Único) --}}
                <x-forms.input id="correo" type='email' required='true' labelText='Correo Electrónico' />
                <div class="error-message" id="correo-error"></div>
            </div>

            {{-- Fila 3: Teléfono y Cargo --}}
            <div class="col-md-6">
                {{-- Teléfono (Obligatorio) --}}
                <x-forms.input id="telefono" required='true' labelText='Teléfono' placeholder="8 dígitos comenzando con 6, 7 o 2" />
                <small class="text-muted">Formato: 8 dígitos comenzando con 6, 7 o 2</small>
                <div class="error-message" id="telefono-error"></div>
            </div>

            <div class="col-md-6">
                <x-forms.input id="cargo" required='true' labelText='Cargo / Puesto' />
                <div class="error-message" id="cargo-error"></div>
            </div>

            {{-- Fila 4: Dirección (Obligatoria) --}}
            <div class="col-md-12">
                {{-- Dirección (Obligatoria) --}}
                <x-forms.textarea id="direccion" required='true' labelText='Dirección' />
                <small class="text-muted">Mínimo 5 caracteres</small>
                <div class="error-message" id="direccion-error"></div>
            </div>

            {{-- Fila 5: Imagen --}}
            <div class="col-md-6">
                <x-forms.input id="img" type='file' labelText='Seleccione una imagen' accept="image/*"/>
                <div class="error-message" id="img-error"></div>
            </div>

            <div class="col-md-6">
                <p>Imagen seleccionada:</p>

                <img id="img-default"
                    class="img-fluid"
                    src="{{ asset('assets/img/paisaje.png') }}"
                    alt="Imagen por defecto" style="max-width: 200px; max-height: 200px;">

                <img src="" alt="Ha cargado un archivo no compatible"
                    id="img-preview"
                    class="img-fluid img-thumbnail" style="display: none;">
            </div>

        </div>

        <x-slot name='footer'>
            <button type="submit" class="btn btn-primary">Guardar</button>
        </x-slot>

    </x-forms.template>

</div>
@endsection

@push('js')
<script>
    $(document).ready(function() {
        const inputImagen = document.getElementById('img');
        const imagenPreview = document.getElementById('img-preview');
        const imagenDefault = document.getElementById('img-default');

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

        // Función para validar teléfono (OBLIGATORIO - 8 dígitos)
        function validarTelefono(telefono) {
            if (!telefono) {
                return 'El teléfono es obligatorio';
            }

            telefono = telefono.replace(/\s/g, '');

            // Verificar que solo contenga números
            if (!/^\d+$/.test(telefono)) {
                return 'Solo se permiten números';
            }

            // Verificar longitud exacta de 8 dígitos
            if (telefono.length !== 8) {
                return 'El teléfono debe tener exactamente 8 dígitos';
            }

            // Verificar que empiece con 6, 7 o 2
            const primerDigito = telefono.charAt(0);
            if (!['6', '7', '2'].includes(primerDigito)) {
                return 'El teléfono debe comenzar con 6, 7 o 2';
            }

            return null; // Válido
        }

        // Función para validar dirección (OBLIGATORIA)
        function validarDireccion(direccion) {
            if (!direccion.trim()) {
                return 'La dirección es obligatoria';
            }

            if (direccion.trim().length < 5) {
                return 'La dirección debe tener al menos 5 caracteres';
            }

            return null;
        }

        // Función para validar email
        function validarEmail(email) {
            if (!email) return 'El correo electrónico es obligatorio';

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                return 'El formato del correo electrónico no es válido';
            }

            return null;
        }

        // Función para validar campo de texto (solo letras y espacios)
        function validarSoloLetras(valor, campoNombre) {
            if (!valor.trim()) {
                return `El ${campoNombre} es obligatorio`;
            }

            const letrasRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
            if (!letrasRegex.test(valor)) {
                return `El ${campoNombre} solo puede contener letras y espacios`;
            }

            if (valor.trim().length < 2) {
                return `El ${campoNombre} debe tener al menos 2 caracteres`;
            }

            return null;
        }

        // Función para validar imagen
        function validarImagen(archivo) {
            if (!archivo) return null; // No es obligatorio

            const tiposPermitidos = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
            const tamanoMaximo = 2 * 1024 * 1024; // 2MB

            if (!tiposPermitidos.includes(archivo.type)) {
                return 'Solo se permiten archivos de imagen (JPEG, JPG, PNG, GIF)';
            }

            if (archivo.size > tamanoMaximo) {
                return 'La imagen no debe superar los 2MB';
            }

            return null;
        }

        // Validación para solo letras en tiempo real
        $('#razon_social, #apellido_paterno, #apellido_materno, #cargo').on('input', function() {
            $(this).val($(this).val().replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, ''));
            limpiarError(this.id);
        });

        // Validación y formateo de teléfono en tiempo real - SOLO NÚMEROS
        $('#telefono').on('input', function() {
            let value = $(this).val();

            // Permitir solo números
            value = value.replace(/[^0-9]/g, '');

            // Aplicar límite de 8 dígitos
            if (value.length > 8) {
                value = value.slice(0, 8);
            }

            $(this).val(value);
            limpiarError('telefono');
        });

        // Prevenir entrada de caracteres no permitidos en teléfono
        $('#telefono').on('keydown', function(e) {
            const key = e.key;

            // Permitir teclas de control (backspace, delete, tab, etc.)
            if (e.ctrlKey || e.altKey || key === 'Backspace' || key === 'Delete' || key === 'Tab' ||
                key === 'ArrowLeft' || key === 'ArrowRight' || key === 'Home' || key === 'End') {
                return true;
            }

            // Permitir solo números
            if (!/^\d$/.test(key)) {
                e.preventDefault();
                return false;
            }
        });

        // Validación de campos en tiempo real al perder foco
        $('#razon_social').on('blur', function() {
            const error = validarSoloLetras($(this).val(), 'nombre');
            if (error) {
                mostrarError('razon_social', error);
            } else {
                limpiarError('razon_social');
            }
        });

        $('#apellido_paterno').on('blur', function() {
            const error = validarSoloLetras($(this).val(), 'apellido paterno');
            if (error) {
                mostrarError('apellido_paterno', error);
            } else {
                limpiarError('apellido_paterno');
            }
        });

        $('#apellido_materno').on('blur', function() {
            const valor = $(this).val();
            if (valor.trim()) {
                const error = validarSoloLetras(valor, 'apellido materno');
                if (error) {
                    mostrarError('apellido_materno', error);
                } else {
                    limpiarError('apellido_materno');
                }
            } else {
                limpiarError('apellido_materno');
            }
        });

        $('#cargo').on('blur', function() {
            const error = validarSoloLetras($(this).val(), 'cargo');
            if (error) {
                mostrarError('cargo', error);
            } else {
                limpiarError('cargo');
            }
        });

        $('#correo').on('blur', function() {
            const error = validarEmail($(this).val());
            if (error) {
                mostrarError('correo', error);
            } else {
                limpiarError('correo');
            }
        });

        $('#telefono').on('blur', function() {
            const telefono = $(this).val();
            const error = validarTelefono(telefono);
            if (error) {
                mostrarError('telefono', error);
            } else {
                limpiarError('telefono');
            }
        });

        $('#direccion').on('blur', function() {
            const direccion = $(this).val();
            const error = validarDireccion(direccion);
            if (error) {
                mostrarError('direccion', error);
            } else {
                limpiarError('direccion');
            }
        });

        // Preview de imagen
        inputImagen.addEventListener('change', function() {
            const error = validarImagen(this.files[0]);
            if (error) {
                mostrarError('img', error);
                this.value = ''; // Limpiar el input file
                imagenPreview.style.display = 'none';
                imagenDefault.style.display = 'block';
            } else {
                limpiarError('img');
                if (this.files && this.files[0]) {
                    const reader = new FileReader();
                    reader.onload = function(e) {
                        imagenPreview.src = e.target.result;
                        imagenPreview.style.display = 'block';
                        imagenDefault.style.display = 'none';
                    }
                    reader.readAsDataURL(this.files[0]);
                }
            }
        });

        // Validación antes de enviar el formulario
        $('#empleadoForm').on('submit', function(e) {
            let errores = [];
            let hayError = false;

            // Validar nombres
            const razonSocial = $('#razon_social').val().trim();
            const errorNombres = validarSoloLetras(razonSocial, 'nombre');
            if (errorNombres) {
                hayError = true;
                errores.push('• ' + errorNombres);
                mostrarError('razon_social', errorNombres);
            } else {
                limpiarError('razon_social');
            }

            // Validar apellido paterno
            const apellidoPaterno = $('#apellido_paterno').val().trim();
            const errorApellidoPaterno = validarSoloLetras(apellidoPaterno, 'apellido paterno');
            if (errorApellidoPaterno) {
                hayError = true;
                errores.push('• ' + errorApellidoPaterno);
                mostrarError('apellido_paterno', errorApellidoPaterno);
            } else {
                limpiarError('apellido_paterno');
            }

            // Validar apellido materno (opcional)
            const apellidoMaterno = $('#apellido_materno').val().trim();
            if (apellidoMaterno) {
                const errorApellidoMaterno = validarSoloLetras(apellidoMaterno, 'apellido materno');
                if (errorApellidoMaterno) {
                    hayError = true;
                    errores.push('• ' + errorApellidoMaterno);
                    mostrarError('apellido_materno', errorApellidoMaterno);
                } else {
                    limpiarError('apellido_materno');
                }
            }

            // Validar correo
            const correo = $('#correo').val().trim();
            const errorCorreo = validarEmail(correo);
            if (errorCorreo) {
                hayError = true;
                errores.push('• ' + errorCorreo);
                mostrarError('correo', errorCorreo);
            } else {
                limpiarError('correo');
            }

            // Validar teléfono (OBLIGATORIO)
            const telefono = $('#telefono').val();
            const errorTelefono = validarTelefono(telefono);
            if (errorTelefono) {
                hayError = true;
                errores.push('• ' + errorTelefono);
                mostrarError('telefono', errorTelefono);
            } else {
                limpiarError('telefono');
            }

            // Validar cargo
            const cargo = $('#cargo').val().trim();
            const errorCargo = validarSoloLetras(cargo, 'cargo');
            if (errorCargo) {
                hayError = true;
                errores.push('• ' + errorCargo);
                mostrarError('cargo', errorCargo);
            } else {
                limpiarError('cargo');
            }

            // Validar dirección (OBLIGATORIA)
            const direccion = $('#direccion').val().trim();
            const errorDireccion = validarDireccion(direccion);
            if (errorDireccion) {
                hayError = true;
                errores.push('• ' + errorDireccion);
                mostrarError('direccion', errorDireccion);
            } else {
                limpiarError('direccion');
            }

            // Validar imagen
            const archivoImagen = $('#img')[0].files[0];
            const errorImagen = validarImagen(archivoImagen);
            if (errorImagen) {
                hayError = true;
                errores.push('• ' + errorImagen);
                mostrarError('img', errorImagen);
            } else {
                limpiarError('img');
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

            // Formatear teléfono (eliminar espacios) antes de enviar
            if (telefono) {
                $('#telefono').val(telefono.replace(/\s/g, ''));
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
    });
</script>
@endpush
