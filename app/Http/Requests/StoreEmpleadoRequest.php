<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreEmpleadoRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        // Determinamos si estamos creando (STORE) o editando (UPDATE)
        // Esto es crucial para la regla 'unique' del correo.
        $empleadoId = $this->route('empleado') ? $this->route('empleado')->id : null;

        return [
            // Campos de Nombre y Apellidos
            'razon_social' => 'required|string|max:255',
            'apellido_paterno' => 'required|string|max:255',
            'apellido_materno' => 'nullable|string|max:255',

            // Campo de Contacto
            'correo' => [
                'required',
                'email',
                'max:255',
                // La regla 'unique' ignora el ID del empleado actual si estamos editando
                Rule::unique('empleados', 'correo')->ignore($empleadoId),
            ],
            
            // Otros Campos
            'direccion' => 'nullable|string|max:500', // Un límite alto para direcciones largas
            'telefono' => 'nullable|string|max:20', 

            // Campos Existentes
            'cargo' => 'required|string|max:50',
            'img' => 'nullable|image|mimes:png,jpg,jpeg|max:2048'
        ];
    }
}