<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Primero actualizar los registros existentes con TARJETA a EFECTIVO
        DB::table('ventas')->where('metodo_pago', 'TARJETA')->update(['metodo_pago' => 'EFECTIVO']);

        Schema::table('ventas', function (Blueprint $table) {
            // Modificar la columna enum para incluir solo EFECTIVO y QR
            $table->enum('metodo_pago', ['EFECTIVO', 'QR'])->change();
        });
    }

    public function down()
    {
        Schema::table('ventas', function (Blueprint $table) {
            // Revertir a los valores originales si es necesario
            $table->enum('metodo_pago', ['EFECTIVO', 'TARJETA', 'QR'])->change();
        });
    }
};
