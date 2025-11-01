<?php

namespace App\Http\Requests;

use App\Enums\TipoPersonaEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class StorePersonaRequest extends FormRequest
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
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'tipo' => ['required', new Enum(TipoPersonaEnum::class)],
            'razon_social' => 'required_if:tipo,JURIDICA|max:255',
            'nombres' => 'required_if:tipo,NATURAL|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellido_paterno' => 'required_if:tipo,NATURAL|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'apellido_materno' => 'nullable|string|max:100|regex:/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/',
            'direccion' => 'nullable|max:255',
            'telefono' => 'nullable|max:15',
            'email' => 'nullable|max:255|email',
            'documento_id' => 'required|integer|exists:documentos,id',
            'numero_documento' => 'required|max:20|unique:personas,numero_documento'
        ];
    }

    public function messages(): array
    {
        return [
            'nombres.regex' => 'El campo nombres solo puede contener letras y espacios.',
            'apellido_paterno.regex' => 'El campo apellido paterno solo puede contener letras y espacios.',
            'apellido_materno.regex' => 'El campo apellido materno solo puede contener letras y espacios.',
            'razon_social.required_if' => 'El campo razón social es obligatorio para personas jurídicas.',
            'nombres.required_if' => 'El campo nombres es obligatorio para personas naturales.',
            'apellido_paterno.required_if' => 'El campo apellido paterno es obligatorio para personas naturales.',
        ];
    }
}