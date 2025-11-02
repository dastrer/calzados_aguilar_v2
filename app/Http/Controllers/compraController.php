<?php

namespace App\Http\Controllers;

use App\Enums\MetodoPagoEnum;
use App\Events\CreateCompraDetalleEvent;
use App\Http\Requests\StoreCompraRequest;
use App\Models\Compra;
use App\Models\Comprobante;
use App\Models\Empresa;
use App\Models\Producto;
use App\Models\Proveedore;
use App\Services\ActivityLogService;
use App\Services\ComprobanteService;
use App\Services\EmpresaService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class compraController extends Controller
{
    protected EmpresaService $empresaService;

    function __construct(EmpresaService $empresaService)
    {
        $this->middleware('permission:ver-compra|crear-compra|mostrar-compra|eliminar-compra', ['only' => ['index']]);
        $this->middleware('permission:crear-compra', ['only' => ['create', 'store']]);
        $this->middleware('permission:mostrar-compra', ['only' => ['show']]);
        //$this->middleware('permission:eliminar-compra', ['only' => ['destroy']]);
        $this->middleware('check-show-compra-user', ['only' => ['show']]);
        $this->empresaService = $empresaService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $query = Compra::with(['comprobante', 'proveedore.persona', 'user', 'productos'])
            ->where('user_id', Auth::id());
        
        // Filtro por fecha
        if (request()->has('fecha') && request('fecha') != '') {
            $query->whereDate('fecha_hora', request('fecha'));
        }
        
        // Filtro por producto
        if (request()->has('producto_id') && request('producto_id') != '') {
            $query->whereHas('productos', function($q) {
                $q->where('productos.id', request('producto_id'));
            });
        }
        
        $compras = $query->latest()->get();
        $productos = Producto::where('estado', 1)->orderBy('nombre')->get();

        return view('compra.index', compact('compras', 'productos'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(ComprobanteService $comprobanteService): View
    {
        $proveedores = Proveedore::whereHas('persona', function ($query) {
            $query->where('estado', 1);
        })->get();
        $comprobantes = $comprobanteService->obtenerComprobantes();
        $productos = Producto::where('estado', 1)->get();
        $optionsMetodoPago = MetodoPagoEnum::cases();
        
        // CORRECCIÓN: La variable $empresa es necesaria para mostrar el símbolo de la moneda en la vista.
        $empresa = $this->empresaService->obtenerEmpresa();

        return view('compra.create', compact(
            'proveedores',
            'comprobantes',
            'productos',
            'optionsMetodoPago',
            'empresa' // Se reintroduce la variable
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCompraRequest $request): RedirectResponse
    {
        DB::beginTransaction();
        try {
            // Obtener datos validados del request
            $validatedData = $request->validated();
            
            // Agregar campos adicionales que no están en el StoreCompraRequest
            $compraData = array_merge($validatedData, [
                'fecha_hora' => $request->fecha_hora . ' ' . now()->format('H:i:s'),
                'user_id' => auth()->id(),
                'numero_comprobante' => $request->numero_comprobante,
            ]);

            // Llenar tabla compras
            $compra = new Compra();
            
            // Manejar archivo si existe
            if (isset($request->file_comprobante)) {
                $compraData['comprobante_path'] = $compra->handleUploadFile($request->file_comprobante);
            }

            $compra = Compra::create($compraData);

            // Llenar tabla compra_producto
            //1.Recuperar los arrays
            $arrayProducto_id = $request->get('arrayidproducto');
            $arrayCantidad = $request->get('arraycantidad');
            $arrayPrecioCompra = $request->get('arraypreciocompra');
            // La fecha de vencimiento (arrayfechavencimiento) se ELIMINA

            //2.Realizar el llenado
            $siseArray = count($arrayProducto_id);
            $cont = 0;
            while ($cont < $siseArray) {
                $compra->productos()->syncWithoutDetaching([
                    $arrayProducto_id[$cont] => [
                        'cantidad' => $arrayCantidad[$cont],
                        'precio_compra' => $arrayPrecioCompra[$cont],
                        // Eliminamos la referencia a 'fecha_vencimiento'
                    ]
                ]);

                //3.Despachar evento de Creacion de registro
                CreateCompraDetalleEvent::dispatch(
                    $compra,
                    $arrayProducto_id[$cont],
                    $arrayCantidad[$cont],
                    $arrayPrecioCompra[$cont],
                    // Eliminamos el argumento $arrayFechaVencimiento[$cont]
                );

                $cont++;
            }

            DB::commit();
            ActivityLogService::log('Creación de compra', 'Compras', $compraData);
            return redirect()->route('compras.index')->with('success', 'compra exitosa');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::error('Error al crear la compra', ['error' => $e->getMessage()]);
            return redirect()->route('compras.index')->with('error', 'Ups, algo falló');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Compra $compra): View
    {
        $empresa = $this->empresaService->obtenerEmpresa();
        // NOTA: La variable $empresa aún se usa aquí, ya que puede ser necesaria para mostrar la moneda
        // o información general de la empresa en el comprobante de compra.
        return view('compra.show', compact('compra', 'empresa'));
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
        /*
          Compra::where('id', $id)
              ->update([
                  'estado' => 0
              ]);

          return redirect()->route('compras.index')->with('success', 'Compra eliminada');*/
    }
}