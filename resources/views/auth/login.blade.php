<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="Inicio de sesión - Calzados Aguilar" />
    <meta name="author" content="SakCode" />
    <title>Calzados Aguilar - Login</title>

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://use.fontawesome.com/releases/v6.1.0/js/all.js" crossorigin="anonymous"></script>

    <style>
    :root {
        --aguilar-primary: #1A2B4C;
        --aguilar-accent: #BFAE80;
        --aguilar-light: #FAFAFA;
        --aguilar-gray: #D1D5DB;
    }

    body.login-bg {
        position: relative;
        background: url("{{ asset('images/fondo-calzados.jpg') }}") no-repeat center center fixed;
        background-size: cover;
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        overflow: hidden;
        font-family: 'Inter', sans-serif;
    }

    .login-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: rgba(255, 255, 255, 0.6);
        backdrop-filter: blur(6px);
        z-index: 1;
    }

    .login-card {
        position: relative;
        z-index: 2;
        background-color: #FFFFFF;
        border-radius: 12px;
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
        overflow: hidden;
        max-width: 500px;
        width: 100%;
    }

    .login-header {
        background: linear-gradient(135deg, var(--aguilar-primary), #2C2C2C);
        color: #FFFFFF;
        padding: 1rem 1.5rem; /* Reducido de 2rem a 1rem */
        text-align: center;
    }

    .login-header h3 {
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .login-header small {
        color: var(--aguilar-accent);
        font-size: 0.95rem;
    }

    .logo-preview {
        max-height: 250px;
        width: auto;
        margin-top: 0.5rem; /* Añadido para reducir espacio superior */
        margin-bottom: 0.5rem; /* Reducido de 1rem a 0.5rem */
    }

    .card-body {
        padding: 2rem;
        background-color: var(--aguilar-light);
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #CCC;
        padding: 0.9rem 1rem;
    }

    .form-control:focus {
        border-color: var(--aguilar-accent);
        box-shadow: 0 0 0 0.2rem rgba(191, 174, 128, 0.25);
    }

    .btn-aguilar {
        background-color: var(--aguilar-primary);
        color: #FFFFFF;
        font-weight: 600;
        border-radius: 8px;
        padding: 0.75rem 1.5rem;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(26, 43, 76, 0.3);
    }

    .btn-aguilar:hover {
        background-color: var(--aguilar-accent);
        color: var(--aguilar-primary);
        box-shadow: 0 6px 18px rgba(191, 174, 128, 0.4);
    }

    .alert-danger {
        background-color: rgba(231, 76, 60, 0.1);
        border: 1px solid rgba(231, 76, 60, 0.3);
        color: #c0392b;
        border-radius: 8px;
    }

    footer {
        background-color: #F0F0F0;
        font-size: 0.85rem;
        padding: 1rem;
        text-align: center;
    }

    footer a {
        color: var(--aguilar-primary);
        text-decoration: none;
    }

    footer a:hover {
        color: var(--aguilar-accent);
    }
</style>

</head>

<body class="login-bg">
    <div class="login-overlay"></div>

    <div class="login-card">
        <div class="login-header">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Calzados Aguilar" class="logo-preview">
            <h3>Calzados Aguilar</h3>
            <small>Acceso al Sistema</small>
        </div>
        <div class="card-body">
            @if ($errors->any())
                @foreach ($errors->all() as $item)
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{$item}}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endforeach
            @endif

            <form action="{{ route('login.login') }}" method="post">
                @csrf
                <div class="form-floating mb-3">
                    <input autofocus autocomplete="off" value="invitado@gmail.com" class="form-control" name="email" id="inputEmail" type="email" placeholder="name@example.com" />
                    <label for="inputEmail">Correo electrónico</label>
                </div>

                <div class="form-floating mb-3">
                    <input class="form-control" name="password" value="12345678" id="inputPassword" type="password" placeholder="Password" />
                    <label for="inputPassword">Contraseña</label>
                </div>

                <div class="d-grid mt-4">
                    <button class="btn btn-aguilar" type="submit">Iniciar sesión</button>
                </div>
            </form>
        </div>
        <footer>
            &copy; Calzados Aguilar {{ date('Y') }} &middot;
            <a href="#">Política de Privacidad</a> &middot;
            <a href="#">Términos y Condiciones</a>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>
