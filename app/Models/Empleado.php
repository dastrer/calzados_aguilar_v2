<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class Empleado extends Model
{
    // Reemplazamos $guarded por $fillable, listando todos los campos que pueden ser asignados
    protected $fillable = [
        'razon_social',
        'apellido_paterno',
        'apellido_materno',
        'correo',
        'telefono',
        'direccion',
        'cargo',
        'img_path',
    ];

    /**
     * Define la relación con el usuario (si existe)
     */
    public function user(): HasOne
    {
        return $this->hasOne(User::class);
    }

    /**
     * Define la lógica de los eventos del modelo.
     * Aquí se maneja la eliminación de la imagen al eliminar el empleado.
     */
    protected static function boot()
    {
        parent::boot();

        // Eliminar la imagen asociada cuando se elimina el registro del empleado
        static::deleting(function ($empleado) {
            // Utilizamos el método existente para eliminar la imagen
            if ($empleado->img_path) {
                $empleado->handleDeleteImage($empleado->img_path);
            }
            
            // Nota: La eliminación del usuario asociado se mantiene en el EmpleadoController
            // para una mejor gestión de errores y mensajes de confirmación.
        });
    }
    
    /**
     * Elimina el archivo de imagen del almacenamiento.
     * @param string $img_path La ruta completa de la imagen a eliminar (Ej: storage/empleados/uniqueid.jpg).
     * @return void
     */
    public function handleDeleteImage(string $img_path): void
    {
        // 1. Quitamos 'storage/' para obtener la ruta relativa al disco 'public'
        $relative_path = str_replace('storage/', '', $img_path); 

        if (Storage::disk('public')->exists($relative_path)) {
            Storage::disk('public')->delete($relative_path);
        }
    }


    /**
     * Guarda la imagen en el servidor, elimina la anterior si existe, y devuelve la ruta.
     * @param UploadedFile $image El archivo de imagen subido.
     * @param string|null $img_path La ruta de la imagen anterior a eliminar.
     * @return string La nueva ruta de la imagen (Ej: storage/empleados/uniqueid.jpg).
     */
    public function handleUploadImage(UploadedFile $image, $img_path = null): string
    {
        // 1. Elimina la imagen anterior si existe usando el nuevo método
        if ($img_path) {
            $this->handleDeleteImage($img_path);
        }

        // 2. Guarda la nueva imagen
        $name = uniqid() . '.' . $image->getClientOriginalExtension();
        // Usamos storeAs en el disco 'public'. Por defecto, Laravel agrega 'public/' al inicio
        // La función storeAs devuelve la ruta relativa al disco (ej: empleados/uniqueid.jpg)
        $path_relative_to_disk = $image->storeAs('empleados', $name, 'public'); 
        
        // 3. Devolvemos la ruta completa con 'storage/' para el campo img_path en la BD
        return 'storage/' . $path_relative_to_disk;
    }
}
