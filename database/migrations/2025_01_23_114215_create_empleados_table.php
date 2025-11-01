<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            
            // Campo de Nombre
            $table->string('razon_social'); 
            
            // Campos de Apellidos
            $table->string('apellido_paterno');
            $table->string('apellido_materno')->nullable(); // Opcional
            
            // Campo de Contacto Único
            $table->string('correo')->unique(); 

            // Otros Campos de Contacto e Información
            $table->string('telefono', 20)->nullable(); 
            $table->text('direccion')->nullable(); // Usamos 'text' para direcciones más largas

            // Campo Existente
            $table->string('cargo', 50);
            $table->string('img_path', 2048)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    // En tu migración original
public function down(): void
{
    Schema::dropIfExists('empleados'); // ESTO BORRA LA TABLA Y LOS DATOS
}
};