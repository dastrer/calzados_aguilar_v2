<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Presentacione;
use App\Models\Producto;
use App\Services\ActivityLogService;
use App\Services\ProductoService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Throwable;

class ProductoController extends Controller
{
    protected $productoService;

    function __construct(ProductoService $productoService)
    {
        $this->productoService = $productoService;
        $this->middleware('permission:ver-producto|crear-producto|editar-producto|eliminar-producto', ['only' => ['index']]);
        $this->middleware('permission:crear-producto', ['only' => ['create', 'store']]);
        $this->middleware('permission:editar-producto', ['only' => ['edit', 'update']]);
        $this->middleware('permission:eliminar-producto', ['only' => ['destroy']]);
        // El catálogo es público, no requiere permisos
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $productos = Producto::with([
            'categoria.caracteristica',
            'marca.caracteristica',
            'presentacione.caracteristica'
        ])
            ->latest()
            ->get();

        return view('producto.index', compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $marcas = Marca::join('caracteristicas as c', 'marcas.caracteristica_id', '=', 'c.id')
            ->select('marcas.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $presentaciones = Presentacione::join('caracteristicas as c', 'presentaciones.caracteristica_id', '=', 'c.id')
            ->select('presentaciones.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        return view('producto.create', compact('marcas', 'presentaciones', 'categorias'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductoRequest $request): RedirectResponse
    {
        try {
            $this->productoService->crearProducto($request->validated());
            ActivityLogService::log('Creación de producto', 'Productos', $request->validated());
            return redirect()->route('productos.index')->with('success', 'Producto registrado');
        } catch (Throwable $e) {
            Log::error('Error al crear el producto', ['error' => $e->getMessage()]);
            return redirect()->route('productos.index')->with('error', 'Ups, algo falló');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto): View
    {
        $marcas = Marca::join('caracteristicas as c', 'marcas.caracteristica_id', '=', 'c.id')
            ->select('marcas.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $presentaciones = Presentacione::join('caracteristicas as c', 'presentaciones.caracteristica_id', '=', 'c.id')
            ->select('presentaciones.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        $categorias = Categoria::join('caracteristicas as c', 'categorias.caracteristica_id', '=', 'c.id')
            ->select('categorias.id as id', 'c.nombre as nombre')
            ->where('c.estado', 1)
            ->get();

        return view('producto.edit', compact('producto', 'marcas', 'presentaciones', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto): RedirectResponse
    {
        try {
            $this->productoService->editarProducto($request->validated(), $producto);
            ActivityLogService::log('Edición de producto', 'Productos', $request->validated());
            return redirect()->route('productos.index')->with('success', 'Producto editado');
        } catch (Throwable $e) {
            Log::error('Error al editar el producto', ['error' => $e->getMessage()]);
            return redirect()->route('productos.index')->with('error', 'Ups, algo falló');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Implementación de eliminación/restauración si se necesita en el futuro
        // Por ahora se mantiene comentada
    }

    /**
     * Display the product catalog (administrative - requires authentication).
     */
    public function catalogo(): View
    {
        try {
            $productos = Producto::with([
                'categoria.caracteristica',
                'marca.caracteristica',
                'presentacione.caracteristica',
                'inventario'
            ])
            ->where('estado', 1)
            ->orderBy('nombre')
            ->paginate(20);

            if (auth()->check()) {
                ActivityLogService::log('Visualización del catálogo', 'Catálogo', [
                    'total_productos' => $productos->total(),
                    'pagina_actual' => $productos->currentPage()
                ]);
            }

            return view('catalogo', compact('productos'));

        } catch (Throwable $e) {
            Log::error('Error al cargar el catálogo', ['error' => $e->getMessage()]);
            $productos = collect();
            return view('catalogo', compact('productos'))
                ->with('error', 'Error al cargar el catálogo. Por favor, intente nuevamente.');
        }
    }

    /**
     * Mostrar el catálogo público de productos (solo visualización)
     */
    public function catalogoPublico(Request $request)
    {
        try {
            // Query base para productos activos
            $query = Producto::with([
                'marca.caracteristica',
                'categoria.caracteristica',
                'presentacione.caracteristica'
            ])
            ->where('estado', 1);

            // Si es una petición AJAX para el preview (desde welcome)
            if ($request->ajax() || $request->has('limit')) {
                $limit = $request->input('limit', 4);
                $query->limit($limit);

                $productos = $query->orderBy('created_at', 'desc')->get();

                $productosData = $productos->map(function ($producto) {
                    return [
                        'id' => $producto->id,
                        'nombre' => $producto->nombre,
                        'precio' => $producto->precio,
                        'precio_formatted' => $producto->precio ? 'Bs. ' . number_format($producto->precio, 2) : 'Consultar precio',
                        'img_path' => $producto->img_path ? asset($producto->img_path) : asset('assets/img/calzado-default.png'),
                        'descripcion' => $producto->descripcion,
                        'codigo' => $producto->codigo,
                        'estado' => $producto->estado,
                        'marca_nombre' => $producto->marca && $producto->marca->caracteristica
                            ? $producto->marca->caracteristica->nombre
                            : 'Sin marca',
                        'categoria_nombre' => $producto->categoria && $producto->categoria->caracteristica
                            ? $producto->categoria->caracteristica->nombre
                            : 'Sin categoría',
                        'presentacion_nombre' => $producto->presentacione && $producto->presentacione->caracteristica
                            ? $producto->presentacione->caracteristica->nombre
                            : 'Sin presentación'
                    ];
                });

                return response()->json([
                    'success' => true,
                    'productos' => $productosData,
                    'total' => $productosData->count()
                ]);
            }

            // Paginación normal para la vista completa
            $productos = $query->orderBy('created_at', 'desc')->paginate(12);

            return view('catalogo_publico', compact('productos'));

        } catch (\Exception $e) {
            Log::error('Error al cargar catálogo público: ' . $e->getMessage());

            if ($request->ajax() || $request->has('limit')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al cargar el catálogo',
                    'error' => $e->getMessage()
                ]);
            }

            $productos = Producto::where('estado', 1)->paginate(12);
            return view('catalogo_publico', compact('productos'))
                ->with('error', 'Lo sentimos, hubo un problema al cargar el catálogo.');
        }
    }

    /**
     * Búsqueda en el catálogo público
     */
    public function catalogoPublicoBuscar(Request $request)
    {
        $request->validate([
            'busqueda' => 'nullable|string|max:100'
        ]);

        try {
            $query = Producto::with([
                'marca.caracteristica',
                'categoria.caracteristica',
                'presentacione.caracteristica'
            ])
            ->where('estado', 1);

            if ($request->has('busqueda') && !empty($request->busqueda)) {
                $busqueda = $request->busqueda;
                $query->where(function($q) use ($busqueda) {
                    $q->where('nombre', 'LIKE', "%{$busqueda}%")
                      ->orWhere('codigo', 'LIKE', "%{$busqueda}%")
                      ->orWhere('descripcion', 'LIKE', "%{$busqueda}%")
                      ->orWhereHas('marca.caracteristica', function($q) use ($busqueda) {
                          $q->where('nombre', 'LIKE', "%{$busqueda}%");
                      })
                      ->orWhereHas('categoria.caracteristica', function($q) use ($busqueda) {
                          $q->where('nombre', 'LIKE', "%{$busqueda}%");
                      });
                });
            }

            $productos = $query->orderBy('created_at', 'desc')->paginate(12);

            return view('catalogo_publico', [
                'productos' => $productos,
                'busqueda' => $request->busqueda ?? ''
            ]);

        } catch (\Exception $e) {
            Log::error('Error en búsqueda de catálogo público: ' . $e->getMessage());
            return redirect()->route('catalogo.publico')
                ->with('error', 'Error en la búsqueda. Por favor, intenta nuevamente.');
        }
    }
}
