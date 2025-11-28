<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="description" content="Sistema de información web para la gestión integral de inventarios, compras y ventas para Calzados Aguilar" />
    <meta name="author" content="Est. Juan Pablo Ramirez Aguilar - Sistemas Informáticos" />
    <title>Calzados Aguilar – Bienvenido</title>

    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script> <!-- Reemplaza con tu kit de FontAwesome -->

    <style>
        :root {
            --aguilar-primary: #1A2B4C;
            --aguilar-accent: #BFAE80;
            --aguilar-light: #FAFAFA;
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Inter', sans-serif;
            background: url("{{ asset('images/bannerwelcome.png') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            position: relative;
            overflow-x: hidden;
        }

        .overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            z-index: 0;
        }

        .navbar {
            z-index: 2;
            background-color: rgba(26, 43, 76, 0.85);
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        .welcome-content {
            z-index: 2;
            text-align: center;
            color: var(--aguilar-light);
            padding: 6rem 2rem 3rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            animation: fadeIn 1.5s ease-in-out;
        }

        .welcome-content h1 {
            font-size: 3rem;
            font-weight: 700;
            margin-bottom: 1rem;
            animation: slideDown 1s ease-out;
        }

        .welcome-content p {
            font-size: 1.25rem;
            color: var(--aguilar-accent);
            margin-bottom: 2rem;
            animation: slideUp 1.2s ease-out;
        }

        .btn-aguilar {
            background-color: var(--aguilar-primary);
            color: #FFFFFF;
            font-weight: 600;
            border-radius: 50px;
            padding: 0.6rem 1.2rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(26, 43, 76, 0.3);
        }

        .btn-aguilar:hover {
            background-color: var(--aguilar-accent);
            color: var(--aguilar-primary);
            box-shadow: 0 6px 18px rgba(191, 174, 128, 0.4);
        }

        .section {
            z-index: 2;
            padding: 4rem 2rem;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .section h2 {
            color: var(--aguilar-light);
            font-weight: 700;
            margin-bottom: 2rem;
        }

        .section p,
        .section h5 {
            color: var(--aguilar-accent);
        }

        footer {
            z-index: 2;
            background-color: rgba(0, 0, 0, 0.5);
            color: var(--aguilar-light);
            text-align: center;
            padding: 1rem;
            font-size: 0.9rem;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes slideDown {
            from { transform: translateY(-30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }

        @keyframes slideUp {
            from { transform: translateY(30px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
    </style>
</head>

<body>
    <div class="overlay"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-md px-4">
        <a class="navbar-brand d-flex align-items-center" href="{{ route('panel') }}">
            <img src="{{ asset('images/logo.png') }}" alt="Logo Calzados Aguilar">
            <span class="text-light fw-semibold">Calzados Aguilar</span>
        </a>
        <div class="ms-auto">
            <a href="{{ route('login.index') }}" class="btn btn-aguilar d-inline-flex align-items-center gap-2">
                <i class="fa-solid fa-right-to-bracket"></i> Acceder
            </a>
        </div>
    </nav>

    <!-- Welcome Content -->
    <div class="welcome-content container">
        <h1>Sistema de gestión integral</h1>
        <p>Donde cada paso cuenta. Gestión inteligente para tu negocio de calzados.</p>
        <a href="{{ route('login.index') }}" class="btn btn-aguilar d-inline-flex align-items-center gap-2">
            <i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión
        </a>
    </div>

    <!-- Beneficios -->
    <section class="section text-center">
        <h2>¿Por qué elegir nuestro sistema?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="p-3 border rounded bg-dark bg-opacity-50">
                    <h5>Inventario en tiempo real</h5>
                    <p>Controla existencias, movimientos y alertas de stock desde cualquier dispositivo.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded bg-dark bg-opacity-50">
                    <h5>Reportes dinámicos</h5>
                    <p>Visualiza ventas, compras y tendencias con gráficos y filtros personalizados.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 border rounded bg-dark bg-opacity-50">
                    <h5>Acceso 24/7</h5>
                    <p>Tu negocio siempre disponible, desde cualquier lugar y dispositivo.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Visión de marca -->
    <section class="section text-center">
        <h2>Calzados Aguilar</h2>
        <p class="lead">Elegancia, precisión y tecnología al servicio de tu tienda.</p>
        <p>Nos especializamos en ofrecer soluciones digitales que reflejan la calidad y estilo de tu marca. Nuestro sistema está diseñado para crecer contigo, adaptarse a tus procesos y proyectar confianza en cada paso.</p>
    </section>

    <!-- Footer -->
    <footer>
        Est. Juan Pablo Ramirez Aguilar – Sistemas Informáticos
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
