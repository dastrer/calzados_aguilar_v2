@extends('layouts.app')

@section('title', 'Catálogo General de Productos')

@push('css')
<style>
    .product-card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: none;
        border-radius: 15px;
        overflow: hidden;
        background-color: #FFFFFF;
    }
    .product-card:hover {
        transform: translateY(-8px);
        box-shadow: 0 12px 25px rgba(0,0,0,0.15);
    }
    .product-image {
        height: 220px;
        object-fit: cover;
        background: #FAFAF5;
    }
    /* PRECIO DESTACADO EN SUCCESS */
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
    .price-tag:hover {
        background: linear-gradient(135deg, rgba(40, 167, 69, 0.15), rgba(40, 167, 69, 0.08));
        transform: scale(1.02);
        transition: all 0.3s ease;
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
    }
    .catalog-header {
        background: linear-gradient(135deg, #1A2B4C 0%, #2c3e50 100%);
        color: #FFFFFF;
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
        text-shadow: 1px 1px 3px rgba(0,0,0,0.5);
    }
    .filter-section {
        background: #FAFAF5;
        border-radius: 10px;
        padding: 1.5rem;
        border: 1px solid #1A2B4C;
    }
    .btn-primary {
        background: linear-gradient(135deg, #1A2B4C, #2c3e50);
        border: none;
        color: #FFFFFF;
    }
    .btn-primary:hover {
        background: linear-gradient(135deg, #D4AF37, #2c3e50);
        color: #1A2B4C;
    }
    /* BOTÓN IMPRIMIR CON GRADIENTE DORADO - MODIFICADO */
    .btn-print {
        background: linear-gradient(135deg, #D4AF37, #f1c40f);
        border: none;
        color: #1A2B4C;
        font-weight: 600;
        box-shadow: 0 4px 15px rgba(212, 175, 55, 0.3);
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        z-index: 1;
        transition: all 0.3s ease;
    }
    .btn-print::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.4), transparent);
        transition: 0.5s;
        z-index: -1;
    }
    .btn-print:hover {
        background: linear-gradient(135deg, #f1c40f, #D4AF37);
        color: #1A2B4C;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(212, 175, 55, 0.5);
    }
    .btn-print:hover::before {
        left: 100%;
    }
    .btn-print:active {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(212, 175, 55, 0.4);
    }
    .btn-outline-primary {
        border-color: #1A2B4C;
        color: #1A2B4C;
        font-weight: 600;
    }
    .btn-outline-primary:hover {
        background-color: #1A2B4C;
        color: #FFFFFF;
        transform: translateY(-2px);
    }
    .export-buttons {
        display: flex;
        gap: 10px;
        justify-content: center;
        flex-wrap: wrap;
    }
    .product-specs {
        font-size: 0.85rem;
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
    }
    /* BADGE ACTIVO MODIFICADO A DORADO */
    .badge.bg-success {
        background-color: #D4AF37 !important;
        color: #1A2B4C !important;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(212, 175, 55, 0.3);
    }
    .badge.bg-danger {
        background-color: #dc3545 !important;
        font-weight: 600;
        padding: 6px 10px;
        border-radius: 8px;
    }
    .text-muted {
        color: #666 !important;
    }
    .text-primary {
        color: #1A2B4C !important;
        font-weight: 600;
    }
    .form-control {
        border: 1px solid #1A2B4C;
        background-color: #FFFFFF;
        color: #2C2C2C;
    }
    .form-control:focus {
        border-color: #D4AF37;
        box-shadow: 0 0 0 0.3rem rgba(212, 175, 55, 0.25);
    }
    .card-body {
        padding: 1.25rem;
    }
    .product-features {
        background: #FAFAF5;
        padding: 10px;
        border-radius: 8px;
        margin: 10px 0;
        border-left: 3px solid #D4AF37;
    }
    /* STOCK INFO MODIFICADO A DORADO */
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
    }
    .out-of-stock {
        background-color: rgba(220, 53, 69, 0.15);
        color: #dc3545;
        font-weight: 600;
    }
    .product-highlight {
        animation: pulseHighlight 2s ease-in-out;
    }
    @keyframes pulseHighlight {
        0% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0.7); }
        70% { box-shadow: 0 0 0 10px rgba(212, 175, 55, 0); }
        100% { box-shadow: 0 0 0 0 rgba(212, 175, 55, 0); }
    }
    /* BOTÓN VENDER EN SUCCESS - MANTENIDO SIN CAMBIOS */
    .btn-sell {
        background: linear-gradient(135deg, #28a745, #218838);
        border: none;
        color: #FFFFFF;
        font-weight: 700;
        box-shadow: 0 4px 15px rgba(40, 167, 69, 0.4);
        letter-spacing: 0.5px;
        position: relative;
        overflow: hidden;
        z-index: 1;
    }
    .btn-sell::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: 0.5s;
        z-index: -1;
    }
    .btn-sell:hover {
        background: linear-gradient(135deg, #218838, #1e7e34);
        color: #FFFFFF;
        transform: translateY(-3px);
        box-shadow: 0 6px 20px rgba(40, 167, 69, 0.5);
    }
    .btn-sell:hover::before {
        left: 100%;
    }
    .btn-sell:active {
        transform: translateY(-1px);
        box-shadow: 0 3px 10px rgba(40, 167, 69, 0.4);
    }
    @media print {
        .no-print {
            display: none !important;
        }
        .product-card {
            break-inside: avoid;
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
        .catalog-header {
            background: #1A2B4C !important;
            -webkit-print-color-adjust: exact;
        }
        .btn {
            display: none !important;
        }
        .price-tag {
            color: #28a745 !important;
            border-left: 3px solid #28a745 !important;
            background: #f8f9fa !important;
        }
    }
</style>
@endpush

@section('content')
<div class="container-fluid px-4">

    <!-- Header del Catálogo -->
    <div class="catalog-header text-center">
        <h1 class="display-5 fw-bold">Catálogo de Calzados Aguilar</h1>
        <p class="lead">Calzado de calidad para toda la familia</p>

        <!-- Botones de Exportación - SOLO IMPRIMIR CON GRADIENTE DORADO -->
        <div class="mt-3 no-print export-buttons">
            <button class="btn btn-print" onclick="imprimirCatalogo()">
                <i class="fas fa-print"></i> Imprimir Catálogo
            </button>
        </div>
    </div>

    <!-- Búsqueda -->
    <div class="row mb-4 no-print">
        <div class="col-md-12">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Buscar calzados por nombre, marca o categoría..." id="searchInput">
                <button class="btn btn-primary" type="button" onclick="filterProducts()">
                    <i class="fas fa-search"></i> Buscar
                </button>
            </div>
        </div>
    </div>

    <!-- Grid de Productos -->
    <div class="row" id="productsGrid">
        @forelse($productos as $producto)
            @php
                // Información básica del producto
                $marcaNombre = $producto->marca->caracteristica->nombre ?? 'Sin marca';
                $categoriaNombre = $producto->categoria->caracteristica->nombre ?? 'Sin categoría';
                $presentacionNombre = $producto->presentacione->caracteristica->nombre ?? 'Sin modelo';

                // Determinar si está disponible para venta
                $disponibleParaVenta = $producto->estado == 1;

                // Verificar stock si existe la relación
                $tieneStock = false;
                $cantidadStock = 0;
                if (isset($producto->inventario) && $producto->inventario) {
                    $tieneStock = $producto->inventario->cantidad > 0;
                    $cantidadStock = $producto->inventario->cantidad;
                }

                // Determinar si puede venderse
                $puedeVenderse = $disponibleParaVenta && $tieneStock;

                // Agregar clase de resalte para productos con stock
                $productoClass = $puedeVenderse ? 'product-highlight' : '';

                // Obtener imagen del producto
                $imagenProducto = $producto->img_path ? asset($producto->img_path) : asset('assets/img/calzado-default.png');
            @endphp

            <div class="col-xl-3 col-lg-4 col-md-6 mb-4 product-item {{ $productoClass }}"
                 data-name="{{ strtolower($producto->nombre) }}"
                 data-marca="{{ strtolower($marcaNombre) }}"
                 data-categoria="{{ strtolower($categoriaNombre) }}"
                 data-imagen="{{ $imagenProducto }}"
                 data-precio="{{ $producto->precio ? 'Bs. ' . number_format($producto->precio, 2, ',', '.') : 'Precio no definido' }}"
                 data-marca-text="{{ $marcaNombre }}"
                 data-categoria-text="{{ $categoriaNombre }}">

                <div class="card product-card h-100">
                    <!-- Badge de Estado -->
                    <div class="position-absolute top-0 start-0 p-2">
                        @if($producto->estado == 1)
                            <span class="badge bg-success">ACTIVO</span>
                        @else
                            <span class="badge bg-danger">INACTIVO</span>
                        @endif
                    </div>

                    <!-- Imagen del Calzado -->
                    <img src="{{ $imagenProducto }}"
                         class="card-img-top product-image"
                         alt="{{ $producto->nombre }}"
                         onerror="this.src='{{ asset('assets/img/calzado-default.png') }}'">

                    <div class="card-body d-flex flex-column">
                        <!-- Información del Calzado - NOMBRE DESTACADO -->
                        <h5 class="card-title product-title">{{ $producto->nombre }}</h5>

                        <!-- PRECIO DESTACADO EN SUCCESS -->
                        <div class="mb-2">
                            @if($producto->precio)
                                <div class="price-tag">Bs. {{ number_format($producto->precio, 2, ',', '.') }}</div>
                            @else
                                <div class="text-muted">Precio no definido</div>
                            @endif
                        </div>

                        <!-- Información de Stock -->
                        @if($tieneStock)
                            <div class="stock-info in-stock">
                                <i class="fas fa-check-circle"></i> Stock: {{ $cantidadStock }} unidades
                            </div>
                        @else
                            <div class="stock-info out-of-stock">
                                <i class="fas fa-times-circle"></i> Sin stock
                            </div>
                        @endif

                        <!-- Especificaciones del Calzado -->
                        <div class="product-specs mt-2">
                            @if($marcaNombre)
                                <small class="text-muted d-block">
                                    <i class="fas fa-tag"></i> Marca: {{ $marcaNombre }}
                                </small>
                            @endif

                            @if($categoriaNombre)
                                <small class="d-block mt-1">
                                    <span class="categoria-badge">
                                        <i class="fas fa-folder"></i> {{ $categoriaNombre }}
                                    </span>
                                </small>
                            @endif

                            @if($presentacionNombre)
                                <small class="text-primary d-block mt-2">
                                    <i class="fas fa-shoe-prints"></i> Modelo: {{ $presentacionNombre }}
                                </small>
                            @endif
                        </div>

                        <!-- Botones de Acción -->
                        <div class="action-buttons no-print mt-3">
                            <a href="{{ route('productos.edit', $producto->id) }}"
                               class="btn btn-outline-primary btn-sm">
                                <i class="fas fa-edit"></i> Editar
                            </a>

                            @if($puedeVenderse)
                                <a href="{{ route('ventas.create') }}?producto={{ $producto->id }}"
                                   class="btn btn-sell btn-sm">
                                    <i class="fas fa-cart-plus"></i> VENDER
                                </a>
                            @else
                                <button class="btn btn-outline-secondary btn-sm" disabled>
                                    <i class="fas fa-ban"></i> {{ $producto->estado == 0 ? 'INACTIVO' : 'SIN STOCK' }}
                                </button>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @empty
            <div class="col-12 text-center py-5">
                <i class="fas fa-shoe-prints fa-3x text-muted mb-3"></i>
                <h4 class="text-muted">No hay calzados disponibles</h4>
                <p class="text-muted">Agrega calzados para mostrarlos en el catálogo</p>
                <a href="{{ route('productos.create') }}" class="btn btn-primary mt-3">
                    <i class="fas fa-plus"></i> Agregar Calzado
                </a>
            </div>
        @endforelse
    </div>

    <!-- Paginación -->
    @if($productos->hasPages())
        <div class="d-flex justify-content-center mt-4 no-print">
            {{ $productos->links() }}
        </div>
    @endif
</div>
@endsection

@push('js')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const searchInput = document.getElementById('searchInput');
        searchInput.addEventListener('input', filterProducts);

        // Resaltar productos con stock al cargar
        setTimeout(() => {
            document.querySelectorAll('.product-highlight').forEach(card => {
                card.style.animation = 'pulseHighlight 2s ease-in-out';
                card.style.animationDelay = '0.5s';
            });
        }, 500);
    });

    // Función para filtrar productos
    function filterProducts() {
        const searchTerm = document.getElementById('searchInput').value.toLowerCase();
        const productItems = document.querySelectorAll('.product-item');

        productItems.forEach(item => {
            const productName = item.getAttribute('data-name');
            const productMarca = item.getAttribute('data-marca');
            const productCategoria = item.getAttribute('data-categoria');

            // Filtro por búsqueda
            const nameMatch = productName.includes(searchTerm);
            const marcaMatch = productMarca.includes(searchTerm);
            const categoriaMatch = productCategoria.includes(searchTerm);

            const searchMatch = nameMatch || marcaMatch || categoriaMatch;

            if (searchMatch) {
                item.style.display = 'block';
            } else {
                item.style.display = 'none';
            }
        });
    }

    // Función para IMPRIMIR - VERSIÓN CORREGIDA
    // Función para IMPRIMIR - VERSIÓN MODIFICADA CON IMÁGENES MÁS ALTAS
// Función para IMPRIMIR - VERSIÓN OPTIMIZADA CON 4 CARDS POR FILA
function imprimirCatalogo() {
    const boton = event.target;
    const textoOriginal = boton.innerHTML;
    boton.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Preparando impresión...';
    boton.disabled = true;

    const productosVisibles = document.querySelectorAll('.product-item[style*="display: block"]');
    const productosMostrar = productosVisibles.length > 0 ? productosVisibles : document.querySelectorAll('.product-item');

    if (productosMostrar.length === 0) {
        Swal.fire({
            title: 'No hay productos',
            text: 'No hay productos para imprimir.',
            icon: 'warning',
            confirmButtonColor: '#D4AF37'
        });
        boton.innerHTML = textoOriginal;
        boton.disabled = false;
        return;
    }

    // Crear ventana de impresión
    const ventanaImpresion = window.open('', '_blank');

    let contenidoHTML = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Catálogo Calzados Aguilar - ${new Date().toLocaleDateString()}</title>
            <meta charset="UTF-8">
            <style>
                @page {
                    size: Letter portrait;
                    margin: 10mm 12mm 15mm 12mm; /* Margenes optimizados para carta */
                }

                * {
                    margin: 0;
                    padding: 0;
                    box-sizing: border-box;
                }

                body {
                    font-family: 'Arial', sans-serif;
                    font-size: 9px;
                    line-height: 1.2;
                    color: #1A2B4C;
                    background: white;
                    padding: 0;
                    margin: 0;
                    width: 100%;
                }

                /* SOLO EN PRIMERA PÁGINA */
                .first-page-header {
                    text-align: center;
                    background: #1A2B4C;
                    color: white;
                    padding: 5mm 0;
                    margin-bottom: 6mm;
                    border-radius: 0 0 4mm 4mm;
                    box-shadow: 0 1mm 2mm rgba(0,0,0,0.1);
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .first-page-header h1 {
                    font-size: 16px;
                    font-weight: bold;
                    margin: 0 0 2mm 0;
                }

                .first-page-header .subtitle {
                    font-size: 11px;
                    opacity: 0.9;
                    margin-bottom: 2mm;
                }

                .first-page-header .info-line {
                    font-size: 9px;
                    display: flex;
                    justify-content: space-between;
                    padding: 0 5mm;
                    border-top: 0.5px solid rgba(255,255,255,0.2);
                    padding-top: 2mm;
                    margin-top: 2mm;
                }

                /* HEADER PARA PÁGINAS POSTERIORES (más pequeño) */
                .page-header {
                    text-align: center;
                    padding: 2mm 0 3mm 0;
                    margin-bottom: 4mm;
                    border-bottom: 1px solid #1A2B4C;
                    font-size: 9px;
                    color: #1A2B4C;
                }

                .page-header .page-info {
                    display: flex;
                    justify-content: space-between;
                    font-weight: bold;
                }

                /* CONTENIDO PRINCIPAL */
                .page-content {
                    width: 100%;
                    min-height: calc(100vh - 25mm); /* Altura de página */
                }

                /* GRID DE 4 COLUMNAS Y 3 FILAS - COMPACTO */
                .products-grid {
                    display: grid;
                    grid-template-columns: repeat(4, 1fr); /* 4 columnas */
                    grid-auto-rows: 58mm; /* ALTURA FIJA PARA CADA FILA - MÁS COMPACTO */
                    gap: 3mm 2mm; /* Espacio reducido */
                    width: 100%;
                    page-break-inside: avoid;
                }

                /* TARJETA DE PRODUCTO - MÁS COMPACTA */
                .product-card {
                    border: 0.3mm solid #d1d5db;
                    border-radius: 2mm;
                    padding: 2mm;
                    background: white;
                    page-break-inside: avoid;
                    break-inside: avoid;
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    justify-content: space-between;
                    height: 56mm; /* ALTURA FIJA Y MÁS COMPACTA */
                    overflow: hidden;
                    position: relative;
                }

                /* CONTENEDOR DE IMAGEN - MÁS COMPACTO */
                .product-image-container {
                    width: 100%;
                    height: 32mm; /* MÁS BAJO */
                    overflow: hidden;
                    border-radius: 1.5mm;
                    margin-bottom: 1.5mm;
                    background: linear-gradient(135deg, #f8f9fa, #e9ecef);
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    border: 0.2mm solid #e5e7eb;
                }

                /* IMAGEN CON OBJECT-FIT: COVER */
                .product-image {
                    width: 100%;
                    height: 100%;
                    object-fit: cover; /* COVER PARA LLENAR EL ESPACIO */
                    object-position: center;
                    display: block;
                }

                /* INFORMACIÓN DEL PRODUCTO - COMPACTA */
                .product-info {
                    width: 100%;
                    flex-grow: 1;
                    display: flex;
                    flex-direction: column;
                    justify-content: space-between;
                }

                /* TÍTULO DEL PRODUCTO - COMPACTO */
                .product-title {
                    font-size: 8.5px;
                    font-weight: bold;
                    color: #1A2B4C;
                    text-align: center;
                    margin-bottom: 1mm;
                    line-height: 1.1;
                    height: 18px; /* Altura fija para 2 líneas */
                    overflow: hidden;
                    display: -webkit-box;
                    -webkit-line-clamp: 2;
                    -webkit-box-orient: vertical;
                    word-break: break-word;
                }

                /* PRECIO - COMPACTO */
                .product-price {
                    font-size: 10px;
                    font-weight: bold;
                    color: #28a745;
                    text-align: center;
                    margin: 1mm 0;
                    padding: 1mm;
                    background: rgba(40, 167, 69, 0.1);
                    border-radius: 1.5mm;
                    border-left: 1mm solid #28a745;
                    height: 14px;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                }

                /* ESPECIFICACIONES - MÁS COMPACTO */
                .product-specs {
                    font-size: 7.5px;
                    color: #4b5563;
                    text-align: center;
                    height: 20px; /* Altura fija compacta */
                    display: flex;
                    flex-direction: column;
                    justify-content: center;
                    gap: 0.5mm;
                }

                .spec-item {
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    gap: 0.5mm;
                }

                .categoria-badge {
                    background: #1A2B4C;
                    color: white;
                    padding: 0.5mm 1mm;
                    border-radius: 1mm;
                    font-size: 6.5px;
                    font-weight: 600;
                    display: inline-block;
                }

                /* FOOTER DE PÁGINA */
                .page-footer {
                    position: fixed;
                    bottom: 0;
                    left: 12mm;
                    right: 12mm;
                    height: 8mm;
                    background: #f8f9fa;
                    border-top: 0.2mm solid #dee2e6;
                    text-align: center;
                    padding: 1mm;
                    font-size: 7px;
                    color: #6c757d;
                    z-index: 100;
                    -webkit-print-color-adjust: exact;
                    print-color-adjust: exact;
                }

                .footer-info {
                    display: flex;
                    justify-content: space-between;
                    align-items: center;
                    margin-top: 0.5mm;
                }

                /* CONTROLES DE PÁGINA */
                .page {
                    page-break-after: always;
                    break-after: page;
                }

                .page:last-child {
                    page-break-after: auto;
                    break-after: auto;
                }

                /* ESTILOS ESPECÍFICOS PARA IMPRESIÓN */
                @media print {
                    body {
                        margin: 0 !important;
                        padding: 0 !important;
                        width: 100% !important;
                        font-size: 8.5px !important;
                    }

                    @page {
                        size: Letter portrait !important;
                        margin: 10mm 12mm 15mm 12mm !important;
                    }

                    .first-page-header {
                        padding: 4mm 0 !important;
                        margin-bottom: 5mm !important;
                    }

                    .first-page-header h1 {
                        font-size: 15px !important;
                    }

                    .page-header {
                        padding: 1.5mm 0 2mm 0 !important;
                        margin-bottom: 3mm !important;
                    }

                    .products-grid {
                        grid-template-columns: repeat(4, 1fr) !important;
                        grid-auto-rows: 57mm !important; /* ALTURA FIJA EN IMPRESIÓN */
                        gap: 2.5mm 1.5mm !important;
                    }

                    .product-card {
                        height: 55mm !important; /* ALTURA FIJA EN IMPRESIÓN */
                        border: 0.25mm solid #ccc !important;
                        padding: 1.5mm !important;
                    }

                    .product-image-container {
                        height: 31mm !important; /* ALTURA FIJA EN IMPRESIÓN */
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }

                    .product-image {
                        object-fit: cover !important; /* FORZAR COVER EN IMPRESIÓN */
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }

                    .product-title {
                        font-size: 8px !important;
                        height: 16px !important;
                    }

                    .product-price {
                        font-size: 9px !important;
                        padding: 0.8mm !important;
                        height: 12px !important;
                    }

                    .product-specs {
                        font-size: 7px !important;
                        height: 18px !important;
                    }

                    .page-footer {
                        position: fixed !important;
                        bottom: 0 !important;
                        left: 12mm !important;
                        right: 12mm !important;
                        height: 7mm !important;
                        font-size: 6.5px !important;
                        -webkit-print-color-adjust: exact !important;
                        print-color-adjust: exact !important;
                    }

                    /* EVITAR QUE LAS CARDS SE CORTEN */
                    .product-card {
                        page-break-inside: avoid !important;
                        break-inside: avoid !important;
                    }

                    /* EVITAR QUE EL GRID SE CORTE */
                    .products-grid {
                        page-break-inside: avoid !important;
                        break-inside: avoid !important;
                    }
                }

                /* MENSAJE DE CARGA */
                #loading-message {
                    position: fixed;
                    top: 50%;
                    left: 50%;
                    transform: translate(-50%, -50%);
                    background: white;
                    padding: 15px;
                    border-radius: 8px;
                    box-shadow: 0 0 15px rgba(0,0,0,0.1);
                    text-align: center;
                    z-index: 2000;
                    font-size: 11px;
                }

                .loading-text {
                    margin-top: 8px;
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div id="loading-message">
                <i class="fas fa-spinner fa-spin" style="color: #D4AF37;"></i>
                <div class="loading-text">Generando catálogo...</div>
            </div>
    `;

    // Agrupar productos en páginas de 12 (4x3)
    const productosPorPagina = 12; // 4 columnas x 3 filas
    const totalPaginas = Math.ceil(productosMostrar.length / productosPorPagina);

    for (let pagina = 0; pagina < totalPaginas; pagina++) {
        const inicio = pagina * productosPorPagina;
        const fin = inicio + productosPorPagina;
        const productosPagina = Array.from(productosMostrar).slice(inicio, fin);

        contenidoHTML += `
            <div class="page">
                <!-- ENCABEZADO SOLO EN PRIMERA PÁGINA -->
                ${pagina === 0 ? `
                    <div class="first-page-header">
                        <h1>Catálogo de Calzados Aguilar</h1>
                        <div class="subtitle">Calzado de calidad para toda la familia</div>
                        <div class="info-line">
                            <span>Fecha: ${new Date().toLocaleDateString()}</span>
                            <span>Total: ${productosMostrar.length} productos</span>
                            <span>Página 1 de ${totalPaginas}</span>
                        </div>
                    </div>
                ` : `
                    <div class="page-header">
                        <div class="page-info">
                            <span>Calzados Aguilar - Catálogo</span>
                            <span>Página ${pagina + 1} de ${totalPaginas}</span>
                            <span>Continuación...</span>
                        </div>
                    </div>
                `}

                <!-- CONTENIDO DE LA PÁGINA -->
                <div class="page-content">
                    <div class="products-grid">
        `;

        // Siempre 12 productos por página (4x3)
        for (let i = 0; i < productosPorPagina; i++) {
            if (i < productosPagina.length) {
                const item = productosPagina[i];
                const nombre = item.querySelector('.product-title').textContent;
                const precio = item.getAttribute('data-precio');
                const imagen = item.getAttribute('data-imagen');
                const marca = item.getAttribute('data-marca-text');
                const categoria = item.getAttribute('data-categoria-text');

                contenidoHTML += `
                    <div class="product-card">
                        <div class="product-image-container">
                            <img src="${imagen}" class="product-image" alt="${nombre}"
                                 onerror="this.onerror=null; this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMzAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDMwMCAyMDAiIGZpbGw9IiNmNWY1ZjUiPjxyZWN0IHdpZHRoPSIzMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjZmFmYWZhIi8+PHRleHQgeD0iNTAlIiB5PSI1MCUiIGZvbnQtZmFtaWx5PSJBcmlhbCIgZm9udC1zaXplPSIxNHB4IiBmaWxsPSIjNjY2IiB0ZXh0LWFuY2hvcj0ibWlkZGxlIiBkeT0iLjNlbSI+Q2Fsc2FkbzwvdGV4dD48L3N2Zz4=';">
                        </div>
                        <div class="product-info">
                            <div class="product-title">${nombre}</div>
                            <div class="product-price">${precio}</div>
                            <div class="product-specs">
                                ${marca && marca !== 'Sin marca' ?
                                    `<div class="spec-item">${marca}</div>` : ''}
                                ${categoria && categoria !== 'Sin categoría' ?
                                    `<div class="spec-item"><span class="categoria-badge">${categoria}</span></div>` : ''}
                            </div>
                        </div>
                    </div>
                `;
            } else {
                // Slot vacío invisible para mantener estructura grid
                contenidoHTML += `
                    <div class="product-card" style="visibility: hidden; height: 0; padding: 0; border: none;"></div>
                `;
            }
        }

        contenidoHTML += `
                    </div>
                </div>

                <!-- FOOTER DE PÁGINA -->
                <div class="page-footer">
                    <div>Calzados Aguilar • Catálogo Oficial</div>
                    <div class="footer-info">
                        <span>Página ${pagina + 1} de ${totalPaginas}</span>
                        <span>${new Date().getFullYear()} • Todos los derechos reservados</span>
                        <span>Generado: ${new Date().toLocaleDateString()}</span>
                    </div>
                </div>
            </div>
        `;
    }

    contenidoHTML += `
            <script>
                let yaImprimio = false;
                let imagenesCargadas = 0;
                let totalImagenes = 0;

                function inicializarImpresion() {
                    totalImagenes = document.querySelectorAll('img').length;

                    if (totalImagenes === 0) {
                        imprimirDirectamente();
                        return;
                    }

                    cargarImagenes();
                }

                function cargarImagenes() {
                    const imagenes = document.querySelectorAll('img');
                    const loadingMsg = document.getElementById('loading-message');

                    imagenes.forEach(img => {
                        if (img.complete) {
                            imagenesCargadas++;
                        } else {
                            img.onload = function() {
                                imagenesCargadas++;
                                actualizarProgreso();
                            };
                            img.onerror = function() {
                                imagenesCargadas++;
                                actualizarProgreso();
                            };
                        }
                    });

                    // Verificar progreso cada 100ms
                    const intervalo = setInterval(actualizarProgreso, 100);

                    // Timeout de seguridad
                    setTimeout(() => {
                        clearInterval(intervalo);
                        if (imagenesCargadas < totalImagenes) {
                            console.log('Algunas imágenes no se cargaron completamente');
                        }
                        finalizarCarga();
                    }, 3000);

                    function actualizarProgreso() {
                        if (loadingMsg) {
                            const porcentaje = Math.round((imagenesCargadas / totalImagenes) * 100);
                            loadingMsg.innerHTML = \`
                                <i class="fas fa-spinner fa-spin" style="color: #D4AF37;"></i>
                                <div class="loading-text">Cargando: \${porcentaje}%</div>
                            \`;
                        }

                        if (imagenesCargadas >= totalImagenes) {
                            clearInterval(intervalo);
                            finalizarCarga();
                        }
                    }
                }

                function finalizarCarga() {
                    const loadingMsg = document.getElementById('loading-message');
                    if (loadingMsg) {
                        loadingMsg.innerHTML = \`
                            <i class="fas fa-check" style="color: #28a745;"></i>
                            <div class="loading-text" style="color: #28a745;">¡Listo para imprimir!</div>
                        \`;
                    }

                    // Ajustar layout después de cargar imágenes
                    ajustarLayout();

                    // Pequeña pausa antes de imprimir
                    setTimeout(imprimirDirectamente, 500);
                }

                function ajustarLayout() {
                    // Forzar ajuste de imágenes con object-fit: cover
                    const imagenes = document.querySelectorAll('.product-image');
                    imagenes.forEach(img => {
                        img.style.objectFit = 'cover';
                        img.style.objectPosition = 'center';
                    });

                    // Asegurar que todas las cards tengan altura uniforme
                    const cards = document.querySelectorAll('.product-card:not([style*="visibility: hidden"])');
                    if (cards.length > 0) {
                        cards.forEach(card => {
                            card.style.height = '55mm';
                        });
                    }
                }

                function imprimirDirectamente() {
                    if (!yaImprimio) {
                        yaImprimio = true;

                        // Ocultar mensaje de carga
                        const loadingMsg = document.getElementById('loading-message');
                        if (loadingMsg) {
                            loadingMsg.style.display = 'none';
                        }

                        // Configurar evento para cerrar después de imprimir
                        window.addEventListener('afterprint', function() {
                            setTimeout(() => {
                                window.close();
                            }, 300);
                        });

                        // Pequeña pausa y luego imprimir
                        setTimeout(() => {
                            window.print();
                        }, 200);
                    }
                }

                // Iniciar cuando el documento esté listo
                if (document.readyState === 'loading') {
                    document.addEventListener('DOMContentLoaded', inicializarImpresion);
                } else {
                    inicializarImpresion();
                }

                // Prevenir impresión múltiple
                window.addEventListener('beforeprint', function() {
                    if (!yaImprimio) {
                        yaImprimio = true;
                    }
                });
            <\/script>
        </body>
        </html>
    `;

    ventanaImpresion.document.write(contenidoHTML);
    ventanaImpresion.document.close();

    // Restaurar botón después de 3 segundos (tiempo de seguridad)
    setTimeout(() => {
        boton.innerHTML = textoOriginal;
        boton.disabled = false;
    }, 3000);
}

    // Función para mostrar alerta de error
    function mostrarErrorAlert(mensaje) {
        Swal.fire({
            title: 'Error',
            text: mensaje,
            icon: 'error',
            confirmButtonColor: '#D4AF37',
            confirmButtonText: 'Aceptar'
        });
    }
</script>

<!-- Estilos para los botones de SweetAlert -->
<style>
    .btn-swal-confirm {
        background: linear-gradient(135deg, #D4AF37, #f1c40f);
        border: none;
        color: #1A2B4C;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-swal-confirm:hover {
        background: linear-gradient(135deg, #f1c40f, #D4AF37);
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(212, 175, 55, 0.3);
    }

    .btn-swal-cancel {
        background: linear-gradient(135deg, #6c757d, #5a6268);
        border: none;
        color: #FFFFFF;
        font-weight: 600;
        padding: 10px 20px;
        border-radius: 8px;
        transition: all 0.3s ease;
    }

    .btn-swal-cancel:hover {
        background: linear-gradient(135deg, #5a6268, #6c757d);
        transform: translateY(-2px);
    }
</style>
@endpush
