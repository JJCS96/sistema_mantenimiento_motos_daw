document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('.needs-validation');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (form.classList.contains('cliente-form')) {
                validateClienteForm(form);
            }

            if (form.classList.contains('moto-form')) {
                validateMotoForm(form);
            }

            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }

            form.classList.add('was-validated');
        });
    });

    function validateClienteForm(form) {
        var cedula = form.querySelector('[name="cedula"]');
        var telefono = form.querySelector('[name="telefono"]');
        var correo = form.querySelector('[name="correo"]');
        var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

        if (cedula) {
            var cedulaValida = /^\d{10}$/.test(cedula.value.trim());
            cedula.setCustomValidity(cedulaValida ? '' : 'La cedula debe tener 10 digitos.');
        }

        if (telefono) {
            var telefonoValido = /^\d{10}$/.test(telefono.value.trim());
            telefono.setCustomValidity(telefonoValido ? '' : 'El telefono debe tener 10 digitos.');
        }

        if (correo) {
            var correoValido = emailPattern.test(correo.value.trim());
            correo.setCustomValidity(correoValido ? '' : 'Ingrese un correo valido.');
        }
    }

    function validateMotoForm(form) {
        var cliente = form.querySelector('[name="id_cliente"]');
        var placa = form.querySelector('[name="placa"]');
        var anio = form.querySelector('[name="anio"]');
        var currentYear = new Date().getFullYear();

        if (cliente) {
            cliente.setCustomValidity(cliente.value.trim() === '' ? 'Seleccione un cliente.' : '');
        }

        if (placa) {
            placa.setCustomValidity(placa.value.trim() === '' ? 'Ingrese la placa.' : '');
        }

        if (anio) {
            var valor = anio.value.trim();
            var numero = Number(valor);
            var esNumeroValido = /^\d+$/.test(valor);
            var error = '';

            if (valor === '') {
                error = 'Ingrese el anio.';
            } else if (!esNumeroValido) {
                error = 'El anio debe ser numerico.';
            } else if (numero < 1990) {
                error = 'El anio no puede ser menor a 1990.';
            } else if (numero > currentYear) {
                error = 'El anio no puede ser mayor al actual.';
            }

            anio.setCustomValidity(error);
        }
    }
});
