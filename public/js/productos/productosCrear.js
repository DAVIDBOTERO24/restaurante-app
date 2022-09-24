$(document).ready(function () {

    $('#formCrearProducto').validate({
        rules: {
            nombre: {
                required: true,
                maxlength: 40,
                minlength: 4,
            },
            codigo: {
                required: true,
                digits: true,
                maxlength: 10,
                minlength: 5,
            },
            id_proveedor: {
                required: true,
            }, 
            unidad: {
                required: true,
            },
            total: {
                required: true,
                digits: true,
            },
        },
        messages: {
            nombre: {
                required: 'Se requiere que ingrese el nombre del producto',
                maxlength: 'El nombre debe tener máximo 40 caracteres',
                minlength: 'El nombre debe tener mínimo 5 caracteres',
            },
            codigo: {
                required: 'Se requiere que ingrese el codigo o identificador del producto',
                digits: 'El codigo debe ser un valor númerico y no debe contener espacios',
                maxlength: 'El codigo debe tener máximo 10 digitos',
                minlength: 'El codigo debe tener mínimo 5 digitos',
            },
            id_proveedor: {
                required: 'Se requiere que elija el nombre del proveedor del producto',
            }, 
            unidad: {
                required: 'Se requiere que ingrese la unidad del producto',

            },
            total: {
                required: 'Se requiere que ingrese el total inicial del producto',
                digits: 'El codigo debe ser un valor númerico y no debe contener espacios',
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

});