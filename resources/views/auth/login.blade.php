<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
    <meta name="description" content="Inicio de sesión - Calzados Aguilar" />
    <meta name="author" content="SakCode" />
    <title>Calzados Aguilar - Login</title>

    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
        overflow-x: hidden;
        font-family: 'Inter', sans-serif;
        padding: 1rem;
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
        margin: auto;
    }

    .login-header {
        background: linear-gradient(135deg, var(--aguilar-primary), #2C2C2C);
        color: #FFFFFF;
        padding: 1rem 1.5rem;
        text-align: center;
    }

    .login-header h3 {
        font-weight: 700;
        margin-bottom: 0.5rem;
        font-size: clamp(1.2rem, 4vw, 1.4rem);
    }

    .login-header small {
        color: var(--aguilar-accent);
        font-size: clamp(0.75rem, 3vw, 0.85rem);
    }

    .logo-preview {
        max-height: clamp(80px, 25vw, 200px);
        width: auto;
        max-width: 100%;
        margin-top: 0.25rem;
        margin-bottom: 0.25rem;
        object-fit: contain;
    }

    .card-body {
        padding: clamp(1rem, 4vw, 1.5rem) clamp(1rem, 5vw, 2rem);
        background-color: var(--aguilar-light);
    }

    .form-control {
        border-radius: 8px;
        border: 1px solid #CCC;
        padding: 0.75rem 1rem;
        font-size: clamp(0.9rem, 3vw, 0.95rem);
        height: auto;
    }

    .form-floating {
        margin-bottom: 0.75rem;
    }

    .form-floating > label {
        padding: 0.75rem 1rem;
        font-size: clamp(0.85rem, 3vw, 0.9rem);
    }

    .btn-aguilar {
        background-color: var(--aguilar-primary);
        color: #FFFFFF;
        font-weight: 600;
        border-radius: 8px;
        padding: clamp(0.6rem, 2vw, 0.65rem) clamp(1.2rem, 3vw, 1.5rem);
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(26, 43, 76, 0.3);
        font-size: clamp(0.9rem, 3vw, 0.95rem);
        border: none;
        width: 100%;
        min-height: 48px;
    }

    .btn-aguilar:hover {
        background-color: var(--aguilar-accent);
        color: var(--aguilar-primary);
        box-shadow: 0 6px 18px rgba(191, 174, 128, 0.4);
    }

    .btn-aguilar-outline {
        background-color: transparent;
        color: var(--aguilar-primary);
        font-weight: 600;
        border-radius: 8px;
        padding: clamp(0.6rem, 2vw, 0.65rem) clamp(1.2rem, 3vw, 1.5rem);
        transition: all 0.3s ease;
        border: 2px solid var(--aguilar-primary);
        font-size: clamp(0.9rem, 3vw, 0.95rem);
        width: 100%;
        min-height: 48px;
        text-decoration: none;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .btn-aguilar-outline:hover {
        background-color: var(--aguilar-primary);
        color: #FFFFFF;
        border-color: var(--aguilar-primary);
    }

    .alert-danger {
        background-color: rgba(231, 76, 60, 0.1);
        border: 1px solid rgba(231, 76, 60, 0.3);
        color: #c0392b;
        border-radius: 8px;
        padding: 0.75rem 1rem;
        margin-bottom: 0.75rem;
        font-size: clamp(0.85rem, 3vw, 0.9rem);
    }

    .d-grid.mt-4 {
        margin-top: clamp(1rem, 4vw, 1.5rem) !important;
    }

    .d-grid.mt-3 {
        margin-top: clamp(0.75rem, 3vw, 1rem) !important;
    }

    footer {
        background-color: #F0F0F0;
        font-size: clamp(0.7rem, 2.5vw, 0.8rem);
        padding: clamp(0.5rem, 2vw, 0.75rem) clamp(0.75rem, 3vw, 1rem);
        text-align: center;
        line-height: 1.4;
    }

    footer a {
        color: var(--aguilar-primary);
        text-decoration: none;
        white-space: nowrap;
    }

    footer a:hover {
        color: var(--aguilar-accent);
    }

    /* Media Queries para dispositivos específicos */
    @media (max-width: 768px) {
        body.login-bg {
            padding: 0.75rem;
        }

        .login-card {
            border-radius: 10px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        }

        .login-header {
            padding: 0.75rem 1rem;
        }
    }

    @media (max-width: 576px) {
        body.login-bg {
            padding: 0.5rem;
            align-items: flex-start;
            padding-top: 2rem;
        }

        .login-card {
            border-radius: 8px;
            max-width: 95%;
        }

        .card-body {
            padding: 1rem 1.25rem;
        }

        .form-control {
            padding: 0.65rem 0.85rem;
        }

        .form-floating > label {
            padding: 0.65rem 0.85rem;
        }

        .btn-aguilar,
        .btn-aguilar-outline {
            min-height: 44px;
        }

        footer {
            padding: 0.5rem 0.75rem;
        }
    }

    @media (max-width: 360px) {
        .login-header h3 {
            font-size: 1.1rem;
        }

        .login-header small {
            font-size: 0.7rem;
        }

        .card-body {
            padding: 0.85rem 1rem;
        }

        footer {
            font-size: 0.65rem;
        }

        footer a {
            display: inline-block;
            margin: 0 0.25rem;
        }
    }

    /* Para tablets en orientación horizontal */
    @media (min-width: 768px) and (max-width: 1024px) and (orientation: landscape) {
        body.login-bg {
            padding: 1rem;
            align-items: center;
        }

        .login-card {
            max-width: 450px;
        }
    }

    /* Para dispositivos con altura muy reducida */
    @media (max-height: 600px) {
        body.login-bg {
            align-items: flex-start;
            padding-top: 1rem;
        }

        .login-card {
            margin-top: 1rem;
            margin-bottom: 1rem;
        }

        .logo-preview {
            max-height: 60px;
        }
    }

    /* Asegurar que los botones sean tappables en móviles */
    @media (hover: none) and (pointer: coarse) {
        .btn-aguilar,
        .btn-aguilar-outline {
            min-height: 50px;
            padding: 0.75rem 1.5rem;
        }

        .form-control {
            font-size: 16px; /* Evita zoom en iOS */
        }
    }

    /* Mejoras de accesibilidad */
    @media (prefers-reduced-motion: reduce) {
        * {
            animation-duration: 0.01ms !important;
            animation-iteration-count: 1 !important;
            transition-duration: 0.01ms !important;
        }
    }

    @media (prefers-contrast: high) {
        .btn-aguilar {
            border: 2px solid #000;
        }

        .btn-aguilar-outline {
            border-width: 3px;
        }
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

            <!-- Botón para volver atrás con icono -->
            <div class="d-grid mt-3">
                <a href="{{ route('panel') }}" class="btn-aguilar-outline">
                    <i class="fas fa-arrow-left"></i>
                    <span>Volver atrás</span>
                </a>
            </div>
        </div>
        <footer>
            &copy; Calzados Aguilar {{ date('Y') }} &middot;
            <a href="#">Política de Privacidad</a> &middot;
            <a href="#">Términos y Condiciones</a>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Script para mejorar la experiencia en dispositivos táctiles
        document.addEventListener('DOMContentLoaded', function() {
            // Prevenir zoom en inputs en iOS
            document.querySelectorAll('input, select, textarea').forEach(el => {
                el.addEventListener('touchstart', function() {
                    this.style.fontSize = '16px';
                });
            });

            // Mejorar la accesibilidad del teclado
            document.querySelectorAll('.btn-aguilar, .btn-aguilar-outline').forEach(btn => {
                btn.addEventListener('keypress', function(e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        this.click();
                    }
                });
            });
        });
    </script>
</body>

</html>
