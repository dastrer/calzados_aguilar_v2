<?php

namespace App\Http\Controllers;

use App\Exports\VentasExport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;

class ExportExcelController extends Controller
{
    /**
     * Exportar en EXCEL todas las ventas
     */
    public function exportExcelVentasAll()
    {
        try {
            $filename = 'ventas_' . now()->format('Y_m_d_His') . '.xlsx';

            // Descarga inmediata
            return Excel::download(new VentasExport(), $filename);

        } catch (\Exception $e) {
            return redirect()->route('ventas.index')
                ->with('error', 'Error al generar Excel: ' . $e->getMessage());
        }
    }
}
