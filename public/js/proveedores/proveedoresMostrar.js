$(document).ready(function () {

    var servidor = window.location.origin + '/';
    var URLactual = servidor + 'proveedores/';
    var dataProveedor = {};

    // Token de Laravel
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var tablaProveedores = $('#tabla_proveedores').DataTable({
        'ajax': URLactual + 'lista_proveedores',
        'type': 'GET',
        'destroy': true,
        'processing': true,
        'responsive': true,
        'autoWidth': false,
        'dataType': 'json',
        // 'serverSide': true,
        'columns': [
            {
                'data': 'id_proveedores',
                'name': 'id_proveedores'
            },
            {
                'data': 'nombre',
                'name': 'nombre'
            },
            {
                'data': 'nit',
                'name': 'nit',
            },
            {
                'data': 'telefono',
                'name': 'telefono',
            },
            {
                'data': 'correo',
                'name': 'correo',
                render: function (data) {
                    if (data != null) return data;
                    return '-';
                }
            },
            {
                'data': 'direccion',
                'name': 'direccion',
                render: function (data) {
                    if (data != null) return data;
                    return '-';
                }
            },
            {
                'data': 'name',
                'name': 'name',
            },
            {
                'data': 'updated_at',
                'name': 'updated_at',
                render: function (data) {
                    return moment(data).format('DD-MM-YYYY - h:mm a');
                }
            },
            {
                'class': 'editar_proveedor',
                'orderable': false,
                'data': null,
                'defaultContent': '<td>' +
                    '<div class="action-buttons text-center">' +
                    '<a href="#" class="btn btn-primary btn-icon btn-sm">' +
                    '<i class="fas fa-edit"></i>' +
                    '</a>' +
                    '</div>' +
                    '</td>',
            },
            {
                'class': 'eliminar_proveedor',
                'orderable': false,
                'data': null,
                'defaultContent': '<td>' +
                    '<div class="action-buttons text-center">' +
                    '<a href="#" class="btn btn-danger btn-icon btn-sm">' +
                    '<i class="fas fa-trash-alt"></i>' +
                    '</a>' +
                    '</div>' +
                    '</td>',
            }
        ],
        'order': [[0, 'desc']],
        'lengthChange': true,
        'lengthMenu': [
            [6, 10, 25, 50, 75, 100, -1],
            [6, 10, 25, 50, 75, 100, 'ALL']
        ],
        'language': {
            'lengthMenu': 'Mostrar _MENU_ registros por página',
            'zeroRecords': 'No hay registros',
            'info': 'Mostrando página _PAGE_ de _PAGES_',
            'infoEmpty': 'No hay registros disponibles',
            'infoFiltered': '(filtrado de _MAX_ registros totales)',
            'search': 'Buscar:',
            'paginate': {
                'next': 'Siguiente',
                'previous': 'Anterior'
            }
        },
    });

    $('div.dataTables_filter input', tablaProveedores.table().container()).focus();

    $('#tabla_proveedores tbody').on('click', '.editar_proveedor', function () {
        let data = tablaProveedores.row(this).data();
        dataProveedor = data;
        let formulario = document.forms['formularioProveedor'];
        for (let elemento of formulario) {
            if (elemento.classList.contains('is-invalid')) {
                elemento.classList.remove('is-invalid');
            }
        }
        document.getElementById('formularioProveedor').setAttribute('action', URLactual + 'actualizar/' + data.id_proveedores);
        document.getElementById('idProveedor').value = data.id_proveedores;
        document.getElementById('nombreProveedor').value = data.nombre;
        document.getElementById('nitProveedor').value = data.nit;
        document.getElementById('telefonoProveedor').value = data.telefono;
        document.getElementById('correoProveedor').value = data.correo;
        document.getElementById('direccionProveedor').value = data.direccion;
        document.getElementById('formEditarProveedor').style.display = '';
    });

    $('#tabla_proveedores tbody').on('click', '.eliminar_proveedor', function () {
        let data = tablaProveedores.row(this).data();
        eliminarProveedor(data.id_proveedores, data.nombre);
    });

    document.getElementById('eliminar_proveedor2').addEventListener('click', function () {
        eliminarProveedor(dataProveedor.id_proveedores, dataProveedor.nombre);
    });

    function eliminarProveedor(id, nombre) {
        Swal.fire({
            title: '¿Desea eliminar al proveedor <b>' + nombre + '</b> ?',
            text: 'Al eliminar al proveedor automáticamente se eliminaran los productos y registros de inventario asociados al mismo',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                $.ajax({
                    url: URLactual + 'eliminar/' + id,
                    type: 'delete',
                    success: function () {
                        let tarjetaForm = document.getElementById('formEditarProveedor');
                        if (tarjetaForm.style.display != 'none') {
                            tarjetaForm.style.display = 'none';
                        }
                        tablaProveedores.ajax.reload();
                        Swal.fire({
                            icon: 'success',
                            title: 'Proveedor <b>' + nombre + '</b> eliminado exitosamente',
                            showConfirmButton: false,
                            timer: 2000
                        })
                    },
                    error: function () {
                        Swal.fire({
                            icon: 'error',
                            title: 'Algo salió mal',
                            text: 'Error al tratar de eliminar el proveedor del sistema',
                        })
                    }
                });
            }
        })
    }

    document.getElementById('btnOcultar').addEventListener('click', function () {
        document.getElementById('formEditarProveedor').style.display = 'none';
    });

    document.getElementById('nombreProveedor').addEventListener('keyup', function (evento) {
        let input = evento.target.value;
        document.getElementById('nombreProveedor').value = input.charAt(0).toUpperCase() + input.slice(1);
    });

    document.getElementById('correoProveedor').addEventListener('keyup', function (evento) {
        let input = evento.target.value;
        document.getElementById('correoProveedor').value = input.slice(0).toLowerCase();
    });

    document.getElementById('direccionProveedor').addEventListener('keyup', function (evento) {
        let input = evento.target.value;
        document.getElementById('direccionProveedor').value = input.charAt(0).toUpperCase() + input.slice(1).toLowerCase();
    });

    $('#formularioProveedor').validate({
        rules: {
            nombre: {
                required: true,
                maxlength: 50,
                minlength: 5
            },
            nit: {
                required: true,
                digits: true,
                maxlength: 15,
                minlength: 6,
            },
            telefono: {
                required: true,
                digits: true,
                maxlength: 10,
                minlength: 7,
            },
            correo: {
                email: true,
                maxlength: 50,
            },
            direccion: {
                maxlength: 50,
                minlength: 5
            },
        },
        messages: {
            nombre: {
                required: 'Se requiere que ingrese el nombre del proveedor',
                maxlength: 'El nombre debe tener máximo 50 caracteres',
                minlength: 'El nombre debe tener mínimo 5 caracteres',
            },
            nit: {
                required: 'Se requiere que ingrese el nit o identificador del proveedor',
                digits: 'El nit debe ser un valor númerico y no debe contener espacios',
                maxlength: 'El nit debe tener máximo 15 digitos',
                minlength: 'El nit debe tener mínimo 6 digitos',
            },
            telefono: {
                required: 'Se requiere que ingrese el teléfono del proveedor',
                digits: 'El teléfono debe ser un valor númerico y no debe contener espacios',
                maxlength: 'El teléfono debe tener máximo 10 digitos',
                minlength: 'El teléfono debe tener mínimo 7 digitos',
            },
            correo: {
                email: 'Ingrese una dirección de correo electrónico válida',
                maxlength: 'El correo debe tener máximo 50 caracteres',
            },
            direccion: {
                maxlength: 'La dirección debe tener máximo 50 caracteres',
                minlength: 'La dirección debe tener mínimo 5 caracteres',
            },
        },
        errorElement: 'span',
        errorPlacement: function (error, element) {
            error.addClass('invalid-feedback');
            element.closest('.form-group').append(error);
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass('is-invalid');
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).removeClass('is-invalid');
        },
    });

    $('input.proveedor').keydown(function (event) {
        let divPadre = $(this).closest('.form-group');
        if (divPadre.find('.errorServidor').length) {
            $(this).removeClass('is-invalid');
            divPadre.find('.errorServidor').text('');
            divPadre.find('.errorServidor').removeClass('errorServidor');
        }
    });

    function cargarPedidosProveedorIndividual() {
        $('#tabla_proveedor').DataTable({
            'ajax': servidor + 'inventario/lista_pedidos/' + dataProveedor.id_proveedores,
            'type': 'GET',
            'destroy': true,
            'processing': true,
            'responsive': true,
            'autoWidth': false,
            'dataType': 'json',
            'columns': [
                {
                    'data': 'id_inventario',
                    'name': 'id_inventario',
                    'width': '3%',
                },
                {
                    'data': 'producto',
                    'name': 'producto',
                    'width': '10%',
                },
                {
                    'data': 'cantidad',
                    'name': 'cantidad',
                    // 'class': 'text-center',
                    'width': '5%',
                    render: function (data, type, row) {
                        if (row.estado == true) {
                            return '<span class="text-success font-weight-bold" style="font-size: 18px">+</span>' + data;
                        }
                        return '<span class="text-danger font-weight-bold" style="font-size: 18px">-</span>' + data;
                    }
                },
                {
                    'data': 'cantidad_producto',
                    'name': 'cantidad_producto',
                    // 'class': 'text-center',
                    'width': '5%'
                },
                {
                    'data': 'costo',
                    'name': 'costo',
                    'width': '10%',
                    render: function (data) {
                        if (data != null) {
                            return data.toLocaleString('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 });
                        }
                        return '-';
                    }
                },
                {
                    'data': 'costo_unitario',
                    'name': 'costo_unitario',
                    'width': '10%',
                    render: function (data) {
                        if (data != null) {
                            return data.toLocaleString('es-CO', { style: 'currency', currency: 'COP', minimumFractionDigits: 0 });
                        }
                        return '-';
                    }
                },
                {
                    'data': 'fecha_vencimiento',
                    'name': 'fecha_vencimiento',
                    'width': '10%',
                    render: function (data) {
                        if (data != null) {
                            return moment(data).format('DD-MM-YYYY');
                        }
                        return '-';
                    }
                },
                {
                    'data': 'fecha',
                    'name': 'fecha',
                    'width': '10%',
                    render: function (data) {
                        return moment(data).format('DD-MM-YYYY');
                    }
                },
                {
                    'data': 'fecha',
                    'name': 'fecha',
                    'width': '10%',
                    render: function (data) {
                        return moment(data).format('h:mm:ss a');
                    }
                },
                {
                    'data': 'name',
                    'name': 'name',
                }
            ],
            'order': [[0, 'desc']],
            'lengthChange': true,
            'lengthMenu': [
                [5, 10, 25, 50, 75, 100, -1],
                [5, 10, 25, 50, 75, 100, 'ALL']
            ],
            'language': {
                'lengthMenu': 'Mostrar _MENU_ registros por página',
                'zeroRecords': 'No hay registros',
                'info': 'Mostrando página _PAGE_ de _PAGES_',
                'infoEmpty': 'No hay registros disponibles',
                'infoFiltered': '(filtrado de _MAX_ registros totales)',
                'search': 'Buscar:',
                'paginate': {
                    'next': 'Siguiente',
                    'previous': 'Anterior'
                }
            },
        });
    }

    document.getElementById('historial_proveedor').addEventListener('click', function (evento) {
        document.getElementById('tituloModalProv').textContent = 'Historial de pedidos del proveedor ' + dataProveedor.nombre;
        cargarPedidosProveedorIndividual();
    });

    (function () {
        let id_proveedor = document.getElementById('idProveedor').value;
        if (id_proveedor != '') {
            dataProveedor.id_proveedores = id_proveedor;
            dataProveedor.nombre = document.getElementById('nombreProveedor').value;
            document.getElementById('formularioProveedor').setAttribute('action', URLactual + 'actualizar/' + id_proveedor);
            document.getElementById('formEditarProveedor').style.display = '';
        }
    })();

});