<?php

namespace App\Http\Controllers;

use App\Enums\TipoTransaccionEnum;
use App\Http\Requests\StoreInventarioRequest;
use App\Models\Inventario;
use App\Models\Kardex;
use App\Models\Producto;
use App\Models\Ubicacione;
use App\Services\ActivityLogService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class InventarioControlller extends Controller
{
    function __construct()
    {
        $this->middleware('check_producto_inicializado', ['only' => ['create', 'store']]);
    }
    
        /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $inventario = Inventario::with([
            'producto.presentacione.caracteristica', 
            'ubicacione'
        ])->latest()->get();
        
        $ubicaciones = Ubicacione::all(); // Agregado para el modal de reubicación
        return view('inventario.index', compact('inventario', 'ubicaciones'));
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $producto = Producto::findOrfail($request->producto_id);
        $ubicaciones = Ubicacione::all();
        return view('inventario.create', compact('producto', 'ubicaciones'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreInventarioRequest $request, Kardex $kardex): RedirectResponse
    {
        DB::beginTransaction();
        try {
            $kardex->crearRegistro($request->validated(), TipoTransaccionEnum::Apertura);
            Inventario::create($request->validated());
            DB::commit();
            ActivityLogService::log('Inicialiación de producto', 'Productos', $request->validated());
            return redirect()->route('productos.index')->with('success', 'Producto inicializado');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error al inicializar el producto', ['error' => $e->getMessage()]);
            return redirect()->route('productos.index')->with('error', 'Ups, algo falló');
        }
    }

    /**
     * Reubicar un producto en el inventario
     */
    public function reubicar(Request $request, Inventario $inventario): RedirectResponse
    {
        $request->validate([
            'ubicacione_id' => 'required|exists:ubicaciones,id'
        ]);

        // Verificar que la nueva ubicación sea diferente a la actual
        if ($request->ubicacione_id == $inventario->ubicacione_id) {
            return redirect()->back()->with('error', 'El producto ya se encuentra en esta ubicación');
        }

        DB::beginTransaction();
        try {
            // Guardar la ubicación anterior para el log
            $ubicacionAnterior = $inventario->ubicacione->nombre;
            
            // Actualizar la ubicación
            $inventario->update([
                'ubicacione_id' => $request->ubicacione_id
            ]);

            // Obtener el nombre de la nueva ubicación
            $nuevaUbicacion = Ubicacione::find($request->ubicacione_id)->nombre;

            // Registrar en el log de actividad
            ActivityLogService::log(
                'Reubicación de producto', 
                'Inventario', 
                [
                    'producto_id' => $inventario->producto_id,
                    'ubicacion_anterior' => $ubicacionAnterior,
                    'ubicacion_nueva' => $nuevaUbicacion,
                    'cantidad' => $inventario->cantidad
                ]
            );

            DB::commit();
            
            return redirect()
                ->back()
                ->with('success', "Producto reubicado correctamente de '$ubicacionAnterior' a '$nuevaUbicacion'");
                
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error al reubicar el producto', [
                'error' => $e->getMessage(),
                'inventario_id' => $inventario->id,
                'nueva_ubicacion' => $request->ubicacione_id
            ]);
            return redirect()->back()->with('error', 'Error al reubicar el producto');
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
        //
    }
}