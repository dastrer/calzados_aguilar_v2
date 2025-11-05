<?php

namespace App\Http\Controllers;

use App\Enums\MetodoPagoEnum;
use App\Events\CreateVentaDetalleEvent;
use App\Events\CreateVentaEvent;
use App\Http\Requests\StoreVentaRequest;
use App\Models\Cliente;
use App\Models\Producto;
use App\Models\Venta;
use App\Services\ActivityLogService;
use App\Services\ComprobanteService;
use App\Services\EmpresaService;
use Carbon\Carbon;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ventaController extends Controller
{
    protected EmpresaService $empresaService;

    function __construct(EmpresaService $empresaService)
    {
        $this->middleware('permission:ver-venta|crear-venta|mostrar-venta|eliminar-venta', ['only' => ['index']]);
        $this->middleware('permission:crear-venta', ['only' => ['create', 'store']]);
        $this->middleware('permission:mostrar-venta', ['only' => ['show']]);
        //$this->middleware('permission:eliminar-venta', ['only' => ['destroy']]);
        $this->middleware('check-caja-aperturada-user', ['only' => ['create', 'store']]);
        $this->middleware('check-show-venta-user', ['only' => ['show']]);
        $this->empresaService = $empresaService;
    }
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $ventas = Venta::with([
            'comprobante',
            'cliente.persona',
            'user',
            'productos' => function($query) {
                $query->with('marca.caracteristica');
            }
        ])
        ->when($request->fecha, function($query, $fecha) {
            // Usar whereDate con fecha_hora
            return $query->whereDate('fecha_hora', $fecha);
        })
        ->when($request->producto_id, function($query, $productoId) {
            return $query->whereHas('productos', function($q) use ($productoId) {
                $q->where('productos.id', $productoId);
            });
        })
        ->where('user_id', Auth::id())
        ->latest()
        ->get();

        // MODIFICACIÓN: Cargar productos con el mismo formato
        $productos = Producto::with(['presentacione.caracteristica'])
            ->where('estado', 1)
            ->get()->map(function($producto) {
                $partes = explode(' - ', $producto->nombreCompleto);
                $nombre = '';

                foreach ($partes as $parte) {
                    if (!str_contains($parte, 'Código:') && !str_contains($parte, 'Presentación:')) {
                        $nombre = $parte;
                        break;
                    }
                }

                $producto->nombre_filtro = $nombre . ' - ' . ($producto->presentacione->caracteristica->nombre ?? '');
                return $producto;
            });

        // NUEVO: Obtener estadísticas para mostrar en el index
        $estadisticas = $this->obtenerEstadisticas($request);

        return view('venta.index', compact('ventas', 'productos', 'estadisticas'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ComprobanteService $comprobanteService): View
    {
        // MODIFICACIÓN: Cargar productos con relaciones y procesar el formato
        $productos = Producto::with([
            'presentacione.caracteristica',
            'marca.caracteristica',
            'categoria.caracteristica',
            'inventario' // Para obtener la cantidad en stock
        ])
        ->where('estado', 1)
        ->whereHas('inventario', function($query) {
            $query->where('cantidad', '>', 0);
        })
        ->get()->map(function($producto) {
            // Procesar el nombreCompleto para extraer el nombre simple
            $partes = explode(' - ', $producto->nombreCompleto);
            $nombre = '';
            $codigoPresentacion = '';

            foreach ($partes as $parte) {
                if (str_contains($parte, 'Presentación:')) {
                    $codigoPresentacion = str_replace('Presentación: ', '', $parte);
                } elseif (!str_contains($parte, 'Código:')) {
                    $nombre = $parte;
                }
            }

            // Crear propiedades adicionales para la vista
            $producto->nombre_simple = $nombre;
            $producto->modelo = $producto->presentacione->caracteristica->nombre ?? $codigoPresentacion;
            $producto->texto_select = $nombre . ' - ' . $producto->modelo;
            $producto->cantidad_stock = $producto->inventario->cantidad ?? 0;
            $producto->sigla = $producto->presentacione->caracteristica->nombre ?? '';

            return $producto;
        });

        $clientes = Cliente::whereHas('persona', function ($query) {
            $query->where('estado', 1);
        })->get();

        $comprobantes = $comprobanteService->obtenerComprobantes();
        $optionsMetodoPago = MetodoPagoEnum::cases();
        $empresa = $this->empresaService->obtenerEmpresa();

        return view('venta.create', compact(
            'productos',
            'clientes',
            'comprobantes',
            'optionsMetodoPago',
            'empresa'
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVentaRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            //Llenar mi tabla venta
            $venta = Venta::create($request->validated());

            //Llenar mi tabla venta_producto
            //1. Recuperar los arrays
            $arrayProducto_id = $request->get('arrayidproducto');
            $arrayCantidad = $request->get('arraycantidad');
            $arrayPrecioVenta = $request->get('arrayprecioventa');

            //2.Realizar el llenado
            $siseArray = count($arrayProducto_id);
            $cont = 0;

            while ($cont < $siseArray) {
                $venta->productos()->syncWithoutDetaching([
                    $arrayProducto_id[$cont] => [
                        'cantidad' => $arrayCantidad[$cont],
                        'precio_venta' => $arrayPrecioVenta[$cont],
                    ]
                ]);

                //Despachar evento
                CreateVentaDetalleEvent::dispatch(
                    $venta,
                    $arrayProducto_id[$cont],
                    $arrayCantidad[$cont],
                    $arrayPrecioVenta[$cont]
                );

                $cont++;
            }

            //Despachar evento
            CreateVentaEvent::dispatch($venta);

            DB::commit();
            ActivityLogService::log('Creación de una venta', 'Ventas', $request->validated());
            return redirect()->route('movimientos.index', ['caja_id' => $venta->caja_id])
                ->with('success', 'Venta registrada');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error al crear la venta', ['error' => $e->getMessage()]);
            return redirect()->route('ventas.index')->with('error', 'Ups, algo falló');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta): View
    {
        $empresa = $this->empresaService->obtenerEmpresa();
        return view('venta.show', compact('venta', 'empresa'));
    }

    /**
     * Obtener estadísticas de ventas y productos
     */
    private function obtenerEstadisticas(Request $request)
    {
        $hoy = Carbon::today();
        $inicioSemana = Carbon::now()->startOfWeek();
        $inicioMes = Carbon::now()->startOfMonth();
        $inicioAnio = Carbon::now()->startOfYear();

        // Aplicar mismos filtros que en la consulta principal
        $queryBase = Venta::where('user_id', Auth::id())
            ->when($request->fecha, function($query, $fecha) {
                return $query->whereDate('fecha_hora', $fecha);
            })
            ->when($request->producto_id, function($query, $productoId) {
                return $query->whereHas('productos', function($q) use ($productoId) {
                    $q->where('productos.id', $productoId);
                });
            });

        return [
            // Ventas por período
            'ventas_hoy' => (clone $queryBase)->whereDate('fecha_hora', $hoy)->sum('total'),
            'ventas_semana' => (clone $queryBase)->whereBetween('fecha_hora', [$inicioSemana, Carbon::now()])->sum('total'),
            'ventas_mes' => (clone $queryBase)->whereBetween('fecha_hora', [$inicioMes, Carbon::now()])->sum('total'),
            'ventas_anio' => (clone $queryBase)->whereBetween('fecha_hora', [$inicioAnio, Carbon::now()])->sum('total'),

            // Cantidad de ventas
            'cantidad_ventas_hoy' => (clone $queryBase)->whereDate('fecha_hora', $hoy)->count(),
            'cantidad_ventas_semana' => (clone $queryBase)->whereBetween('fecha_hora', [$inicioSemana, Carbon::now()])->count(),
            'cantidad_ventas_mes' => (clone $queryBase)->whereBetween('fecha_hora', [$inicioMes, Carbon::now()])->count(),

            // Métodos de pago
            'efectivo_hoy' => (clone $queryBase)->whereDate('fecha_hora', $hoy)->where('metodo_pago', 'EFECTIVO')->sum('total'),
            'qr_hoy' => (clone $queryBase)->whereDate('fecha_hora', $hoy)->where('metodo_pago', 'QR')->sum('total'),

            // Productos más vendidos (solo si no hay filtro de producto específico)
            'productos_mas_vendidos' => !$request->producto_id ? $this->productosMasVendidos($queryBase) : [],

            // Ventas por día de la semana actual
            'ventas_ultima_semana' => $this->ventasUltimosDias(7, $request),

            // NUEVO: Datos para gráficos dinámicos
            'ventas_por_dia' => $this->ventasPorPeriodo('dia', 7, $request),
            'ventas_por_semana' => $this->ventasPorPeriodo('semana', 4, $request),
            'ventas_por_mes' => $this->ventasPorPeriodo('mes', 12, $request),
            'ventas_por_anio' => $this->ventasPorPeriodo('anio', 5, $request),
        ];
    }

    /**
     * Obtener productos más vendidos
     */
    private function productosMasVendidos($queryBase)
    {
        return DB::table('producto_venta')
            ->join('ventas', 'producto_venta.venta_id', '=', 'ventas.id')
            ->join('productos', 'producto_venta.producto_id', '=', 'productos.id')
            ->whereIn('ventas.id', $queryBase->select('id'))
            ->select(
                'productos.nombre',
                'productos.codigo',
                DB::raw('SUM(producto_venta.cantidad) as total_vendido'),
                DB::raw('SUM(producto_venta.cantidad * producto_venta.precio_venta) as monto_total')
            )
            ->groupBy('productos.id', 'productos.nombre', 'productos.codigo')
            ->orderBy('total_vendido', 'DESC')
            ->limit(5)
            ->get();
    }

    /**
     * Obtener ventas de los últimos días para gráficos
     */
    private function ventasUltimosDias($dias, Request $request)
    {
        $query = Venta::where('fecha_hora', '>=', Carbon::now()->subDays($dias))
            ->where('user_id', Auth::id())
            ->when($request->fecha, function($query, $fecha) {
                return $query->whereDate('fecha_hora', $fecha);
            })
            ->when($request->producto_id, function($query, $productoId) {
                return $query->whereHas('productos', function($q) use ($productoId) {
                    $q->where('productos.id', $productoId);
                });
            })
            ->select(
                DB::raw('DATE(fecha_hora) as fecha'),
                DB::raw('SUM(total) as monto_total'),
                DB::raw('COUNT(*) as cantidad_ventas')
            )
            ->groupBy(DB::raw('DATE(fecha_hora)'))
            ->orderBy('fecha', 'ASC');

        return $query->get();
    }

    /**
     * NUEVO: Obtener ventas por período para gráficos dinámicos
     */
    /**
 * NUEVO: Obtener ventas por período para gráficos dinámicos
 */
private function ventasPorPeriodo($periodo, $cantidad, Request $request)
{
    $query = Venta::where('user_id', Auth::id())
        ->when($request->fecha, function($query, $fecha) {
            return $query->whereDate('fecha_hora', $fecha);
        })
        ->when($request->producto_id, function($query, $productoId) {
            return $query->whereHas('productos', function($q) use ($productoId) {
                $q->where('productos.id', $productoId);
            });
        });

    switch ($periodo) {
        case 'dia':
            // Para "Hoy" - ventas por hora del día actual
            if ($cantidad === 1) {
                $query->whereDate('fecha_hora', Carbon::today())
                      ->select(
                          DB::raw('HOUR(fecha_hora) as hora'),
                          DB::raw('SUM(total) as monto_total'),
                          DB::raw('COUNT(*) as cantidad_ventas')
                      )
                      ->groupBy(DB::raw('HOUR(fecha_hora)'))
                      ->orderBy('hora', 'ASC');
            } else {
                // Últimos X días
                $query->where('fecha_hora', '>=', Carbon::now()->subDays($cantidad - 1))
                      ->select(
                          DB::raw('DATE(fecha_hora) as fecha'),
                          DB::raw('SUM(total) as monto_total'),
                          DB::raw('COUNT(*) as cantidad_ventas')
                      )
                      ->groupBy(DB::raw('DATE(fecha_hora)'))
                      ->orderBy('fecha', 'ASC');
            }
            break;

        case 'semana':
            $query->where('fecha_hora', '>=', Carbon::now()->subWeeks($cantidad - 1))
                  ->select(
                      DB::raw('YEAR(fecha_hora) as año'),
                      DB::raw('WEEK(fecha_hora) as semana'),
                      DB::raw('SUM(total) as monto_total'),
                      DB::raw('COUNT(*) as cantidad_ventas')
                  )
                  ->groupBy(DB::raw('YEAR(fecha_hora)'), DB::raw('WEEK(fecha_hora)'))
                  ->orderBy('año', 'ASC')
                  ->orderBy('semana', 'ASC');
            break;

        case 'mes':
            $query->where('fecha_hora', '>=', Carbon::now()->subMonths($cantidad - 1))
                  ->select(
                      DB::raw('YEAR(fecha_hora) as año'),
                      DB::raw('MONTH(fecha_hora) as mes'),
                      DB::raw('SUM(total) as monto_total'),
                      DB::raw('COUNT(*) as cantidad_ventas')
                  )
                  ->groupBy(DB::raw('YEAR(fecha_hora)'), DB::raw('MONTH(fecha_hora)'))
                  ->orderBy('año', 'ASC')
                  ->orderBy('mes', 'ASC');
            break;

        case 'anio':
            $query->where('fecha_hora', '>=', Carbon::now()->subYears($cantidad - 1))
                  ->select(
                      DB::raw('YEAR(fecha_hora) as año'),
                      DB::raw('SUM(total) as monto_total'),
                      DB::raw('COUNT(*) as cantidad_ventas')
                  )
                  ->groupBy(DB::raw('YEAR(fecha_hora)'))
                  ->orderBy('año', 'ASC');
            break;
    }

    $resultados = $query->get();

    // Formatear los resultados para que sean consistentes
    return $resultados->map(function($item) use ($periodo) {
        if ($periodo === 'dia' && isset($item->hora)) {
            // Para ventas por hora del día - corregido
            $item->etiqueta = sprintf('%02d:00', $item->hora);
        } elseif ($periodo === 'dia' && isset($item->fecha)) {
            // Para ventas por día
            $item->etiqueta = Carbon::parse($item->fecha)->format('d/m');
        } elseif ($periodo === 'semana') {
            $item->etiqueta = 'Sem ' . $item->semana;
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
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        /* Venta::where('id', $id)
            ->update([
                'estado' => 0
            ]);

        return redirect()->route('ventas.index')->with('success', 'Venta eliminada');*/
    }
}
