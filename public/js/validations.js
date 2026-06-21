document.addEventListener('DOMContentLoaded', function () {
    var forms = document.querySelectorAll('.needs-validation');

    forms.forEach(function (form) {
        form.addEventListener('submit', function (event) {
            if (form.classList.contains('cliente-form')) {
                validateClienteForm(form);
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
});
