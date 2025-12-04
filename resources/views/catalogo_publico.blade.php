<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catálogo - Calzados Aguilar</title>

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        :root {
            --primary-color: #1A2B4C;
            --secondary-color: #D4AF37;
            --light-bg: #FAFAF5;
        }

        /* FONDO CON IMAGEN fondo-calzados.jpg */
        body {
            background: url("{{ asset('images/fondo-calzados.jpg') }}") no-repeat center center fixed;
            background-size: cover;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            color: #333;
            padding-top: 70px;
            min-height: 100vh;
            position: relative;
            overflow-x: hidden; /* Prevenir scroll horizontal */
        }

        /* Overlay semi-transparente para mejorar legibilidad */
        body::before {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(255, 255, 255, 0.85);
            z-index: -1;
        }

        /* Contenedor principal con fondo semi-transparente */
        .container-fluid {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            margin-top: 20px;
            margin-bottom: 40px;
            padding: 30px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            max-width: 100%;
            overflow: hidden; /* Prevenir desbordamiento */
        }

        /* MEJORA RESPONSIVA: Ajustes para pantallas pequeñas */
        @media (max-width: 768px) {
            .container-fluid {
                padding: 20px 15px;
                margin: 15px 10px;
                border-radius: 15px;
            }
        }

        @media (max-width: 480px) {
            .container-fluid {
                padding: 15px 10px;
                margin: 10px 5px;
                border-radius: 12px;
            }
        }

        /* Navbar Público - Más transparente para fondo */
        .navbar-publico {
            background: rgba(26, 43, 76, 0.95);
            backdrop-filter: blur(10px);
            box-shadow: 0 2px 15px rgba(0,0,0,0.15);
            padding: 10px 0;
        }

        .navbar-brand img {
            height: 40px;
            margin-right: 10px;
        }

        /* MEJORA RESPONSIVA: Ajustes navbar móvil */
        @media (max-width: 768px) {
            .navbar-publico {
                padding: 8px 0;
            }

            .navbar-brand span {
                font-size: 1rem;
            }

            .navbar-brand img {
                height: 35px;
            }

            .btn-outline-light {
                padding: 6px 12px;
                font-size: 0.9rem;
            }

            body {
                padding-top: 60px;
            }
        }

        @media (max-width: 480px) {
            .navbar-brand span {
                font-size: 0.9rem;
            }

            .navbar-brand img {
                height: 30px;
                margin-right: 5px;
            }

            .btn-outline-light {
                padding: 5px 10px;
                font-size: 0.85rem;
            }

            .btn-outline-light i {
                margin-right: 2px;
            }
        }

        /* Product Cards con fondo blanco sólido para contraste */
        .product-card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: none;
            border-radius: 15px;
            overflow: hidden;
            background-color: #FFFFFF;
            height: 100%;
            border: 1px solid rgba(26, 43, 76, 0.1);
            margin-bottom: 20px;
        }

        .product-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.2);
            border-color: var(--secondary-color);
        }

        /* MEJORA RESPONSIVA: Eliminar transform hover en móviles */
        @media (max-width: 768px) {
            .product-card:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 20px rgba(0,0,0,0.15);
            }
        }

        @media (max-width: 480px) {
            .product-card:hover {
                transform: translateY(-2px);
                box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            }
        }

        .product-image {
            height: 220px;
            object-fit: cover;
            background: linear-gradient(135deg, #f8f9fa, #e9ecef);
            width: 100%;
        }

        /* MEJORA RESPONSIVA: Ajuste altura imagen en móviles */
        @media (max-width: 992px) {
            .product-image {
                height: 200px;
            }
        }

        @media (max-width: 768px) {
            .product-image {
                height: 180px;
            }
        }

        @media (max-width: 576px) {
            .product-image {
                height: 160px;
            }
        }

        @media (max-width: 400px) {
            .product-image {
                height: 140px;
            }
        }

        /* PRECIO DESTACADO */
        .price-tag {
            font-size: 1.6rem;
            font-weight: 800;
            color: #28a745;
            text-shadow: 1px 1px 3px rgba(40, 167, 69, 0.2);
            padding: 10px 15px;
            background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(40, 167, 69, 0.05));
            border-radius: 10px;
            border-left: 5px solid #28a745;
            margin: 12px 0;
            position: relative;
            overflow: hidden;
            word-break: break-word;
        }

        .price-tag::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transform: translateX(-100%);
            animation: shine 3s infinite;
        }

        @keyframes shine {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
        }

        /* MEJORA RESPONSIVA: Ajuste tamaño precio */
        @media (max-width: 992px) {
            .price-tag {
                font-size: 1.4rem;
                padding: 8px 12px;
            }
        }

        @media (max-width: 768px) {
            .price-tag {
                font-size: 1.3rem;
                padding: 7px 10px;
            }
        }

        @media (max-width: 576px) {
            .price-tag {
                font-size: 1.2rem;
                padding: 6px 8px;
                margin: 8px 0;
            }
        }

        @media (max-width: 400px) {
            .price-tag {
                font-size: 1.1rem;
                padding: 5px 6px;
                margin: 6px 0;
            }
        }

        .product-title {
            font-size: 1.2rem;
            font-weight: 700;
            color: #1A2B4C;
            height: auto;
            min-height: 50px;
            padding: 5px 0;
            border-bottom: 2px solid #D4AF37;
            margin-bottom: 15px;
            word-break: break-word;
            line-height: 1.3;
        }

        /* MEJORA RESPONSIVA: Ajuste título producto */
        @media (max-width: 992px) {
            .product-title {
                font-size: 1.1rem;
                min-height: 45px;
            }
        }

        @media (max-width: 768px) {
            .product-title {
                font-size: 1rem;
                min-height: 40px;
                margin-bottom: 10px;
                padding: 3px 0;
            }
        }

        @media (max-width: 576px) {
            .product-title {
                font-size: 0.95rem;
                min-height: 36px;
            }
        }

        /* Catalog Header con gradiente semi-transparente */
        .catalog-header {
            background: linear-gradient(135deg, rgba(26, 43, 76, 0.9), rgba(44, 62, 80, 0.9));
            color: #FFFFFF;
            border-radius: 15px;
            padding: 2rem;
            margin-bottom: 2rem;
            text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
            backdrop-filter: blur(5px);
        }

        /* MEJORA RESPONSIVA: Ajuste header */
        @media (max-width: 992px) {
            .catalog-header {
                padding: 1.5rem;
                margin-bottom: 1.5rem;
            }

            .catalog-header h1 {
                font-size: 1.8rem;
            }

            .catalog-header .lead {
                font-size: 1rem;
            }
        }

        @media (max-width: 768px) {
            .catalog-header {
                padding: 1.2rem;
                margin-bottom: 1.2rem;
                border-radius: 12px;
            }

            .catalog-header h1 {
                font-size: 1.5rem;
            }

            .catalog-header .lead {
                font-size: 0.95rem;
            }
        }

        @media (max-width: 480px) {
            .catalog-header {
                padding: 1rem;
                margin-bottom: 1rem;
                border-radius: 10px;
            }

            .catalog-header h1 {
                font-size: 1.3rem;
            }

            .catalog-header .lead {
                font-size: 0.9rem;
            }
        }

        /* BÚSQUEDA con fondo semi-transparente */
        .search-section {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 2rem;
            margin: 0 auto 2rem;
            border: 2px solid rgba(26, 43, 76, 0.15);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
            max-width: 900px;
            width: 100%;
        }

        /* MEJORA RESPONSIVA: Ajuste sección búsqueda */
        @media (max-width: 992px) {
            .search-section {
                padding: 1.5rem;
                margin: 0 15px 1.5rem;
            }
        }

        @media (max-width: 768px) {
            .search-section {
                padding: 1.2rem;
                margin: 0 10px 1.2rem;
                border-radius: 12px;
            }
        }

        @media (max-width: 480px) {
            .search-section {
                padding: 1rem;
                margin: 0 5px 1rem;
                border-radius: 10px;
            }
        }

        .search-box {
            position: relative;
            margin: 0 auto;
            max-width: 800px;
        }

        .search-icon {
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            color: var(--primary-color);
            z-index: 10;
        }

        /* MEJORA RESPONSIVA: Ajuste icono búsqueda */
        @media (max-width: 768px) {
            .search-icon {
                left: 15px;
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .search-icon {
                left: 10px;
                font-size: 0.85rem;
            }
        }

        .search-input {
            padding-left: 50px;
            padding-right: 120px;
            height: 55px;
            font-size: 1.1rem;
            border: 2px solid var(--primary-color);
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.95);
            color: #2C2C2C;
            transition: all 0.3s ease;
            width: 100%;
        }

        /* MEJORA RESPONSIVA: Ajuste campo búsqueda */
        @media (max-width: 992px) {
            .search-input {
                height: 50px;
                font-size: 1rem;
                padding-left: 45px;
                padding-right: 110px;
            }
        }

        @media (max-width: 768px) {
            .search-input {
                height: 48px;
                font-size: 0.95rem;
                padding-left: 40px;
                padding-right: 100px;
                border-radius: 10px;
            }
        }

        @media (max-width: 576px) {
            .search-input {
                height: 46px;
                font-size: 0.9rem;
                padding-left: 35px;
                padding-right: 90px;
            }
        }

        @media (max-width: 480px) {
            .search-input {
                height: 44px;
                font-size: 0.85rem;
                padding-left: 30px;
                padding-right: 80px;
                border-radius: 8px;
            }
        }

        @media (max-width: 380px) {
            .search-input {
                height: 42px;
                padding-left: 28px;
                padding-right: 75px;
            }
        }

        .search-input:focus {
            border-color: var(--secondary-color);
            box-shadow: 0 0 0 0.3rem rgba(212, 175, 55, 0.25);
            background: white;
        }

        .search-btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            height: 45px;
            padding: 0 25px;
            background: linear-gradient(135deg, var(--primary-color), #2c3e50);
            border: none;
            border-radius: 10px;
            color: white;
            font-weight: 600;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        /* MEJORA RESPONSIVA: Ajuste botón búsqueda */
        @media (max-width: 992px) {
            .search-btn {
                height: 42px;
                padding: 0 22px;
                font-size: 0.95rem;
            }
        }

        @media (max-width: 768px) {
            .search-btn {
                height: 40px;
                padding: 0 18px;
                font-size: 0.9rem;
                border-radius: 8px;
            }

            .search-btn i {
                margin-right: 4px;
            }
        }

        @media (max-width: 576px) {
            .search-btn {
                height: 38px;
                padding: 0 15px;
                font-size: 0.85rem;
                right: 3px;
            }

            .search-btn i {
                margin-right: 3px;
            }
        }

        @media (max-width: 480px) {
            .search-btn {
                height: 36px;
                padding: 0 12px;
                font-size: 0.8rem;
                border-radius: 6px;
            }

            .search-btn i {
                margin-right: 2px;
            }

            .search-btn span {
                display: none; /* Ocultar texto "Buscar" en móviles muy pequeños */
            }

            .search-btn i {
                margin-right: 0;
            }
        }

        .search-btn:hover {
            background: linear-gradient(135deg, var(--secondary-color), #D4AF37);
            color: var(--primary-color);
            transform: translateY(-50%) scale(1.05);
        }

        /* MEJORA RESPONSIVA: Eliminar transform hover en móviles */
        @media (max-width: 768px) {
            .search-btn:hover {
                transform: translateY(-50%) scale(1.02);
            }
        }

        .product-specs {
            font-size: 0.85rem;
        }

        /* MEJORA RESPONSIVA: Ajuste especificaciones */
        @media (max-width: 768px) {
            .product-specs {
                font-size: 0.8rem;
            }
        }

        @media (max-width: 480px) {
            .product-specs {
                font-size: 0.75rem;
            }
        }

        .categoria-badge {
            background: #1A2B4C;
            color: #FFFFFF;
            padding: 5px 10px;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: 600;
            display: inline-block;
            margin: 5px 0;
            word-break: break-word;
        }

        /* MEJORA RESPONSIVA: Ajuste badge categoría */
        @media (max-width: 768px) {
            .categoria-badge {
                padding: 4px 8px;
                font-size: 0.7rem;
            }
        }

        @media (max-width: 480px) {
            .categoria-badge {
                padding: 3px 6px;
                font-size: 0.65rem;
            }
        }

        .badge.bg-success {
            background-color: #D4AF37 !important;
            color: #1A2B4C !important;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 8px;
            box-shadow: 0 2px 5px rgba(212, 175, 55, 0.3);
            font-size: 0.8rem;
        }

        .badge.bg-danger {
            background-color: #dc3545 !important;
            font-weight: 600;
            padding: 6px 10px;
            border-radius: 8px;
            font-size: 0.8rem;
        }

        /* MEJORA RESPONSIVA: Ajuste badges */
        @media (max-width: 768px) {
            .badge.bg-success,
            .badge.bg-danger {
                padding: 5px 8px;
                font-size: 0.75rem;
            }
        }

        @media (max-width: 480px) {
            .badge.bg-success,
            .badge.bg-danger {
                padding: 4px 6px;
                font-size: 0.7rem;
            }
        }

        .text-muted {
            color: #666 !important;
        }

        .text-primary {
            color: #1A2B4C !important;
            font-weight: 600;
        }

        .card-body {
            padding: 1.25rem;
        }

        /* MEJORA RESPONSIVA: Ajuste card body */
        @media (max-width: 768px) {
            .card-body {
                padding: 1rem;
            }
        }

        @media (max-width: 480px) {
            .card-body {
                padding: 0.8rem;
            }
        }

        /* STOCK INFO */
        .stock-info {
            font-size: 0.8rem;
            padding: 4px 8px;
            border-radius: 4px;
            margin-top: 5px;
        }

        .in-stock {
            background-color: rgba(212, 175, 55, 0.15);
            color: #D4AF37;
            font-weight: 600;
            border: 1px solid rgba(212, 175, 55, 0.3);
        }

        .out-of-stock {
            background-color: rgba(220, 53, 69, 0.15);
            color: #dc3545;
            font-weight: 600;
            border: 1px solid rgba(220, 53, 69, 0.3);
        }

        /* MEJORA RESPONSIVA: Ajuste info stock */
        @media (max-width: 768px) {
            .stock-info {
                font-size: 0.75rem;
                padding: 3px 6px;
            }
        }

        @media (max-width: 480px) {
            .stock-info {
                font-size: 0.7rem;
                padding: 2px 4px;
            }
        }

        .product-highlight {
            animation: pulseHighlight 2s ease-in-out;
        }

        @keyframes pulseHighlight {
            0% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.7); }
            70% { box-shadow: 0 0 0 10px rgba(212, 175, 55, 0); }
            100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
        }

        /* MEJORA RESPONSIVA: Reducir animación en móviles */
        @media (max-width: 768px) {
            @keyframes pulseHighlight {
                0% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.5); }
                70% { box-shadow: 0 0 0 5px rgba(212, 175, 55, 0); }
                100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
            }
        }

        /* Footer con gradiente semi-transparente */
        .footer-publico {
            background: linear-gradient(135deg, rgba(26, 43, 76, 0.95), rgba(44, 62, 80, 0.95));
            color: white;
            padding: 40px 0;
            margin-top: 60px;
            backdrop-filter: blur(10px);
        }

        /* MEJORA RESPONSIVA: Ajuste footer */
        @media (max-width: 992px) {
            .footer-publico {
                padding: 30px 0;
                margin-top: 40px;
            }
        }

        @media (max-width: 768px) {
            .footer-publico {
                padding: 25px 0;
                margin-top: 30px;
            }

            .footer-publico h5,
            .footer-publico h6 {
                font-size: 1rem;
            }

            .footer-publico p,
            .footer-publico ul {
                font-size: 0.9rem;
            }
        }

        @media (max-width: 480px) {
            .footer-publico {
                padding: 20px 0;
                margin-top: 25px;
            }

            .footer-publico h5,
            .footer-publico h6 {
                font-size: 0.95rem;
            }

            .footer-publico p,
            .footer-publico ul {
                font-size: 0.85rem;
            }

            .footer-publico .text-center {
                font-size: 0.8rem;
            }
        }

        /* Paginación */
        .pagination .page-link {
            color: var(--primary-color);
            background: white;
            border: 1px solid rgba(26, 43, 76, 0.2);
            font-size: 0.9rem;
            padding: 8px 12px;
        }

        .pagination .page-item.active .page-link {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
        }

        .pagination .page-link:hover {
            background-color: rgba(26, 43, 76, 0.1);
        }

        /* MEJORA RESPONSIVA: Ajuste paginación */
        @media (max-width: 768px) {
            .pagination .page-link {
                font-size: 0.85rem;
                padding: 6px 10px;
            }
        }

        @media (max-width: 480px) {
            .pagination .page-link {
                font-size: 0.8rem;
                padding: 5px 8px;
            }

            .pagination {
                flex-wrap: wrap;
                justify-content: center;
            }
        }

        /* Product counter */
        .product-counter {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
            padding: 10px 20px;
            margin: 15px 0;
            display: inline-block;
            font-weight: 600;
            color: #D4AF37;
            backdrop-filter: blur(5px);
            font-size: 0.9rem;
        }

        /* MEJORA RESPONSIVA: Ajuste contador */
        @media (max-width: 768px) {
            .product-counter {
                padding: 8px 15px;
                font-size: 0.85rem;
                margin: 10px 0;
            }
        }

        @media (max-width: 480px) {
            .product-counter {
                padding: 6px 12px;
                font-size: 0.8rem;
            }
        }

        /* Contenedor centrado para la sección de búsqueda */
        .search-container {
            display: flex;
            justify-content: center;
            padding: 0 15px;
        }

        /* Botón para limpiar búsqueda */
        .btn-clear {
            background: rgba(220, 53, 69, 0.1);
            border: 1px solid #dc3545;
            color: #dc3545;
            padding: 10px 20px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            margin-top: 15px;
            transition: all 0.3s ease;
            font-size: 0.9rem;
        }

        .btn-clear:hover {
            background: #dc3545;
            color: white;
        }

        /* MEJORA RESPONSIVA: Ajuste botón limpiar */
        @media (max-width: 768px) {
            .btn-clear {
                padding: 8px 16px;
                font-size: 0.85rem;
            }
        }

        @media (max-width: 480px) {
            .btn-clear {
                padding: 6px 12px;
                font-size: 0.8rem;
                margin-top: 10px;
            }
        }

        /* Mensaje cuando no hay productos */
        .no-products-message {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            padding: 40px;
            text-align: center;
            border: 2px solid rgba(26, 43, 76, 0.1);
        }

        /* MEJORA RESPONSIVA: Ajuste mensaje sin productos */
        @media (max-width: 992px) {
            .no-products-message {
                padding: 30px;
            }
        }

        @media (max-width: 768px) {
            .no-products-message {
                padding: 25px;
            }

            .no-products-message h3 {
                font-size: 1.3rem;
            }
        }

        @media (max-width: 480px) {
            .no-products-message {
                padding: 20px;
                border-radius: 12px;
            }

            .no-products-message h3 {
                font-size: 1.1rem;
            }

            .no-products-message p {
                font-size: 0.9rem;
            }

            .no-products-message .btn {
                padding: 8px 16px;
                font-size: 0.9rem;
            }
        }

        /* Animación de entrada para productos */
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .product-item {
            animation: fadeInUp 0.5s ease forwards;
            opacity: 0;
        }

        .product-item:nth-child(1) { animation-delay: 0.1s; }
        .product-item:nth-child(2) { animation-delay: 0.2s; }
        .product-item:nth-child(3) { animation-delay: 0.3s; }
        .product-item:nth-child(4) { animation-delay: 0.4s; }
        .product-item:nth-child(5) { animation-delay: 0.5s; }
        .product-item:nth-child(6) { animation-delay: 0.6s; }
        .product-item:nth-child(7) { animation-delay: 0.7s; }
        .product-item:nth-child(8) { animation-delay: 0.8s; }

        /* MEJORA RESPONSIVA: Reducir animaciones en móviles */
        @media (max-width: 768px) {
            .product-item {
                animation: fadeInUp 0.3s ease forwards;
            }

            @media (prefers-reduced-motion: reduce) {
                .product-item {
                    animation: none;
                    opacity: 1;
                }
            }
        }

        /* Ajustes específicos para grid responsivo */
        /* Pantallas grandes: 4 columnas */
        @media (min-width: 1200px) {
            .product-item {
                flex: 0 0 25%;
                max-width: 25%;
            }
        }

        /* Pantallas medianas: 3 columnas */
        @media (min-width: 992px) and (max-width: 1199px) {
            .product-item {
                flex: 0 0 33.333333%;
                max-width: 33.333333%;
            }
        }

        /* Tablets: 2 columnas */
        @media (min-width: 768px) and (max-width: 991px) {
            .product-item {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        /* Móviles grandes: 2 columnas */
        @media (min-width: 576px) and (max-width: 767px) {
            .product-item {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }

        /* Móviles pequeños: 1 columna */
        @media (max-width: 575px) {
            .product-item {
                flex: 0 0 100%;
                max-width: 100%;
            }
        }

        /* Asegurar que las imágenes mantengan proporción */
        img {
            max-width: 100%;
            height: auto;
        }

        /* Mejorar legibilidad en móviles */
        @media (max-width: 768px) {
            h1, h2, h3, h4, h5, h6 {
                word-break: break-word;
            }

            p, span, a, li, small {
                word-break: break-word;
            }
        }

        /* Optimizar scroll en móviles */
        @media (max-width: 768px) {
            html {
                -webkit-overflow-scrolling: touch;
            }
        }

        /* Mejorar accesibilidad táctil */
        @media (max-width: 768px) {
            button,
            .btn,
            a,
            .page-link,
            .search-input {
                min-height: 44px; /* Tamaño mínimo recomendado para toques */
            }

            .search-btn,
            .btn-clear {
                min-height: 36px;
            }
        }

        /* Optimizar para orientación horizontal en móviles */
        @media (max-height: 500px) and (orientation: landscape) {
            .product-image {
                height: 120px;
            }

            .product-title {
                min-height: auto;
                font-size: 0.9rem;
            }

            .card-body {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar Público Simple -->
    <nav class="navbar navbar-expand-lg navbar-dark fixed-top navbar-publico">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="{{ route('panel') }}">
                @if(file_exists(public_path('images/logo.png')))
                    <img src="{{ asset('images/logo.png') }}" alt="Logo Calzados Aguilar">
                @endif
                <span>Calzados Aguilar</span>
            </a>

            <div class="ms-auto">
                <a href="{{ route('panel') }}" class="btn btn-outline-light">
                    <i class="fas fa-home me-1"></i> Volver al Inicio
                </a>
            </div>
        </div>
    </nav>

    <!-- Contenedor principal -->
    <div class="container-fluid px-4 mt-4">
        <!-- Header del Catálogo -->
        <div class="catalog-header text-center">
            <h1 class="display-5 fw-bold">Catálogo de Calzados Aguilar</h1>
            <p class="lead">Calzado de calidad para toda la familia</p>
            <div class="product-counter">
                <i class="fas fa-box me-2"></i>Total: {{ $productos->total() }} productos disponibles
            </div>
        </div>

        <!-- BÚSQUEDA SIMPLE -->
        <div class="search-container">
            <div class="search-section">
                <form action="{{ route('catalogo.publico') }}" method="GET" id="searchForm">
                    <div class="text-center mb-4">
                        <div class="search-box">
                            <i class="fas fa-search search-icon"></i>
                            <input type="text"
                                   class="form-control search-input"
                                   name="busqueda"
                                   id="busquedaInput"
                                   value="{{ request('busqueda', '') }}"
                                   placeholder="Buscar calzados por nombre, marca, categoría..."
                                   aria-label="Buscar calzados">
                            <button class="search-btn" type="submit">
                                <i class="fas fa-search me-2"></i> <span>Buscar</span>
                            </button>
                        </div>

                        @if(request('busqueda'))
                            <a href="{{ route('catalogo.publico') }}" class="btn-clear">
                                <i class="fas fa-times me-1"></i> Limpiar búsqueda
                            </a>
                        @endif
                    </div>
                </form>
            </div>
        </div>

        <!-- Grid de Productos -->
        <div class="row" id="productsGrid">
            @forelse($productos as $index => $producto)
                @php
                    // Información básica del producto
                    $marcaNombre = $producto->marca->caracteristica->nombre ?? 'Sin marca';
                    $categoriaNombre = $producto->categoria->caracteristica->nombre ?? 'Sin categoría';
                    $presentacionNombre = $producto->presentacione->caracteristica->nombre ?? 'Sin modelo';

                    // Verificar stock
                    $tieneStock = false;
                    $cantidadStock = 0;
                    if (isset($producto->inventario) && $producto->inventario) {
                        $tieneStock = $producto->inventario->cantidad > 0;
                        $cantidadStock = $producto->inventario->cantidad;
                    }

                    // Obtener imagen del producto
                    $imagenProducto = $producto->img_path ? asset($producto->img_path) : asset('assets/img/calzado-default.png');

                    // USAR EL PRECIO CORRECTO - Compatible con ambas columnas
                    $precio = $producto->precio ?? $producto->precio_venta ?? 0;
                @endphp

                <div class="col-xl-3 col-lg-4 col-md-6 col-sm-6 col-12 mb-4 product-item"
                     data-name="{{ strtolower($producto->nombre) }}"
                     data-marca="{{ strtolower($marcaNombre) }}"
                     data-categoria="{{ strtolower($categoriaNombre) }}"
                     data-precio="{{ $precio }}"
                     style="animation-delay: {{ ($index % 8) * 0.1 }}s">

                    <div class="card product-card h-100">
                        <!-- Badge de Estado -->
                        <div class="position-absolute top-0 start-0 p-2">
                            @if($producto->estado == 1)
                                <span class="badge bg-success">DISPONIBLE</span>
                            @else
                                <span class="badge bg-danger">AGOTADO</span>
                            @endif
                        </div>

                        <!-- Imagen del Calzado -->
                        <img src="{{ $imagenProducto }}"
                             class="card-img-top product-image"
                             alt="{{ $producto->nombre }}"
                             onerror="this.src='{{ asset('assets/img/calzado-default.png') }}'"
                             loading="lazy">

                        <div class="card-body d-flex flex-column">
                            <!-- Información del Calzado - NOMBRE DESTACADO -->
                            <h5 class="card-title product-title">{{ $producto->nombre }}</h5>

                            <!-- PRECIO DESTACADO -->
                            <div class="mb-2">
                                @if($precio > 0)
                                    <div class="price-tag">Bs. {{ number_format($precio, 2, ',', '.') }}</div>
                                @else
                                    <div class="text-muted">Consultar precio</div>
                                @endif
                            </div>

                            <!-- Información de Stock -->
                            @if($tieneStock)
                                <div class="stock-info in-stock">
                                    <i class="fas fa-check-circle"></i> Disponible: {{ $cantidadStock }} unidades
                                </div>
                            @else
                                <div class="stock-info out-of-stock">
                                    <i class="fas fa-times-circle"></i> Temporalmente agotado
                                </div>
                            @endif

                            <!-- Especificaciones del Calzado -->
                            <div class="product-specs mt-2">
                                @if($marcaNombre && $marcaNombre !== 'Sin marca')
                                    <small class="text-muted d-block">
                                        <i class="fas fa-tag"></i> Marca: {{ $marcaNombre }}
                                    </small>
                                @endif

                                @if($categoriaNombre && $categoriaNombre !== 'Sin categoría')
                                    <small class="d-block mt-1">
                                        <span class="categoria-badge">
                                            <i class="fas fa-folder"></i> {{ $categoriaNombre }}
                                        </span>
                                    </small>
                                @endif

                                @if($presentacionNombre && $presentacionNombre !== 'Sin modelo')
                                    <small class="text-primary d-block mt-2">
                                        <i class="fas fa-shoe-prints"></i> Modelo: {{ $presentacionNombre }}
                                    </small>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="no-products-message">
                        <i class="fas fa-shoe-prints fa-4x text-muted mb-3"></i>
                        <h3 class="text-muted mb-3">No hay calzados disponibles</h3>
                        <p class="text-muted mb-4">
                            @if(request('busqueda'))
                                No se encontraron productos que coincidan con "{{ request('busqueda') }}"
                            @else
                                No hay productos disponibles en este momento
                            @endif
                        </p>
                        @if(request('busqueda'))
                            <a href="{{ route('catalogo.publico') }}" class="btn btn-primary">
                                <i class="fas fa-redo me-1"></i> Ver todos los productos
                            </a>
                        @endif
                    </div>
                </div>
            @endforelse
        </div>

        <!-- Paginación -->
        @if($productos->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $productos->withQueryString()->links() }}
            </div>
        @endif
    </div>

    <!-- Footer Simple -->
    <div class="footer-publico">
        <div class="container">
            <div class="row">
                <div class="col-md-6 mb-4 mb-md-0">
                    <h5 class="mb-3">Calzados Aguilar</h5>
                    <p class="mb-0">
                        Calzado de calidad para toda la familia.<br>
                        Elegancia, estilo y confort en cada paso.
                    </p>
                </div>
                <div class="col-md-6 text-md-end">
                    <h6 class="mb-3">Información de Contacto</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            <i class="fas fa-map-marker-alt me-2"></i> Av. Mercedes Camacho #1064
                        </li>
                        <li class="mb-2">
                            <i class="fas fa-phone me-2"></i> 79531127
                        </li>
                        <li>
                            <i class="fas fa-envelope me-2"></i> info@calzadosaguilar.com
                        </li>
                    </ul>
                </div>
            </div>
            <hr class="my-4 opacity-25">
            <div class="text-center">
                &copy; {{ date('Y') }} Calzados Aguilar. Todos los derechos reservados.
                <br>
                <small>Est. Juan Pablo Ramirez Aguilar – Sistemas Informáticos</small>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Resaltar productos con stock al cargar
        document.addEventListener('DOMContentLoaded', function() {
            // Detectar si es dispositivo móvil
            const isMobile = /Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);

            // Solo aplicar animaciones si no es móvil o si el usuario no prefiere reducción de movimiento
            const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

            if (!isMobile && !prefersReducedMotion) {
                setTimeout(() => {
                    document.querySelectorAll('.in-stock').forEach(stock => {
                        const card = stock.closest('.product-card');
                        if (card) {
                            card.style.animation = 'pulseHighlight 2s ease-in-out';
                            card.style.animationDelay = '0.5s';
                        }
                    });
                }, 500);
            }

            // Auto-enfocar el campo de búsqueda
            const searchInput = document.getElementById('busquedaInput');
            if (searchInput) {
                // Enfocar suavemente después de un breve retraso
                setTimeout(() => {
                    searchInput.focus();
                }, 300);

                // Filtro simple por búsqueda en tiempo real (solo en escritorio)
                if (!isMobile) {
                    searchInput.addEventListener('input', function() {
                        const searchTerm = this.value.toLowerCase();
                        const productItems = document.querySelectorAll('.product-item');

                        productItems.forEach(item => {
                            const productName = item.getAttribute('data-name');
                            const productMarca = item.getAttribute('data-marca');
                            const productCategoria = item.getAttribute('data-categoria');

                            const nameMatch = productName.includes(searchTerm);
                            const marcaMatch = productMarca.includes(searchTerm);
                            const categoriaMatch = productCategoria.includes(searchTerm);

                            const searchMatch = nameMatch || marcaMatch || categoriaMatch;

                            if (searchMatch || searchTerm === '') {
                                item.style.display = 'block';
                                // Añadir animación suave
                                item.style.opacity = '0';
                                setTimeout(() => {
                                    item.style.opacity = '1';
                                    item.style.transition = 'opacity 0.3s ease';
                                }, 10);
                            } else {
                                item.style.opacity = '0';
                                setTimeout(() => {
                                    item.style.display = 'none';
                                }, 300);
                            }
                        });
                    });
                }
            }

            // Optimizar para dispositivos móviles
            if (isMobile) {
                // Reducir animaciones
                document.querySelectorAll('.product-item').forEach(item => {
                    item.style.animationDuration = '0.3s';
                });

                // Mejorar rendimiento táctil
                document.querySelectorAll('button, a, .btn').forEach(element => {
                    element.style.touchAction = 'manipulation';
                });

                // Evitar zoom en inputs en iOS
                document.querySelectorAll('input, select, textarea').forEach(element => {
                    element.style.fontSize = '16px'; // Previene zoom automático en iOS
                });
            }

            // Ajustar altura de tarjetas para igualar en cada fila
            function adjustCardHeights() {
                if (window.innerWidth >= 768) { // Solo en escritorio y tablet
                    const rows = [];
                    const cards = document.querySelectorAll('.product-card');
                    let currentRow = [];
                    let currentTop = null;

                    cards.forEach(card => {
                        const rect = card.getBoundingClientRect();

                        if (currentTop === null) {
                            currentTop = rect.top;
                        }

                        if (Math.abs(rect.top - currentTop) < 10) {
                            currentRow.push(card);
                        } else {
                            if (currentRow.length > 0) {
                                rows.push(currentRow);
                            }
                            currentRow = [card];
                            currentTop = rect.top;
                        }
                    });

                    if (currentRow.length > 0) {
                        rows.push(currentRow);
                    }

                    rows.forEach(row => {
                        let maxHeight = 0;
                        row.forEach(card => {
                            card.style.height = 'auto';
                            maxHeight = Math.max(maxHeight, card.offsetHeight);
                        });

                        row.forEach(card => {
                            card.style.height = maxHeight + 'px';
                        });
                    });
                } else {
                    // En móviles, restaurar altura automática
                    document.querySelectorAll('.product-card').forEach(card => {
                        card.style.height = 'auto';
                    });
                }
            }

            // Ejecutar al cargar y al redimensionar
            adjustCardHeights();
            window.addEventListener('resize', adjustCardHeights);

            // También ajustar después de que se complete la animación de carga
            setTimeout(adjustCardHeights, 800);

            // Mejorar experiencia de búsqueda en móviles
            if (isMobile) {
                const searchForm = document.getElementById('searchForm');
                if (searchForm) {
                    // Evitar que el teclado cubra resultados en iOS
                    searchInput.addEventListener('focus', function() {
                        setTimeout(() => {
                            window.scrollTo(0, 0);
                            document.body.scrollTop = 0;
                        }, 300);
                    });
                }
            }
        });

        // Optimizar para cambios de orientación
        window.addEventListener('orientationchange', function() {
            setTimeout(() => {
                window.dispatchEvent(new Event('resize'));
            }, 300);
        });
    </script>
</body>
</html>
