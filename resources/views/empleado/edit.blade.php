@extends('layouts.app')

@section('title','Editar empleado')

@push('css')
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Editar Empleado</h1>

    <x-breadcrumb.template>
        <x-breadcrumb.item :href="route('panel')" content="Inicio" />
        <x-breadcrumb.item :href="route('empleados.index')" content="Empleados" />
        <x-breadcrumb.item active='true' content="Editar empleado" />
    </x-breadcrumb.template>

    <x-forms.template :action="route('empleados.update',['empleado'=> $empleado])"
        method='post'
        file='true'
        patch='true'>

        <div class="row g-4">

            {{-- Fila 1: Nombres y Apellido Paterno --}}
            <div class="col-md-6">
                {{-- Razon Social (Nombre) --}}
                <x-forms.input id="razon_social"
                    required='true'
                    labelText='Nombres'
                    :defaultValue='$empleado->razon_social' />
            </div>

            <div class="col-md-6">
                {{-- Apellido Paterno --}}
                <x-forms.input id="apellido_paterno"
                    required='true'
                    labelText='Apellido Paterno'
                    :defaultValue='$empleado->apellido_paterno' />
            </div>

            {{-- Fila 2: Apellido Materno y Correo --}}
            <div class="col-md-6">
                {{-- Apellido Materno (Opcional) --}}
                <x-forms.input id="apellido_materno"
                    labelText='Apellido Materno (Opcional)'
                    :defaultValue='$empleado->apellido_materno' />
            </div>

            <div class="col-md-6">
                {{-- Correo (Único) --}}
                <x-forms.input id="correo"
                    type='email'
                    required='true'
                    labelText='Correo Electrónico'
                    :defaultValue='$empleado->correo' />
            </div>

            {{-- Fila 3: Teléfono y Cargo --}}
            <div class="col-md-6">
                {{-- Teléfono (Opcional) --}}
                <x-forms.input id="telefono"
                    labelText='Teléfono (Opcional)'
                    :defaultValue='$empleado->telefono' />
            </div>
            
            <div class="col-md-6">
                <x-forms.input id="cargo"
                    required='true'
                    labelText='Cargo / Puesto'
                    :defaultValue='$empleado->cargo' />
            </div>
            
            {{-- Fila 4: Dirección (ocupa 12 columnas para ser más amplio) --}}
            <div class="col-md-12">
                {{-- Dirección (Opcional) --}}
                <x-forms.textarea id="direccion"
                    labelText='Dirección (Opcional)'
                    :defaultValue='$empleado->direccion' />
            </div>

            {{-- Fila 5: Imagen y Previsualización --}}
            <div class="col-md-6">
                <x-forms.input id="img" type='file' labelText='Seleccione Imagen para cambiar' />
            </div>

            <div class="col-md-6">
                <p>Imagen actual:</p>

                <img id="img-default"
                    class="img-fluid"
                    {{-- Usa la imagen existente si existe, si no, usa la por defecto --}}
                    src="{{ $empleado->img_path ? asset($empleado->img_path) : asset('assets/img/paisaje.png') }}"
                    alt="{{$empleado->razon_social}}">

                <img src="" alt="Ha cargado un archivo no compatible"
                    id="img-preview"
                    class="img-fluid img-thumbnail" style="display: none;">
            </div>


        </div>

        <x-slot name='footer'>
            <button type="submit" class="btn btn-primary">Actualizar</button>
        </x-slot>

    </x-forms.template>


</div>
@endsection

@push('js')
<script>
    const inputImagen = document.getElementById('img');
    const imagenPreview = document.getElementById('img-preview');
    const imagenDefault = document.getElementById('img-default');

    inputImagen.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                imagenPreview.src = e.target.result;
                imagenPreview.style.display = 'block';
                imagenDefault.style.display = 'none';
            }
            reader.readAsDataURL(this.files[0]);
        }
    });
</script>
@endpush