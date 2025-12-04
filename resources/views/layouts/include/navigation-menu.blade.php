<div id="layoutSidenav_nav">
    <nav class="sb-sidenav accordion sb-sidenav-custom" id="sidenavAccordion">
        <div class="sb-sidenav-menu">
            <div class="nav">

                <!-- ENCABEZADO: INICIO -->
                <x-nav.heading>Inicio</x-nav.heading>

                <x-nav.nav-link content='Panel'
                    icon='fas fa-tachometer-alt'
                    :href="route('panel')" />

                <!-- ENCABEZADO: ADQUISICIONES -->
                @if(auth()->user()->canany(['ver-proveedore', 'ver-compra', 'crear-compra']))
                <x-nav.heading>Adquisiciones</x-nav.heading>

                @can('ver-proveedore')
                <x-nav.nav-link content='Proveedores'
                    icon='fa-solid fa-user-group'
                    :href="route('proveedores.index')" />
                @endcan

                @can('ver-compra')
                <x-nav.link-collapsed
                    id="collapseCompras"
                    icon="fa-solid fa-store"
                    content="Compras">
                    @can('ver-compra')
                    <x-nav.link-collapsed-item :href="route('compras.index')" content="Ver" />
                    @endcan

                    @can('crear-compra')
                    <x-nav.link-collapsed-item :href="route('compras.create')" content="Crear" />
                    @endcan
                </x-nav.link-collapsed>
                @endcan
                @endif

                <!-- ENCABEZADO: COMERCIALIZACION -->
                @if(auth()->user()->canany(['ver-producto', 'ver-cliente', 'ver-caja', 'ver-venta', 'crear-venta']))
                <x-nav.heading>Comercialización</x-nav.heading>

                <!-- CATÁLOGO COMO PRIMER ÍTEM EN COMERCIALIZACIÓN -->
                @can('ver-producto')
                <x-nav.nav-link content='Catálogo'
                    icon='fa-solid fa-store'
                    :href="route('catalogo')" />
                @endcan

                @can('ver-cliente')
                <x-nav.nav-link content='Clientes'
                    icon='fa-solid fa-users'
                    :href="route('clientes.index')" />
                @endcan

                @can('ver-caja')
                <x-nav.nav-link content='Cajas'
                    icon='fa-solid fa-money-bill'
                    :href="route('cajas.index')" />
                @endcan

                @can('ver-venta')
                <x-nav.link-collapsed
                    id="collapseVentas"
                    icon="fa-solid fa-cart-shopping"
                    content="Ventas">
                    @can('ver-venta')
                    <x-nav.link-collapsed-item :href="route('ventas.index')" content="Ver" />
                    @endcan

                    @can('crear-venta')
                    <x-nav.link-collapsed-item :href="route('ventas.create')" content="Crear" />
                    @endcan
                </x-nav.link-collapsed>
                @endcan
                @endif

                <!-- ENCABEZADO: PRODUCTOS E INVENTARIO -->
                @if(auth()->user()->canany(['ver-producto', 'ver-categoria', 'ver-presentacione', 'ver-marca', 'ver-inventario', 'ver-kardex']))
                <x-nav.heading>Productos e Inventario</x-nav.heading>

                @canany(['ver-producto', 'ver-categoria', 'ver-presentacione', 'ver-marca'])
                <x-nav.link-collapsed
                    id="collapseProductos"
                    icon="fa-brands fa-shopify"
                    content="Productos">
                    @can('ver-producto')
                    <x-nav.link-collapsed-item :href="route('productos.index')" content="Gestionar Productos" />
                    @endcan

                    @can('ver-categoria')
                    <x-nav.link-collapsed-item :href="route('categorias.index')" content="Categorías" />
                    @endcan

                    @can('ver-presentacione')
                    <x-nav.link-collapsed-item :href="route('presentaciones.index')" content="Modelos" />
                    @endcan

                    @can('ver-marca')
                    <x-nav.link-collapsed-item :href="route('marcas.index')" content="Marcas" />
                    @endcan
                </x-nav.link-collapsed>
                @endcan

                @can('ver-inventario')
                <x-nav.nav-link content='Existencias'
                    icon='fa-solid fa-book'
                    :href="route('inventario.index')" />
                @endcan

                @can('ver-kardex')
                <x-nav.nav-link content='Inventario'
                    icon='fa-solid fa-file'
                    :href="route('kardex.index')" />
                @endcan
                @endif

                <!-- ENCABEZADO: GESTION DE USUARIOS -->
                @if(auth()->user()->canany(['ver-empleado', 'ver-user', 'ver-role']))
                <x-nav.heading>Gestión de Usuarios</x-nav.heading>

                @can('ver-empleado')
                <x-nav.nav-link content='Empleados'
                    icon='fa-solid fa-users'
                    :href="route('empleados.index')" />
                @endcan

                @can('ver-user')
                <x-nav.nav-link content='Usuarios'
                    icon='fa-solid fa-user'
                    :href="route('users.index')" />
                @endcan

                @can('ver-role')
                <x-nav.nav-link content='Roles'
                    icon='fa-solid fa-person-circle-plus'
                    :href="route('roles.index')" />
                @endcan
                @endif

                <!-- ENCABEZADO: HERRAMIENTAS -->
                @can('crear-user')
                <x-nav.heading>Herramientas</x-nav.heading>


                <x-nav.nav-link content='Backups'
                    icon='fas fa-database'
                    :href="route('backups.index')" />


                <x-nav.nav-link content='Mantenimiento'
                    icon='fas fa-wrench'
                    :href="route('mantenimiento.index')" />

                @endcan



            </div>
        </div>
        <div class="sb-sidenav-footer">
            <div class="small">Bienvenido:</div>
            {{ auth()->user()->name }}
        </div>
    </nav>
</div>
