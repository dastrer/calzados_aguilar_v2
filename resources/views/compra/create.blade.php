@extends('layouts.app')

@section('title','Realizar compra')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/css/bootstrap-select.min.css">
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endpush

@section('content')
<div class="container-fluid px-4">
    <h1 class="mt-4 text-center">Crear Compra</h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="{{ route('panel') }}">Inicio</a></li>
        <li class="breadcrumb-item"><a href="{{ route('compras.index')}}">Compras</a></li>
        <li class="breadcrumb-item active">Crear Compra</li>
    </ol>
</div>

<form action="{{ route('compras.store') }}" method="post" enctype="multipart/form-data">
    @csrf

    <div class="container-lg mt-4">
        <div class="row gy-4">

            <div class="col-12">
                <div class="text-white bg-success p-1 text-center">
                    Datos generales
                </div>
                <div class="p-3 border border-3 border-success">
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="proveedore_id" class="form-label">
                                Proveedor:</label>
                            <select name="proveedore_id"
                                id="proveedore_id" required
                                class="form-control selectpicker show-tick"
                                data-live-search="true"
                                title="Selecciona" data-size='2'>
                                @foreach ($proveedores as $item)
                                <option value="{{$item->id}}">{{$item->nombre_documento}}</option>
                                @endforeach
                            </select>
                            @error('proveedore_id')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="comprobante_id" class="form-label">
                                Comprobante:</label>
                            <select name="comprobante_id"
                                id="comprobante_id" required
                                class="form-control selectpicker"
                                title="Selecciona">
                                @foreach ($comprobantes as $item)
                                <option value="{{$item->id}}">{{$item->nombre}}</option>
                                @endforeach
                            </select>
                            @error('comprobante_id')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="numero_comprobante" class="form-label">
                                Numero de comprobante:</label>
                            <input type="text"
                                name="numero_comprobante"
                                id="numero_comprobante"
                                class="form-control"
                                onkeypress="return validarNumeroComprobante(event)">
                            @error('numero_comprobante')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-4">
                            <label for="file_comprobante" class="form-label">
                                Archivo:</label>
                            <input type="file"
                                name="file_comprobante"
                                id="file_comprobante"
                                class="form-control"
                                accept=".pdf">
                            @error('file_comprobante')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="metodo_pago" class="form-label">
                                Método de pago:</label>
                            <select required name="metodo_pago"
                                id="metodo_pago"
                                class="form-control selectpicker"
                                title="Selecciona">
                                <option value="EFECTIVO">Efectivo</option>
                                <option value="QR">QR</option>
                            </select>
                            @error('metodo_pago')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                        <div class="col-sm-6">
                            <label for="fecha_hora" class="form-label">
                                Fecha:</label>
                            <input
                                required
                                type="date"
                                name="fecha_hora"
                                id="fecha_hora"
                                class="form-control"
                                value="{{ date('Y-m-d') }}">
                            @error('fecha_hora')
                            <small class="text-danger">{{ '*'.$message }}</small>
                            @enderror
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="text-white bg-primary p-1 text-center">
                    Detalles de la compra
                </div>
                <div class="p-3 border border-3 border-primary">
                    <div class="row g-4">
                        <div class="col-12">
                            <select id="producto_id"
                                class="form-control selectpicker"
                                data-live-search="true"
                                data-live-search-placeholder="Buscar producto..."
                                data-size="10"
                                data-show-subtext="true"
                                title="Busque un producto aquí - Se muestran todos los productos disponibles">
                                @foreach ($productos as $item)
                                <option value="{{$item->id}}">{{ $item->texto_select }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <label for="cantidad" class="form-label">
                                Cantidad:</label>
                            <input type="text" id="cantidad" class="form-control" onkeypress="return validarCantidad(event)">
                        </div>

                        <div class="col-sm-6">
                            <label for="precio_compra" class="form-label">
                                Precio de compra:</label>
                            <input type="text" id="precio_compra" class="form-control" onkeypress="return validarPrecio(event)">
                        </div>

                        <div class="col-12 my-4 text-end">
                            <button id="btn_agregar" class="btn btn-primary" type="button">
                                Agregar</button>
                        </div>

                        <div class="col-12">
                            <div class="table-responsive">
                                <table id="tabla_detalle" class="table table-hover">
                                    <thead class="bg-primary">
                                        <tr>
                                            <th class="text-white">Producto</th>
                                            <th class="text-white">Presentación</th>
                                            <th class="text-white">Cantidad</th>
                                            <th class="text-white">Precio</th>
                                            <th class="text-white">Subtotal</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <th></th>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            <td></td>
                                            </tr>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="4">Sumas</th>
                                            <th colspan="2">
                                                <input type="hidden" name="subtotal" value="0" id="inputSubtotal">
                                                <span id="sumas">0</span>
                                                <span>{{$empresa->moneda->simbolo}}</span>
                                            </th>
                                        </tr>
                                        <tr style="display: none;">
                                            <th colspan="4">{{$empresa->abreviatura_impuesto}}</th>
                                            <th colspan="2">
                                                <input type="hidden" name="impuesto" value="0" id="inputImpuesto">
                                                <span id="igv">0</span>
                                                <span>{{$empresa->moneda->simbolo}}</span>
                                            </th>
                                        </tr>
                                        <tr>
                                            <th colspan="4">Total</th>
                                            <th colspan="2">
                                                <input type="hidden" name="total" value="0" id="inputTotal">
                                                <span id="total">0</span>
                                                <span>{{$empresa->moneda->simbolo}}</span>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>

                        <div class="col-12 mt-2">
                            <button id="cancelar"
                                type="button"
                                class="btn btn-danger"
                                data-bs-toggle="modal"
                                data-bs-target="#exampleModal">
                                Cancelar compra
                            </button>
                        </div>

                    </div>
                </div>
            </div>

            <div class="col-12 mt-4 text-center">
                <button type="submit" class="btn btn-success" id="guardar">
                    Realizar compra</button>
            </div>

        </div>
    </div>

    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Advertencia</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    ¿Seguro que quieres cancelar la compra?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        Cerrar</button>
                    <button id="btnCancelarCompra" type="button" class="btn btn-danger" data-bs-dismiss="modal">
                        Confirmar</button>
                </div>
            </div>
        </div>
    </div>

</form>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.14.0-beta3/dist/js/bootstrap-select.min.js"></script>
<script>
    $(document).ready(function() {
        // Configurar las restricciones de fecha
        configurarFecha();

        // Inicializar el selectpicker de productos para mostrar todas las opciones
        $('#producto_id').selectpicker('refresh');

        $('#btn_agregar').click(function() {
            agregarProducto();
        });

        $('#btnCancelarCompra').click(function() {
            cancelarCompra();
        });

        disableButtons();

        // Prevenir el pegado de contenido no válido
        $('#numero_comprobante, #cantidad, #precio_compra').on('paste', function(e) {
            e.preventDefault();
            showModal('No se permite pegar contenido en este campo', 'warning');
        });

        // Validación adicional para el campo de precio (permite punto decimal)
        $('#precio_compra').on('input', function() {
            this.value = this.value.replace(/[^0-9.]/g, '');

            // Asegurar que solo haya un punto decimal
            if ((this.value.match(/\./g) || []).length > 1) {
                this.value = this.value.substring(0, this.value.lastIndexOf('.'));
            }
        });

        // Validación adicional para cantidad (solo números enteros)
        $('#cantidad').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        // Validación adicional para número de comprobante (solo números)
        $('#numero_comprobante').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });

    // Funciones de validación para keypress
    function validarNumeroComprobante(event) {
        const charCode = (event.which) ? event.which : event.keyCode;
        // Permitir solo números (0-9)
        if (charCode < 48 || charCode > 57) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    function validarCantidad(event) {
        const charCode = (event.which) ? event.which : event.keyCode;
        // Permitir solo números (0-9)
        if (charCode < 48 || charCode > 57) {
            event.preventDefault();
            return false;
        }
        return true;
    }

    function validarPrecio(event) {
        const charCode = (event.which) ? event.which : event.keyCode;
        // Permitir números (0-9) y punto decimal (46)
        if ((charCode < 48 || charCode > 57) && charCode !== 46) {
            event.preventDefault();
            return false;
        }

        // Evitar múltiples puntos decimales
        if (charCode === 46 && event.target.value.includes('.')) {
            event.preventDefault();
            return false;
        }

        return true;
    }

    function configurarFecha() {
        const fechaInput = document.getElementById('fecha_hora');
        const hoy = new Date();

        // Calcular fecha mínima (una semana antes)
        const fechaMinima = new Date();
        fechaMinima.setDate(hoy.getDate() - 7);

        // Calcular fecha máxima (una semana después)
        const fechaMaxima = new Date();
        fechaMaxima.setDate(hoy.getDate() + 7);

        // Formatear fechas para el input date (YYYY-MM-DD)
        const formatoFecha = (fecha) => {
            return fecha.toISOString().split('T')[0];
        };

        // Establecer atributos min y max
        fechaInput.min = formatoFecha(fechaMinima);
        fechaInput.max = formatoFecha(fechaMaxima);
        fechaInput.value = formatoFecha(hoy);

        // Validar en tiempo real
        fechaInput.addEventListener('change', function() {
            const fechaSeleccionada = new Date(this.value);
            if (fechaSeleccionada < fechaMinima || fechaSeleccionada > fechaMaxima) {
                showModal('La fecha debe estar dentro del rango permitido: una semana antes y una semana después de hoy', 'warning');
                this.value = formatoFecha(hoy); // Resetear a hoy
            }
        });
    }

    //Variables
    let cont = 0;
    let subtotal = [];
    let sumas = 0;
    let igv = 0;
    let total = 0;
    let arrayIdProductos = [];

    //Constantes
    const impuesto = 0;

    function cancelarCompra() {
        //Elimar el tbody de la tabla
        $('#tabla_detalle tbody').empty();

        //Añadir una nueva fila a la tabla (con la nueva estructura de 6 celdas)
        let fila = '<tr>' +
            '<th></th>' +
            '<td></td>' +
            '<td></td>' +
            '<td></td>' +
            '<td></td>' +
            '<td></td>' +
            '</tr>';
        $('#tabla_detalle').append(fila);

        //Reiniciar valores de las variables
        cont = 0;
        subtotal = [];
        sumas = 0;
        igv = 0;
        total = 0;
        arrayIdProductos = [];

        //Mostrar los campos calculados
        $('#sumas').html(sumas);
        $('#igv').html(igv);
        $('#total').html(total);
        $('#inputImpuesto').val(igv);
        $('#inputSubtotal').val(sumas);
        $('#inputTotal').val(total);

        limpiarCampos();
        disableButtons();
    }

    function disableButtons() {
        if (total == 0) {
            $('#guardar').hide();
            $('#cancelar').hide();
        } else {
            $('#guardar').show();
            $('#cancelar').show();
        }
    }

    function agregarProducto() {
        //Obtener valores de los campos
        let idProducto = $('#producto_id').val();
        let textProducto = $('#producto_id option:selected').text();
        let cantidad = $('#cantidad').val();
        let precioCompra = $('#precio_compra').val();

        //Validaciones
        //1.Para que los campos no esten vacíos
        if (textProducto != '' && textProducto != undefined && cantidad != '' && precioCompra != '') {

            // NUEVO: Extraer nombre y presentación del formato "nombre - modelo"
            let partes = textProducto.split(' - ');
            let nameProducto = partes[0]; // Nombre del producto
            let presentacionProducto = partes.slice(1).join(' - '); // Modelo/presentación (por si hay más de un guión)

            //2. Para que los valores ingresados sean los correctos
            if (parseInt(cantidad) > 0 && (cantidad % 1 == 0) && parseFloat(precioCompra) > 0) {

                //3. No permitir el ingreso del mismo producto
                if (!arrayIdProductos.includes(idProducto)) {
                    //Calcular valores
                    subtotal[cont] = round(cantidad * precioCompra);
                    sumas = round(sumas + subtotal[cont]);
                    igv = round(sumas / 100 * impuesto);
                    total = round(sumas + igv);

                    //Crear la fila
                    let fila = '<tr id="fila' + cont + '">' +
                        '<td><input type="hidden" name="arrayidproducto[]" value="' + idProducto + '">' + nameProducto + '</td>' +
                        '<td>' + presentacionProducto + '</td>' +
                        '<td><input type="hidden" name="arraycantidad[]" value="' + cantidad + '">' + cantidad + '</td>' +
                        '<td><input type="hidden" name="arraypreciocompra[]" value="' + precioCompra + '">' + precioCompra + '</td>' +
                        '<td>' + subtotal[cont] + '</td>' +
                        '<td><button class="btn btn-danger" type="button" onClick="eliminarProducto(' + cont + ', ' + idProducto + ')"><i class="fa-solid fa-trash"></i></button></td>' +
                        '</tr>';

                    //Acciones después de añadir la fila
                    $('#tabla_detalle').append(fila);
                    limpiarCampos();
                    cont++;
                    disableButtons();

                    //Mostrar los campos calculados
                    $('#sumas').html(sumas);
                    $('#igv').html(igv);
                    $('#total').html(total);
                    $('#inputImpuesto').val(igv);
                    $('#inputSubtotal').val(sumas);
                    $('#inputTotal').val(total);

                    //Agregar el id del producto al arreglo
                    arrayIdProductos.push(idProducto);

                } else {
                    showModal('Ya ha ingresado el producto');
                }

            } else {
                showModal('Valores incorrectos');
            }

        } else {
            showModal('Le faltan campos por llenar');
        }
    }

    function eliminarProducto(indice, idProducto) {
        //Calcular valores
        sumas -= round(subtotal[indice]);
        igv = round(sumas / 100 * impuesto);
        total = round(sumas + igv);

        //Mostrar los campos calculados
        $('#sumas').html(sumas);
        $('#igv').html(igv);
        $('#total').html(total);
        $('#inputImpuesto').val(igv);
        $('#inputSubtotal').val(sumas);
        $('#inputTotal').val(total);

        //Eliminar el fila de la tabla
        $('#fila' + indice).remove();

        //Eliminar el id del arreglo
        let index = arrayIdProductos.indexOf(idProducto.toString());
        arrayIdProductos.splice(index, 1);

        disableButtons();

    }

    function limpiarCampos() {
        let select = $('#producto_id');
        select.selectpicker('val', '');
        $('#cantidad').val('');
        $('#precio_compra').val('');
    }

    function round(num, decimales = 2) {
        var signo = (num >= 0 ? 1 : -1);
        num = num * signo;
        if (decimales === 0) //con 0 decimales
            return signo * Math.round(num);
        // round(x * 10 ^ decimales)
        num = num.toString().split('e');
        num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
        // x * 10 ^ (-decimales)
        num = num.toString().split('e');
        return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
    }

    function showModal(message, icon = 'error') {
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
            }
        })

        Toast.fire({
            icon: icon,
            title: message
        })
    }
//agrego mi comment para git
</script>
@endpush
