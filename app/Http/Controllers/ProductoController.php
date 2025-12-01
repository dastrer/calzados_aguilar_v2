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
        // La funcionalidad de PDF ha sido eliminada
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
     * Display the product catalog.
     */
    public function catalogo(): View
    {
        try {
            $productos = Producto::with([
                'categoria.caracteristica',
                'marca.caracteristica',
                'presentacione.caracteristica',
                'inventario' // Para mostrar información de stock
            ])
            ->where('estado', 1) // Solo productos activos
            ->orderBy('nombre')
            ->paginate(20);

            // Registrar en el log de actividad solo si el usuario está autenticado
            if (auth()->check()) {
                ActivityLogService::log('Visualización del catálogo', 'Catálogo', [
                    'total_productos' => $productos->total(),
                    'pagina_actual' => $productos->currentPage()
                ]);
            }

            return view('catalogo', compact('productos'));

        } catch (Throwable $e) {
            Log::error('Error al cargar el catálogo', ['error' => $e->getMessage()]);

            // En caso de error, mostrar catálogo vacío con mensaje
            $productos = collect();
            return view('catalogo', compact('productos'))
                ->with('error', 'Error al cargar el catálogo. Por favor, intente nuevamente.');
        }
    }
}