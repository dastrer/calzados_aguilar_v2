@extends('layouts.app')

@section('title','Crear usuario')

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
</style>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Usuario</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('users.index')}}">Usuarios</a></li>
        <li class="breadcrumb-item active">Crear Usuario</li>
    </ol>

    <div class="card text-bg-light">
        <form action="{{ route('users.store') }}" method="post" id="userForm">
            @csrf
            <div class="card-header">
                <p class="">Nota: Los usuarios son los que pueden ingresar al sistema</p>
            </div>
            <div class="card-body">

                <div class="row mb-4">
                    <label for="empleado_id" class="col-lg-2 col-form-label">
                        Empleado:</label>
                    <div class="col-lg-4">
                        <select name="empleado_id" id="empleado_id"
                            class="form-select">
                            <option value="" selected disabled>Seleccione:</option>
                            @foreach ($empleados as $item)
                            <option value="{{$item->id}}"
                                {{-- AGREGAMOS data-email Y EL NOMBRE COMPLETO --}}
                                data-email="{{$item->correo}}"
                                {{ old('empleado_id') == $item->id ? 'selected': '' }}>
                                {{$item->razon_social}} {{$item->apellido_paterno}} {{$item->apellido_materno}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text">
                            Escoja en empleado
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('empleado_id')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <label for="name" class="col-lg-2 col-form-label">Identificador:</label>
                    <div class="col-lg-4">
                        <input autocomplete="off" type="text" name="name"
                            id="name" class="form-control" value="{{old('name')}}"
                            aria-labelledby="nameHelpBlock" placeholder="Solo letras, sin espacios">
                        <div class="error-message" id="name-error"></div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="nameHelpBlock">
                            Escriba un solo nombre (solo letras, sin espacios ni caracteres especiales)
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('name')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <label for="email" class="col-lg-2 col-form-label">Email:</label>
                    <div class="col-lg-4">
                        <input autocomplete="off" type="email" name="email"
                            id="email" class="form-control" value="{{old('email')}}"
                            aria-labelledby="emailHelpBlock">
                        <div class="error-message" id="email-error"></div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="emailHelpBlock">
                            Dirección de correo eléctronico
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('email')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <label for="password" class="col-lg-2 col-form-label">Contraseña:</label>
                    <div class="col-lg-4">
                        <input type="password" name="password" id="password"
                            class="form-control" aria-labelledby="passwordHelpBlock">
                        <div class="error-message" id="password-error"></div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="passwordHelpBlock">
                            Escriba una constraseña segura. Debe incluir números.
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('password')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <label for="password_confirm" class="col-lg-2 col-form-label">Confirmar:</label>
                    <div class="col-lg-4">
                        <input type="password" name="password_confirm" id="password_confirm"
                            class="form-control" aria-labelledby="passwordConfirmHelpBlock">
                        <div class="error-message" id="password_confirm-error"></div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="passwordConfirmHelpBlock">
                            Vuelva a escribir su contraseña.
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('password_confirm')
                        <small class="text-danger">{{'*'.$message}}</small>
                        @enderror
                    </div>
                </div>

                <div class="row mb-4">
                    <label for="role" class="col-lg-2 col-form-label">Rol:</label>
                    <div class="col-lg-4">
                        <select name="role" id="role" class="form-select" aria-labelledby="rolHelpBlock">
                            <option value="" selected disabled>
                                Seleccione:</option>
                            @foreach ($roles as $item)
                            <option value="{{$item->name}}" @selected(old('role')==$item->name)>
                                {{$item->name}}
                            </option>
                            @endforeach
                        </select>
                        <div class="error-message" id="role-error"></div>
                    </div>
                    <div class="col-lg-4">
                        <div class="form-text" id="rolHelpBlock">
                            Escoja un rol para el usuario.
                        </div>
                    </div>
                    <div class="col-lg-2">
                        @error('role')
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

        // Función para validar identificador (solo letras, sin espacios)
        function validarIdentificador(identificador) {
            if (!identificador.trim()) {
                return 'El identificador es obligatorio';
            }

            // Solo letras, sin espacios ni caracteres especiales
            const regex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ]+$/;
            if (!regex.test(identificador)) {
                return 'Solo se permiten letras, sin espacios ni caracteres especiales';
            }

            if (identificador.length < 3) {
                return 'El identificador debe tener al menos 3 caracteres';
            }

            if (identificador.length > 20) {
                return 'El identificador no debe exceder 20 caracteres';
            }

            return null;
        }

        // Función para validar email
        function validarEmail(email) {
            if (!email.trim()) {
                return 'El email es obligatorio';
            }

            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                return 'El formato del email no es válido';
            }

            return null;
        }

        // Función para validar contraseña
        function validarPassword(password) {
            if (!password) {
                return 'La contraseña es obligatoria';
            }

            if (password.length < 8) {
                return 'La contraseña debe tener al menos 8 caracteres';
            }

            // Validar que tenga al menos un número
            if (!/\d/.test(password)) {
                return 'La contraseña debe incluir al menos un número';
            }

            return null;
        }

        // Función para validar confirmación de contraseña
        function validarConfirmacionPassword(password, confirmacion) {
            if (!confirmacion) {
                return 'La confirmación de contraseña es obligatoria';
            }

            if (password !== confirmacion) {
                return 'Las contraseñas no coinciden';
            }

            return null;
        }

        // Función para validar rol
        function validarRol(rol) {
            if (!rol) {
                return 'El rol es obligatorio';
            }

            return null;
        }

        // Validación en tiempo real para el identificador - SOLO LETRAS
        $('#name').on('input', function() {
            let value = $(this).val();

            // Remover cualquier caracter que no sea letra
            value = value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ]/g, '');

            $(this).val(value);
            limpiarError('name');
        });

        // Prevenir entrada de caracteres no permitidos en identificador
        $('#name').on('keydown', function(e) {
            const key = e.key;

            // Permitir teclas de control (backspace, delete, tab, etc.)
            if (e.ctrlKey || e.altKey || key === 'Backspace' || key === 'Delete' ||
                key === 'Tab' || key === 'ArrowLeft' || key === 'ArrowRight' ||
                key === 'Home' || key === 'End') {
                return true;
            }

            // Permitir solo letras (incluyendo caracteres especiales en español)
            const letrasRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ]$/;
            if (!letrasRegex.test(key)) {
                e.preventDefault();
                return false;
            }
        });

        // Validación de campos en tiempo real al perder foco
        $('#name').on('blur', function() {
            const error = validarIdentificador($(this).val());
            if (error) {
                mostrarError('name', error);
            } else {
                limpiarError('name');
            }
        });

        $('#email').on('blur', function() {
            const error = validarEmail($(this).val());
            if (error) {
                mostrarError('email', error);
            } else {
                limpiarError('email');
            }
        });

        $('#password').on('blur', function() {
            const error = validarPassword($(this).val());
            if (error) {
                mostrarError('password', error);
            } else {
                limpiarError('password');
            }
        });

        $('#password_confirm').on('blur', function() {
            const password = $('#password').val();
            const error = validarConfirmacionPassword(password, $(this).val());
            if (error) {
                mostrarError('password_confirm', error);
            } else {
                limpiarError('password_confirm');
            }
        });

        $('#role').on('blur', function() {
            const error = validarRol($(this).val());
            if (error) {
                mostrarError('role', error);
            } else {
                limpiarError('role');
            }
        });

        // Auto-completar email cuando se selecciona un empleado
        document.getElementById('empleado_id').addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const email = selectedOption.getAttribute('data-email');
            document.getElementById('email').value = email;

            // Limpiar error de email si se autocompleta
            limpiarError('email');
        });

        // Validación antes de enviar el formulario
        $('#userForm').on('submit', function(e) {
            let errores = [];
            let hayError = false;

            // Validar identificador
            const identificador = $('#name').val().trim();
            const errorIdentificador = validarIdentificador(identificador);
            if (errorIdentificador) {
                hayError = true;
                errores.push('• ' + errorIdentificador);
                mostrarError('name', errorIdentificador);
            } else {
                limpiarError('name');
            }

            // Validar email
            const email = $('#email').val().trim();
            const errorEmail = validarEmail(email);
            if (errorEmail) {
                hayError = true;
                errores.push('• ' + errorEmail);
                mostrarError('email', errorEmail);
            } else {
                limpiarError('email');
            }

            // Validar contraseña
            const password = $('#password').val();
            const errorPassword = validarPassword(password);
            if (errorPassword) {
                hayError = true;
                errores.push('• ' + errorPassword);
                mostrarError('password', errorPassword);
            } else {
                limpiarError('password');
            }

            // Validar confirmación de contraseña
            const passwordConfirm = $('#password_confirm').val();
            const errorPasswordConfirm = validarConfirmacionPassword(password, passwordConfirm);
            if (errorPasswordConfirm) {
                hayError = true;
                errores.push('• ' + errorPasswordConfirm);
                mostrarError('password_confirm', errorPasswordConfirm);
            } else {
                limpiarError('password_confirm');
            }

            // Validar rol
            const rol = $('#role').val();
            const errorRol = validarRol(rol);
            if (errorRol) {
                hayError = true;
                errores.push('• ' + errorRol);
                mostrarError('role', errorRol);
            } else {
                limpiarError('role');
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
