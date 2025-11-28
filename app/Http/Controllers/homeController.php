<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class homeController extends Controller
{
    public function index(): View
    {
        if (!Auth::check()) {
            return view('welcome');
        }

        // Datos básicos existentes
        $totalVentasPorDia = DB::table('ventas')
            ->selectRaw('DATE(created_at) as fecha, SUM(total) as total')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('fecha', 'asc')
            ->get()->toArray();

        $productosStockBajo = DB::table('productos')
            ->join('inventario', 'productos.id', '=', 'inventario.producto_id')
            ->where('inventario.cantidad', '>', 0)
            ->orderBy('inventario.cantidad', 'asc')
            ->select('productos.nombre', 'inventario.cantidad')
            ->limit(5)
            ->get();

        // NUEVOS DATOS PARA LAS CARDS

        // CARD 1: Productos con bajo stock por modelos (marcas)
        $productosBajoStockModelos = DB::table('productos')
            ->join('inventario', 'productos.id', '=', 'inventario.producto_id')
            ->join('marcas', 'productos.marca_id', '=', 'marcas.id')
            ->join('caracteristicas', 'marcas.caracteristica_id', '=', 'caracteristicas.id')
            ->where('inventario.cantidad', '<', 10)
            ->select(
                'productos.nombre',
                'caracteristicas.nombre as marca',
                'inventario.cantidad as stock'
            )
            ->limit(5)
            ->get();

        // CARD 2: Productos con bajo stock por categoría
        $productosBajoStockCategoria = DB::table('productos')
            ->join('inventario', 'productos.id', '=', 'inventario.producto_id')
            ->join('categorias', 'productos.categoria_id', '=', 'categorias.id')
            ->join('caracteristicas', 'categorias.caracteristica_id', '=', 'caracteristicas.id')
            ->where('inventario.cantidad', '<', 5)
            ->select(
                'caracteristicas.nombre as categoria',
                DB::raw('COUNT(*) as cantidad_productos')
            )
            ->groupBy('caracteristicas.nombre')
            ->get();

        // CARD 3: Productos con alta demanda por fecha (últimos 30 días)
        $productosAltaDemanda = DB::table('producto_venta')
            ->join('ventas', 'producto_venta.venta_id', '=', 'ventas.id')
            ->join('productos', 'producto_venta.producto_id', '=', 'productos.id')
            ->where('ventas.created_at', '>=', Carbon::now()->subDays(30))
            ->select(
                'productos.nombre',
                DB::raw('SUM(producto_venta.cantidad) as total_vendido')
            )
            ->groupBy('productos.id', 'productos.nombre')
            ->orderBy('total_vendido', 'DESC')
            ->limit(5)
            ->get();

        // CARD 4: Compras realizadas en el mes
        $comprasMes = DB::table('compras')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->count();

        // CARD 5: Ventas realizadas en el día
        $ventasHoy = DB::table('ventas')
            ->whereDate('created_at', Carbon::today())
            ->count();

        // CARD 6: Alerta de productos con stock bajo (total)
        $totalProductosStockBajo = DB::table('productos')
            ->join('inventario', 'productos.id', '=', 'inventario.producto_id')
            ->where('inventario.cantidad', '<', 5)
            ->count();

        // GRÁFICOS

        // GRÁFICO 1: Productos con stock crítico
        $stockCritico = DB::table('productos')
            ->join('inventario', 'productos.id', '=', 'inventario.producto_id')
            ->where('inventario.cantidad', '<', 5)
            ->select('productos.nombre', 'inventario.cantidad as stock')
            ->orderBy('inventario.cantidad', 'ASC')
            ->limit(10)
            ->get();

        // GRÁFICO 2: Ventas por período (para gráfico dinámico)
        $ventasPorPeriodo = [
            'dia' => $this->obtenerVentasPorPeriodo('dia', 7),
            'mes' => $this->obtenerVentasPorPeriodo('mes', 12),
            'anio' => $this->obtenerVentasPorPeriodo('anio', 5),
        ];

        // GRÁFICO 3: Compras por período
        $comprasPorPeriodo = [
            'dia' => $this->obtenerComprasPorPeriodo('dia', 7),
            'mes' => $this->obtenerComprasPorPeriodo('mes', 12),
            'anio' => $this->obtenerComprasPorPeriodo('anio', 5),
        ];

        // GRÁFICO 4: Movimiento de caja - usando la tabla movimientos
        try {
            $movimientoCaja = DB::table('movimientos')
                ->where('created_at', '>=', Carbon::now()->subDays(30))
                ->select(
                    DB::raw('DATE(created_at) as fecha'),
                    DB::raw('SUM(monto) as total_movimiento'),
                    DB::raw('COUNT(*) as cantidad_movimientos')
                )
                ->groupBy(DB::raw('DATE(created_at)'))
                ->orderBy('fecha', 'ASC')
                ->get();
        } catch (\Exception $e) {
            // Si hay algún error, crear datos vacíos
            $movimientoCaja = collect();
        }

        // MINITABLAS

        // MINITABLA 1: Últimos productos vendidos
        $ultimosProductosVendidos = DB::table('producto_venta')
            ->join('ventas', 'producto_venta.venta_id', '=', 'ventas.id')
            ->join('productos', 'producto_venta.producto_id', '=', 'productos.id')
            ->select(
                'productos.nombre',
                'producto_venta.cantidad',
                'ventas.created_at',
                'producto_venta.precio_venta'
            )
            ->orderBy('ventas.created_at', 'DESC')
            ->limit(5)
            ->get();

        // MINITABLA 2: Productos populares
        $productosPopulares = DB::table('producto_venta')
            ->join('productos', 'producto_venta.producto_id', '=', 'productos.id')
            ->select(
                'productos.nombre',
                DB::raw('SUM(producto_venta.cantidad) as total_vendido')
            )
            ->groupBy('productos.id', 'productos.nombre')
            ->orderBy('total_vendido', 'DESC')
            ->limit(5)
            ->get();

        // DATOS ADICIONALES PARA EL PANEL

        // Total de ingresos del día
        $ingresosHoy = DB::table('ventas')
            ->whereDate('created_at', Carbon::today())
            ->sum('total');

        // Total de ingresos del mes
        $ingresosMes = DB::table('ventas')
            ->whereMonth('created_at', Carbon::now()->month)
            ->whereYear('created_at', Carbon::now()->year)
            ->sum('total');

        // Productos más vendidos del mes
        $productosMasVendidosMes = DB::table('producto_venta')
            ->join('ventas', 'producto_venta.venta_id', '=', 'ventas.id')
            ->join('productos', 'producto_venta.producto_id', '=', 'productos.id')
            ->whereMonth('ventas.created_at', Carbon::now()->month)
            ->whereYear('ventas.created_at', Carbon::now()->year)
            ->select(
                'productos.nombre',
                DB::raw('SUM(producto_venta.cantidad) as total_vendido'),
                DB::raw('SUM(producto_venta.cantidad * producto_venta.precio_venta) as ingreso_total')
            )
            ->groupBy('productos.id', 'productos.nombre')
            ->orderBy('total_vendido', 'DESC')
            ->limit(5)
            ->get();

        // Estado de la caja actual
        $cajaActual = DB::table('cajas')
            ->where('estado', 1)
            ->first();

        return view('panel.index', compact(
            'totalVentasPorDia',
            'productosStockBajo',
            'productosBajoStockModelos',
            'productosBajoStockCategoria',
            'productosAltaDemanda',
            'comprasMes',
            'ventasHoy',
            'totalProductosStockBajo',
            'stockCritico',
            'ventasPorPeriodo',
            'comprasPorPeriodo',
            'movimientoCaja',
            'ultimosProductosVendidos',
            'productosPopulares',
            'ingresosHoy',
            'ingresosMes',
            'productosMasVendidosMes',
            'cajaActual'
        ));
    }

    /**
     * Obtener ventas por período para gráficos dinámicos
     */
    private function obtenerVentasPorPeriodo($periodo, $cantidad)
    {
        $query = DB::table('ventas');

        switch ($periodo) {
            case 'dia':
                $query->where('created_at', '>=', Carbon::now()->subDays($cantidad - 1))
                      ->select(
                          DB::raw('DATE(created_at) as fecha'),
                          DB::raw('SUM(total) as monto_total'),
                          DB::raw('COUNT(*) as cantidad_ventas')
                      )
                      ->groupBy(DB::raw('DATE(created_at)'))
                      ->orderBy('fecha', 'ASC');
                break;

            case 'mes':
                $query->where('created_at', '>=', Carbon::now()->subMonths($cantidad - 1))
                      ->select(
                          DB::raw('YEAR(created_at) as año'),
                          DB::raw('MONTH(created_at) as mes'),
                          DB::raw('SUM(total) as monto_total'),
                          DB::raw('COUNT(*) as cantidad_ventas')
                      )
                      ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                      ->orderBy('año', 'ASC')
                      ->orderBy('mes', 'ASC');
                break;

            case 'anio':
                $query->where('created_at', '>=', Carbon::now()->subYears($cantidad - 1))
                      ->select(
                          DB::raw('YEAR(created_at) as año'),
                          DB::raw('SUM(total) as monto_total'),
                          DB::raw('COUNT(*) as cantidad_ventas')
                      )
                      ->groupBy(DB::raw('YEAR(created_at)'))
                      ->orderBy('año', 'ASC');
                break;
        }

        $resultados = $query->get();

        // Formatear etiquetas
        return $resultados->map(function($item) use ($periodo) {
            if ($periodo === 'dia') {
                $item->etiqueta = Carbon::parse($item->fecha)->format('d/m');
            } elseif ($periodo === 'mes') {
                $meses = [
                    1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun',
                    7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
                ];
                $item->etiqueta = $meses[$item->mes] . '/' . substr($item->año, -2);
            } elseif ($periodo === 'anio') {
                $item->etiqueta = $item->año;
            }

            return $item;
        });
    }

    /**
     * Obtener compras por período para gráficos dinámicos
     */
    private function obtenerComprasPorPeriodo($periodo, $cantidad)
    {
        $query = DB::table('compras');

        switch ($periodo) {
            case 'dia':
                $query->where('created_at', '>=', Carbon::now()->subDays($cantidad - 1))
                      ->select(
                          DB::raw('DATE(created_at) as fecha'),
                          DB::raw('SUM(total) as monto_total'),
                          DB::raw('COUNT(*) as cantidad_compras')
                      )
                      ->groupBy(DB::raw('DATE(created_at)'))
                      ->orderBy('fecha', 'ASC');
                break;

            case 'mes':
                $query->where('created_at', '>=', Carbon::now()->subMonths($cantidad - 1))
                      ->select(
                          DB::raw('YEAR(created_at) as año'),
                          DB::raw('MONTH(created_at) as mes'),
                          DB::raw('SUM(total) as monto_total'),
                          DB::raw('COUNT(*) as cantidad_compras')
                      )
                      ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
                      ->orderBy('año', 'ASC')
                      ->orderBy('mes', 'ASC');
                break;

            case 'anio':
                $query->where('created_at', '>=', Carbon::now()->subYears($cantidad - 1))
                      ->select(
                          DB::raw('YEAR(created_at) as año'),
                          DB::raw('SUM(total) as monto_total'),
                          DB::raw('COUNT(*) as cantidad_compras')
                      )
                      ->groupBy(DB::raw('YEAR(created_at)'))
                      ->orderBy('año', 'ASC');
                break;
        }

        $resultados = $query->get();

        // Formatear etiquetas
        return $resultados->map(function($item) use ($periodo) {
            if ($periodo === 'dia') {
                $item->etiqueta = Carbon::parse($item->fecha)->format('d/m');
            } elseif ($periodo === 'mes') {
                $meses = [
                    1 => 'Ene', 2 => 'Feb', 3 => 'Mar', 4 => 'Abr', 5 => 'May', 6 => 'Jun',
                    7 => 'Jul', 8 => 'Ago', 9 => 'Sep', 10 => 'Oct', 11 => 'Nov', 12 => 'Dic'
                ];
                $item->etiqueta = $meses[$item->mes] . '/' . substr($item->año, -2);
            } elseif ($periodo === 'anio') {
                $item->etiqueta = $item->año;
            }

            return $item;
        });
    }
}
