<?php

use App\Models\Empresa;

$empresa = Empresa::first();
?>
<nav class="sb-topnav navbar navbar-expand navbar-custom-dark">
    <!-- Navbar Brand con ícono para móvil -->
    <a class="navbar-brand ps-3 ps-md-2 pe-2 d-flex align-items-center" href="{{ route('panel') }}">
        <span class="brand-full">{{$empresa->nombre ?? ''}}</span>
        <span class="brand-short">{{ substr($empresa->nombre ?? 'App', 0, 3) }}</span>
        <i class="fas fa-shoe-prints brand-icon d-none"></i>
    </a>

    <!-- Sidebar Toggle-->
    <button class="btn btn-link btn-sm order-1 order-lg-0 me-2 me-md-4" id="sidebarToggle" href="#!">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Contenedor para elementos de la derecha -->
    <div class="d-flex align-items-center ms-auto">
        <!-- Hora local -->
        <div class="me-2 me-md-3 d-none d-md-block">
            <div class="reloj-rectangular px-2 px-md-3 py-1 py-md-2">
                <i class="fas fa-clock me-1 me-md-2"></i>
                <span id="hora-actual" class="fw-bold"></span>
            </div>
        </div>

        <!-- Notificaciones -->
        <div class="nav-item dropdown me-2 me-md-3">
            <a class="nav-link dropdown-toggle position-relative" href="#" role="button" id="notificationsDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-bell"></i>
                @if(Auth::user()->unreadNotifications->count() > 0)
                <span class="badge bg-danger rounded-pill notification-badge">{{ Auth::user()->unreadNotifications->count() }}</span>
                @endif
            </a>
            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="notificationsDropdown" style="min-width: 280px; max-width: 90vw;">
                @forelse (Auth::user()->unreadNotifications->take(5) as $notification)
                <li>
                    <a href="#" class="dropdown-item py-2">
                        <div class="notification-content">
                            {{ $notification->data['message'] ?? 'Nueva notificación' }}
                        </div>
                        <small class="text-muted d-block mt-1">{{ $notification->created_at->diffForHumans() }}</small>
                    </a>
                </li>
                @empty
                <li>
                    <span class="dropdown-item text-muted py-2">Sin notificaciones nuevas</span>
                </li>
                @endforelse
                <li><hr class="dropdown-divider my-1"></li>
                <li><a class="dropdown-item text-center py-2" href="#">Ver todas</a></li>
            </ul>
        </div>

        <!-- Usuario -->
        <ul class="navbar-nav">
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" id="navbarDropdown" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-user fa-fw"></i>
                    <span class="d-inline d-md-none ms-1">Usuario</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                    @can('ver-perfil')
                    <li><a class="dropdown-item py-2" href="{{ route('profile.index') }}">Configuraciones</a></li>
                    @endcan
                    @can('ver-registro-actividad')
                    <li><a class="dropdown-item py-2" href="{{ route('activityLog.index') }}">Registro de actividad</a></li>
                    @endcan
                    <li><hr class="dropdown-divider my-1" /></li>
                    <li><a class="dropdown-item py-2" href="{{ route('logout') }}">Cerrar sesión</a></li>
                </ul>
            </li>
        </ul>
    </div>
</nav>

<style>
.reloj-rectangular {
    background-color: #1F2A38;
    border: 1px solid #A89F91;
    border-radius: 6px;
    color: #D1D5DB;
    font-family: 'Courier New', monospace;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    transition: all 0.3s ease;
    min-width: 100px;
    max-width: 160px;
}

.reloj-rectangular i {
    color: #A89F91;
    font-size: 0.9rem;
}

#hora-actual {
    letter-spacing: 1px;
    font-size: 0.85rem;
}

.notification-badge {
    position: absolute;
    top: -5px;
    right: -5px;
    font-size: 0.65rem;
    min-width: 18px;
    height: 18px;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 0 4px;
}

/* Estilos para el ícono de zapato */
.brand-icon {
    font-size: 1.5rem;
    color: #A89F91;
    margin-right: 0.5rem;
    display: none !important;
}

/* Para pantallas medianas (tablets) */
@media (max-width: 992px) {
    .brand-full {
        display: none !important;
    }

    .brand-short {
        display: inline-block !important;
    }
}

/* Para pantallas pequeñas (móviles) */
@media (max-width: 768px) {
    .reloj-rectangular {
        min-width: 90px;
        padding: 4px 8px;
        font-size: 0.8rem;
    }

    .reloj-rectangular i {
        font-size: 0.8rem;
    }

    #hora-actual {
        font-size: 0.8rem;
        letter-spacing: 0.5px;
    }

    /* Mostrar reloj en móvil de forma más compacta */
    .d-none.d-md-block {
        display: block !important;
    }

    .reloj-rectangular {
        min-width: 75px;
        padding: 3px 6px;
    }
}

/* Para pantallas muy pequeñas (móviles pequeños) */
@media (max-width: 576px) {
    .brand-short {
        display: none !important;
    }

    .brand-icon {
        display: inline-block !important;
    }

    .reloj-rectangular {
        min-width: 70px;
        padding: 2px 5px;
    }

    #hora-actual {
        font-size: 0.75rem;
    }

    .navbar-brand {
        padding-left: 0.5rem !important;
    }

    /* Ajustar márgenes en móvil */
    .me-2.me-md-3 {
        margin-right: 0.5rem !important;
    }
}

@media (min-width: 577px) and (max-width: 991px) {
    .brand-short {
        display: inline-block !important;
    }

    .brand-full {
        display: none !important;
    }

    .brand-icon {
        display: none !important;
    }
}

@media (min-width: 992px) {
    .brand-full {
        display: inline-block !important;
    }

    .brand-short, .brand-icon {
        display: none !important;
    }
}

/* Ajustes para pantallas extra pequeñas */
@media (max-width: 360px) {
    .reloj-rectangular {
        min-width: 65px;
        font-size: 0.7rem;
        padding: 2px 4px;
    }

    #hora-actual {
        font-size: 0.7rem;
    }

    .navbar-nav .nav-link {
        padding: 0.25rem 0.5rem;
    }

    /* Ocultar ícono del reloj en pantallas muy pequeñas */
    .reloj-rectangular i {
        display: none;
    }

    .brand-icon {
        font-size: 1.3rem;
        margin-right: 0.3rem;
    }
}

/* Mejoras para el menú de notificaciones en móvil */
@media (max-width: 576px) {
    .dropdown-menu {
        font-size: 0.9rem;
    }

    .notification-content {
        font-size: 0.85rem;
        line-height: 1.3;
    }
}

/* Asegurar que los elementos se alineen correctamente */
.d-flex.align-items-center.ms-auto {
    display: flex !important;
    align-items: center;
    margin-left: auto !important;
}

.navbar-nav {
    margin-left: 0 !important;
}

/* Asegurar que el navbar-brand se alinee correctamente */
.navbar-brand {
    display: flex;
    align-items: center;
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    function actualizarHora() {
        const ahora = new Date();
        const horas = ahora.getHours().toString().padStart(2, '0');
        const minutos = ahora.getMinutes().toString().padStart(2, '0');
        const segundos = ahora.getSeconds().toString().padStart(2, '0');
        const horaFormateada = `${horas}:${minutos}:${segundos}`;
        document.getElementById('hora-actual').textContent = horaFormateada;
    }

    function ajustarRelojResponsive() {
        const anchoPantalla = window.innerWidth;
        const horaActual = document.getElementById('hora-actual');
        const ahora = new Date();

        if (anchoPantalla < 400) {
            // En móviles pequeños, mostrar solo horas y minutos
            const horas = ahora.getHours().toString().padStart(2, '0');
            const minutos = ahora.getMinutes().toString().padStart(2, '0');
            horaActual.textContent = `${horas}:${minutos}`;
        } else {
            // En pantallas más grandes, mostrar con segundos
            const horas = ahora.getHours().toString().padStart(2, '0');
            const minutos = ahora.getMinutes().toString().padStart(2, '0');
            const segundos = ahora.getSeconds().toString().padStart(2, '0');
            horaActual.textContent = `${horas}:${minutos}:${segundos}`;
        }
    }

    // Inicializar y actualizar
    ajustarRelojResponsive();
    actualizarHora();

    // Actualizar cada segundo
    setInterval(function() {
        ajustarRelojResponsive();
    }, 1000);

    // Ajustar al redimensionar
    window.addEventListener('resize', ajustarRelojResponsive);
});
</script>
