<?php

namespace App\Http\Controllers;

use App\Models\Kardex;
use App\Models\Producto;
use App\Models\Presentacione;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class KardexController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $producto_id = $request->get('producto_id');
        $presentacione_id = $request->get('presentacione_id');
        $tipo_transaccion = $request->get('tipo_transaccion');
        
        // Cargar productos con presentacione y caracteristica
        $productos = Producto::with(['presentacione.caracteristica'])->latest()->get();
        
        // Cargar presentaciones con caracteristica para el nombre
        $presentaciones = Presentacione::with('caracteristica')->latest()->get();

        // Consulta base con relaciones completas
        $query = Kardex::with(['producto.presentacione.caracteristica']);

        // Aplicar filtros
        if ($producto_id) {
            $query->where('producto_id', $producto_id);
        }

        if ($presentacione_id) {
            $query->whereHas('producto', function($q) use ($presentacione_id) {
                $q->where('presentacione_id', $presentacione_id);
            });
        }

        if ($tipo_transaccion) {
            $query->where('tipo_transaccion', $tipo_transaccion);
        }

        $kardex = $query->latest()->get();

        return view('kardex.index', compact(
            'productos', 
            'kardex', 
            'producto_id',
            'presentaciones',
            'presentacione_id', 
            'tipo_transaccion'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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