@extends('layouts.app')

@section('title','Perfil')

@push('css')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
    .error-message {
        color: #dc3545;
        font-size: 0.875em;
        margin-top: 0.25rem;
    }
</style>
@endpush

@section('content')

<div class="container-fluid">
    <h1 class="mt-4 mb-4 text-center">Configurar perfil</h1>

    <div class="card">
        <div class="card-header">
            <p class="lead fw-bold">Configure y personalize su perfil</p>
        </div>
        <form id="profileForm" action="{{route('profile.update',['profile' => auth()->user() ])}}" method="POST">
            @method('PATCH')
            @csrf
            <div class="card-body">
                <div class="row g-4">

                    <!---Name--->
                    <div class="col-12">
                        <x-forms.input id='name'
                            required='true'
                            labelText='Nombre de usuario'
                            :defaultValue='auth()->user()->name' />
                        <div id="nameError" class="error-message"></div>
                    </div>

                    <!----Email--->
                    <div class="col-12">
                        <x-forms.input id='email'
                            required='true'
                            type='email'
                            labelText='Correo electrónico'
                            :defaultValue='auth()->user()->email' />
                        <div id="emailError" class="error-message"></div>
                    </div>

                    <!----Password--->
                    <div class="col-12">
                        <x-forms.input id='password'
                            type='password'
                            labelText='Nueva contraseña'
                            placeholder="Dejar en blanco para mantener la actual" />
                        <div id="passwordError" class="error-message"></div>
                    </div>

                    <!----Confirm Password--->
                    <div class="col-12">
                        <x-forms.input id='password_confirmation'
                            type='password'
                            labelText='Confirmar nueva contraseña'
                            placeholder="Repite la contraseña" />
                        <div id="passwordConfirmationError" class="error-message"></div>
                    </div>

                </div>
            </div>

            <div class="card-footer">
                <div class="col text-center">
                    @can('editar-perfil')
                    <input id="submitBtn" class="btn btn-success" type="submit" value="Guardar cambios">
                    @endcan
                </div>
            </div>

        </form>
    </div>

</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('profileForm');
        const nameInput = document.getElementById('name');
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        const passwordConfirmInput = document.getElementById('password_confirmation');
        const submitBtn = document.getElementById('submitBtn');

        // Validar solo letras y espacios para el nombre
        nameInput.addEventListener('input', function(e) {
            // Remover números y caracteres especiales excepto espacios
            this.value = this.value.replace(/[^a-zA-ZáéíóúÁÉÍÓÚñÑ\s]/g, '');
        });

        // Validar en tiempo real
        nameInput.addEventListener('blur', validateName);
        emailInput.addEventListener('blur', validateEmail);
        passwordInput.addEventListener('blur', validatePassword);
        passwordConfirmInput.addEventListener('blur', validatePasswordConfirmation);

        // Validar todo el formulario al enviar
        form.addEventListener('submit', function(e) {
            e.preventDefault();

            const isNameValid = validateName();
            const isEmailValid = validateEmail();
            const isPasswordValid = validatePassword();
            const isPasswordConfirmationValid = validatePasswordConfirmation();

            if (isNameValid && isEmailValid && isPasswordValid && isPasswordConfirmationValid) {
                // Si todas las validaciones pasan, enviar el formulario
                form.submit();
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error de validación',
                    text: 'Por favor, corrige los errores en el formulario',
                    confirmButtonColor: '#dc3545'
                });
            }
        });

        function validateName() {
            const name = nameInput.value.trim();
            const nameError = document.getElementById('nameError');

            if (!name) {
                nameError.textContent = 'El nombre es obligatorio';
                nameInput.classList.add('is-invalid');
                return false;
            }

            // Validar que solo contenga letras y espacios
            const nameRegex = /^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/;
            if (!nameRegex.test(name)) {
                nameError.textContent = 'Solo se permiten letras y espacios';
                nameInput.classList.add('is-invalid');
                return false;
            }

            nameError.textContent = '';
            nameInput.classList.remove('is-invalid');
            nameInput.classList.add('is-valid');
            return true;
        }

        function validateEmail() {
            const email = emailInput.value.trim();
            const emailError = document.getElementById('emailError');

            if (!email) {
                emailError.textContent = 'El correo electrónico es obligatorio';
                emailInput.classList.add('is-invalid');
                return false;
            }

            // Validar formato de email
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            if (!emailRegex.test(email)) {
                emailError.textContent = 'Por favor, ingresa un correo electrónico válido';
                emailInput.classList.add('is-invalid');
                return false;
            }

            emailError.textContent = '';
            emailInput.classList.remove('is-invalid');
            emailInput.classList.add('is-valid');
            return true;
        }

        function validatePassword() {
            const password = passwordInput.value;
            const passwordError = document.getElementById('passwordError');

            // Si el campo está vacío, es válido (puede mantener la contraseña actual)
            if (!password) {
                passwordError.textContent = '';
                passwordInput.classList.remove('is-invalid');
                passwordInput.classList.remove('is-valid');
                return true;
            }

            // Validar requisitos de contraseña
            if (password.length < 8) {
                passwordError.textContent = 'La contraseña debe tener al menos 8 caracteres';
                passwordInput.classList.add('is-invalid');
                return false;
            }

            // Verificar que tenga al menos una mayúscula, una minúscula y un número
            const hasUpperCase = /[A-Z]/.test(password);
            const hasLowerCase = /[a-z]/.test(password);
            const hasNumbers = /\d/.test(password);

            if (!hasUpperCase || !hasLowerCase || !hasNumbers) {
                passwordError.textContent = 'Debe contener al menos una mayúscula, una minúscula y un número';
                passwordInput.classList.add('is-invalid');
                return false;
            }

            passwordError.textContent = '';
            passwordInput.classList.remove('is-invalid');
            passwordInput.classList.add('is-valid');
            return true;
        }

        function validatePasswordConfirmation() {
            const password = passwordInput.value;
            const passwordConfirm = passwordConfirmInput.value;
            const passwordConfirmationError = document.getElementById('passwordConfirmationError');

            // Solo validar si se ingresó una nueva contraseña
            if (password) {
                if (!passwordConfirm) {
                    passwordConfirmationError.textContent = 'Debes confirmar la contraseña';
                    passwordConfirmInput.classList.add('is-invalid');
                    return false;
                }

                if (password !== passwordConfirm) {
                    passwordConfirmationError.textContent = 'Las contraseñas no coinciden';
                    passwordConfirmInput.classList.add('is-invalid');
                    return false;
                }
            }

            passwordConfirmationError.textContent = '';
            passwordConfirmInput.classList.remove('is-invalid');
            passwordConfirmInput.classList.add('is-valid');
            return true;
        }

        // Validación en tiempo real para confirmación de contraseña
        passwordInput.addEventListener('input', function() {
            if (passwordConfirmInput.value) {
                validatePasswordConfirmation();
            }
        });

        passwordConfirmInput.addEventListener('input', validatePasswordConfirmation);
    });
</script>
@endpush
