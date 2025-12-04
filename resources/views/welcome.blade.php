<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-bs-theme="dark">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=5" />
    <meta name="description" content="Sistema de información web para la gestión integral de inventarios, compras y ventas para Calzados Aguilar" />
    <meta name="author" content="Est. Juan Pablo Ramirez Aguilar - Sistemas Informáticos" />
    <title>Calzados Aguilar – Bienvenido</title>

    <!-- Bootstrap & Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --aguilar-primary: #1A2B4C;
            --aguilar-accent: #BFAE80;
            --aguilar-light: #FAFAFA;
            --aguilar-dark: #0A1429;
        }

        * {
            box-sizing: border-box;
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
            padding: 0.75rem 1rem;
        }

        .navbar-brand {
            font-size: 1.1rem;
        }

        .navbar-brand img {
            height: 35px;
            margin-right: 8px;
        }

        .welcome-content {
            z-index: 2;
            text-align: center;
            color: var(--aguilar-light);
            padding: 4rem 1rem 2rem;
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            animation: fadeIn 1.5s ease-in-out;
        }

        .welcome-content h1 {
            font-size: clamp(2rem, 5vw, 3.5rem);
            font-weight: 700;
            margin-bottom: 1rem;
            line-height: 1.2;
            animation: slideDown 1s ease-out;
        }

        .welcome-content p {
            font-size: clamp(1rem, 2.5vw, 1.4rem);
            color: var(--aguilar-accent);
            margin-bottom: 1.5rem;
            animation: slideUp 1.2s ease-out;
            line-height: 1.5;
            padding: 0 1rem;
        }

        .btn-aguilar {
            background-color: var(--aguilar-primary);
            color: #FFFFFF;
            font-weight: 600;
            border-radius: 50px;
            padding: 0.75rem 1.5rem;
            transition: all 0.3s ease;
            box-shadow: 0 4px 12px rgba(26, 43, 76, 0.3);
            border: none;
            font-size: 1rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 48px;
        }

        .btn-aguilar:hover {
            background-color: var(--aguilar-accent);
            color: var(--aguilar-primary);
            box-shadow: 0 6px 18px rgba(191, 174, 128, 0.4);
        }

        .section {
            z-index: 2;
            padding: clamp(2rem, 5vw, 4rem) 1rem;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .section h2 {
            color: var(--aguilar-light);
            font-weight: 700;
            margin-bottom: 2rem;
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            line-height: 1.3;
        }

        .section p,
        .section h5 {
            color: var(--aguilar-accent);
        }

        .section h5 {
            font-size: 1.25rem;
            margin-bottom: 1rem;
        }

        .section .lead {
            font-size: clamp(1.1rem, 2.5vw, 1.4rem);
        }

        footer {
            z-index: 2;
            background-color: rgba(0, 0, 0, 0.5);
            color: var(--aguilar-light);
            text-align: center;
            padding: 1.5rem 1rem;
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

        /* Sección del Catálogo */
        .catalog-section {
            background: rgba(26, 43, 76, 0.7);
            border-radius: 15px;
            padding: clamp(1.5rem, 3vw, 3rem);
            margin: clamp(1rem, 3vw, 2rem) auto;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(191, 174, 128, 0.3);
            max-width: 1400px;
        }

        .product-preview-card {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: clamp(1rem, 2vw, 1.5rem);
            height: 100%;
            transition: all 0.3s ease;
            border: 1px solid rgba(191, 174, 128, 0.2);
            display: flex;
            flex-direction: column;
        }

        .product-preview-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
            border-color: var(--aguilar-accent);
        }

        .product-preview-image {
            width: 100%;
            height: clamp(120px, 25vw, 220px);
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 1rem;
            border: 2px solid rgba(191, 174, 128, 0.2);
            aspect-ratio: 4/3;
        }

        .product-preview-price {
            font-size: clamp(1rem, 2vw, 1.4rem);
            font-weight: bold;
            color: #28a745;
            margin: 0.5rem 0;
        }

        .btn-view-catalog {
            background: linear-gradient(135deg, var(--aguilar-primary), #2c3e50);
            border: none;
            color: white;
            padding: clamp(0.7rem, 1.5vw, 1rem) clamp(1.2rem, 2.5vw, 2rem);
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
            font-size: clamp(0.9rem, 1.5vw, 1.1rem);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            min-height: 48px;
        }

        .btn-view-catalog:hover {
            background: linear-gradient(135deg, var(--aguilar-accent), #D4AF37);
            color: var(--aguilar-primary);
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(191, 174, 128, 0.3);
        }

        .loading-catalog {
            text-align: center;
            padding: 2rem;
            color: var(--aguilar-accent);
        }

        /* Grid responsivo */
        .row.g-4 {
            --bs-gutter-x: 1rem;
            --bs-gutter-y: 1rem;
        }

        @media (max-width: 768px) {
            .row.g-4 {
                --bs-gutter-x: 0.75rem;
                --bs-gutter-y: 0.75rem;
            }
        }

        /* Tarjetas de beneficios */
        .benefit-card {
            background: rgba(255, 255, 255, 0.05);
            border-radius: 12px;
            padding: 1.5rem;
            height: 100%;
            border: 1px solid rgba(191, 174, 128, 0.1);
            transition: transform 0.3s ease;
        }

        .benefit-card:hover {
            transform: translateY(-3px);
            border-color: rgba(191, 174, 128, 0.3);
        }

        /* Media Queries completas */
        @media (max-width: 1400px) {
            .catalog-section {
                margin: 1.5rem 1rem;
            }
        }

        @media (max-width: 1200px) {
            .welcome-content {
                padding: 3.5rem 1rem 2rem;
            }

            .section {
                padding: 3rem 1rem;
            }
        }

        @media (max-width: 992px) {
            .navbar {
                padding: 0.5rem 0.75rem;
            }

            .navbar-brand img {
                height: 30px;
            }

            .welcome-content {
                padding: 3rem 1rem 1.5rem;
            }

            .benefit-card {
                padding: 1.25rem;
            }
        }

        @media (max-width: 768px) {
            body {
                background-attachment: scroll;
            }

            .navbar-brand span {
                font-size: 1rem;
            }

            .welcome-content {
                padding: 2.5rem 0.5rem 1rem;
            }

            .section {
                padding: 2rem 0.5rem;
            }

            .catalog-section {
                padding: 1.25rem;
                margin: 0.75rem 0.5rem;
                border-radius: 12px;
            }

            .product-preview-card {
                padding: 0.75rem;
            }

            .btn-aguilar,
            .btn-view-catalog {
                min-height: 44px;
                padding: 0.6rem 1.2rem;
            }

            footer {
                padding: 1rem 0.5rem;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 576px) {
            .navbar-brand {
                font-size: 1rem;
            }

            .navbar-brand img {
                height: 25px;
                margin-right: 5px;
            }

            .welcome-content h1 {
                margin-bottom: 0.75rem;
                padding: 0 0.5rem;
            }

            .welcome-content p {
                padding: 0;
            }

            .catalog-section {
                padding: 1rem;
                margin: 0.5rem;
            }

            .product-preview-card h6 {
                font-size: 0.95rem;
                min-height: 40px;
            }

            .product-preview-card small {
                font-size: 0.8rem;
            }

            #productCountText {
                font-size: 0.85rem;
            }

            .section h5 {
                font-size: 1.1rem;
            }

            .section p {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 375px) {
            .navbar-brand span {
                font-size: 0.9rem;
            }

            .btn-aguilar,
            .btn-view-catalog {
                font-size: 0.9rem;
                padding: 0.5rem 1rem;
                min-height: 40px;
            }

            .product-preview-image {
                height: 100px;
            }

            .product-preview-card h6 {
                font-size: 0.9rem;
                min-height: 35px;
            }

            footer {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 320px) {
            .welcome-content h1 {
                font-size: 1.8rem;
            }

            .navbar-brand {
                flex-direction: column;
                align-items: flex-start;
            }

            .navbar-brand img {
                margin-bottom: 2px;
            }

            .btn-aguilar,
            .btn-view-catalog {
                width: 100%;
                max-width: 200px;
                margin: 0 auto;
            }
        }

        /* Mejoras para tablets en orientación vertical */
        @media (min-width: 768px) and (max-width: 1024px) and (orientation: portrait) {
            .welcome-content {
                padding: 5rem 2rem 3rem;
            }

            .product-preview-image {
                height: 180px;
            }
        }

        /* Mejoras para dispositivos con altura limitada */
        @media (max-height: 600px) {
            .welcome-content {
                padding-top: 2rem;
                padding-bottom: 1rem;
            }

            .section {
                padding-top: 1.5rem;
                padding-bottom: 1.5rem;
            }
        }

        /* Optimización para impresión */
        @media print {
            .overlay,
            .navbar,
            .btn-aguilar,
            .btn-view-catalog,
            footer {
                display: none;
            }

            body {
                background: white !important;
                color: black !important;
            }

            .section {
                background: white !important;
                color: black !important;
                border: 1px solid #ddd;
            }
        }

        /* Soporte para modo oscuro del sistema */
        @media (prefers-color-scheme: dark) {
            :root {
                --aguilar-light: #FAFAFA;
                --aguilar-dark: #0A1429;
            }
        }

        /* Prevenir desbordamiento de texto */
        .text-truncate-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        /* Mejoras de accesibilidad */
        .btn-aguilar:focus,
        .btn-view-catalog:focus {
            outline: 2px solid var(--aguilar-accent);
            outline-offset: 2px;
        }

        /* Optimización de imágenes */
        .product-preview-image {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="overlay"></div>

    <!-- Navbar -->
    <nav class="navbar navbar-expand-md px-3 px-md-4">
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
        <a href="{{ route('login.index') }}" class="btn btn-aguilar d-inline-flex align-items-center gap-2 mx-auto">
            <i class="fa-solid fa-right-to-bracket"></i> Iniciar sesión
        </a>
    </div>

    <!-- Sección del Catálogo -->
    <section class="section text-center">
        <div class="container">
            <div class="catalog-section">
                <h2>Nuestro Catálogo de Calzados</h2>
                <p class="mb-4">Descubre nuestra colección de calzados de calidad para toda la familia</p>

                <!-- Contenedor para productos del catálogo -->
                <div class="row mt-4 g-4" id="catalogPreviewContainer">
                    <div class="loading-catalog">
                        <i class="fas fa-spinner fa-spin fa-2x"></i>
                        <p>Cargando catálogo...</p>
                    </div>
                </div>

                <div class="mt-5">
                    <a href="{{ route('catalogo.publico') }}" class="btn-view-catalog">
                        <i class="fas fa-eye me-2"></i> Ver Catálogo Completo
                    </a>
                    <p class="text-muted mt-2" id="productCountText">0+ calzados disponibles</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Beneficios -->
    <section class="section text-center">
        <h2>¿Por qué elegir nuestro sistema?</h2>
        <div class="row g-4">
            <div class="col-md-4">
                <div class="benefit-card">
                    <h5>Inventario en tiempo real</h5>
                    <p>Controla existencias, movimientos y alertas de stock desde cualquier dispositivo.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="benefit-card">
                    <h5>Reportes dinámicos</h5>
                    <p>Visualiza ventas, compras y tendencias con gráficos y filtros personalizados.</p>
                </div>
            </div>
            <div class="col-md-4">
                <div class="benefit-card">
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
        <div class="container">
            <p class="mb-0">Est. Juan Pablo Ramirez Aguilar – Sistemas Informáticos</p>
            <p class="mb-0 mt-2">
                <a href="{{ route('catalogo.publico') }}" class="text-light text-decoration-none">
                    <i class="fas fa-shoe-prints me-1"></i> Ver catálogo de calzados
                </a>
            </p>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            cargarCatalogoPreview();

            function cargarCatalogoPreview() {
                const container = document.getElementById('catalogPreviewContainer');
                const productCountText = document.getElementById('productCountText');

                fetch('/catalogo-publico?limit=4&_=' + new Date().getTime(), {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Error en la respuesta del servidor');
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success && data.productos && data.productos.length > 0) {
                        // Actualizar contador
                        productCountText.textContent = data.total + '+ calzados disponibles';

                        // Generar las tarjetas de productos
                        let html = '';
                        data.productos.forEach(producto => {
                            html += `
                                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12">
                                    <div class="product-preview-card">
                                        <img src="${producto.img_path}"
                                             alt="${producto.nombre}"
                                             class="product-preview-image"
                                             onerror="this.src='{{ asset('assets/img/calzado-default.png') }}'">
                                        <h6 class="text-light text-truncate-2">${producto.nombre}</h6>
                                        <div class="product-preview-price">
                                            ${producto.precio_formatted}
                                        </div>
                                        <small class="text-muted d-block">
                                            <i class="fas fa-tag me-1"></i> ${producto.marca_nombre}
                                        </small>
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-folder me-1"></i> ${producto.categoria_nombre}
                                        </small>
                                    </div>
                                </div>
                            `;
                        });

                        container.innerHTML = html;
                    } else {
                        mostrarMensajeSinProductos();
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    mostrarMensajeError();
                });
            }

            function mostrarMensajeSinProductos() {
                const container = document.getElementById('catalogPreviewContainer');
                container.innerHTML = `
                    <div class="col-12">
                        <div class="text-center py-4">
                            <i class="fas fa-shoe-prints fa-3x text-muted mb-3"></i>
                            <h5 class="text-light">Próximamente</h5>
                            <p class="text-muted">Estamos preparando nuestro catálogo de calzados.</p>
                        </div>
                    </div>
                `;
            }

            function mostrarMensajeError() {
                const container = document.getElementById('catalogPreviewContainer');
                container.innerHTML = `
                    <div class="col-12">
                        <div class="text-center py-4">
                            <i class="fas fa-exclamation-triangle fa-3x text-warning mb-3"></i>
                            <h5 class="text-light">Error al cargar</h5>
                            <p class="text-muted">No se pudo cargar el catálogo en este momento.</p>
                            <a href="{{ route('catalogo.publico') }}" class="btn btn-sm btn-outline-warning mt-2">
                                Intentar ver catálogo completo
                            </a>
                        </div>
                    </div>
                `;
            }
        });
    </script>
</body>
</html>
