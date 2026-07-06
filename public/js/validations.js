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

            if (form.classList.contains('repuesto-form')) {
                validateRepuestoForm(form);
            }

            if (form.classList.contains('mantenimiento-form')) {
                validateMantenimientoForm(form);
            }

            if (form.classList.contains('mantenimiento-edit-form')) {
                validateMantenimientoEditForm(form);
            }

            if (form.classList.contains('reporte-fechas-form')) {
                validateReporteFechasForm(form);
            }

            if (form.classList.contains('reporte-cliente-form')) {
                validateReporteClienteForm(form);
            }

            if (form.classList.contains('reporte-estado-form')) {
                validateReporteEstadoForm(form);
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

    function validateRepuestoForm(form) {
        var stock = form.querySelector('[name="stock"]');
        var precio = form.querySelector('[name="precio"]');

        if (stock) {
            var stockValue = stock.value.trim();
            var stockNumero = Number(stockValue);
            var stockError = '';

            if (stockValue === '') {
                stockError = 'Ingrese el stock.';
            } else if (!/^\d+$/.test(stockValue)) {
                stockError = 'Stock invalido.';
            } else if (stockNumero < 0) {
                stockError = 'El stock no puede ser negativo.';
            }

            stock.setCustomValidity(stockError);
        }

        if (precio) {
            var precioValue = precio.value.trim();
            var precioNumero = Number(precioValue);
            var precioError = '';

            if (precioValue === '') {
                precioError = 'Ingrese el precio.';
            } else if (isNaN(precioNumero)) {
                precioError = 'Precio invalido.';
            } else if (precioNumero <= 0) {
                precioError = 'El precio debe ser mayor a 0.';
            }

            precio.setCustomValidity(precioError);
        }
    }

    function validateMantenimientoForm(form) {
        validateMantenimientoBase(form);

        var repuestos = form.querySelectorAll('[name="repuesto_ids[]"]');
        var cantidades = form.querySelectorAll('[name="cantidades[]"]');
        var tieneRepuesto = false;

        repuestos.forEach(function (repuesto, index) {
            var cantidad = cantidades[index];
            var repuestoSeleccionado = repuesto.value.trim() !== '';
            var cantidadValor = cantidad ? cantidad.value.trim() : '';
            var cantidadNumero = Number(cantidadValor);
            var stock = repuesto.selectedOptions.length > 0 ? Number(repuesto.selectedOptions[0].getAttribute('data-stock') || '0') : 0;
            var errorCantidad = '';

            if (repuestoSeleccionado) {
                tieneRepuesto = true;

                if (cantidadValor === '') {
                    errorCantidad = 'Ingrese la cantidad del repuesto.';
                } else if (!/^\d+$/.test(cantidadValor)) {
                    errorCantidad = 'La cantidad debe ser numerica.';
                } else if (cantidadNumero <= 0) {
                    errorCantidad = 'La cantidad debe ser mayor a 0.';
                } else if (cantidadNumero > stock) {
                    errorCantidad = 'La cantidad supera el stock disponible.';
                }
            } else if (cantidadValor !== '') {
                errorCantidad = 'Seleccione un repuesto para esa cantidad.';
            }

            if (cantidad) {
                cantidad.setCustomValidity(errorCantidad);
            }
        });

        if (!tieneRepuesto && repuestos.length > 0) {
            repuestos[0].setCustomValidity('Seleccione al menos un repuesto.');
        } else if (repuestos.length > 0) {
            repuestos[0].setCustomValidity('');
        }
    }

    function validateMantenimientoEditForm(form) {
        validateMantenimientoBase(form);
    }

    function validateMantenimientoBase(form) {
        var cliente = form.querySelector('[name="id_cliente"]');
        var moto = form.querySelector('[name="id_moto"]');
        var fecha = form.querySelector('[name="fecha"]');
        var tipoServicio = form.querySelector('[name="tipo_servicio"]');
        var descripcion = form.querySelector('[name="descripcion"]');
        var costo = form.querySelector('[name="costo_mano_obra"]');
        var estado = form.querySelector('[name="estado"]');

        if (cliente) {
            cliente.setCustomValidity(cliente.value.trim() === '' ? 'Seleccione un cliente.' : '');
        }

        if (moto) {
            moto.setCustomValidity(moto.value.trim() === '' ? 'Seleccione una moto.' : '');
        }

        if (fecha) {
            fecha.setCustomValidity(fecha.value.trim() === '' ? 'Ingrese la fecha.' : '');
        }

        if (tipoServicio) {
            tipoServicio.setCustomValidity(tipoServicio.value.trim() === '' ? 'Ingrese el tipo de servicio.' : '');
        }

        if (descripcion) {
            descripcion.setCustomValidity(descripcion.value.trim() === '' ? 'Ingrese la descripcion.' : '');
        }

        if (costo) {
            var costoValue = costo.value.trim();
            var costoNumero = Number(costoValue);
            var costoError = '';

            if (costoValue === '') {
                costoError = 'Ingrese el costo de mano de obra.';
            } else if (isNaN(costoNumero)) {
                costoError = 'El costo debe ser numerico.';
            } else if (costoNumero < 0) {
                costoError = 'El costo no puede ser negativo.';
            }

            costo.setCustomValidity(costoError);
        }

        if (estado) {
            estado.setCustomValidity(estado.value.trim() === '' ? 'Seleccione un estado.' : '');
        }
    }

    var clienteMotoSelect = document.getElementById('id_cliente');
    var motoSelect = document.getElementById('id_moto');

    if (clienteMotoSelect && motoSelect) {
        filterMotoOptions(clienteMotoSelect, motoSelect);

        clienteMotoSelect.addEventListener('change', function () {
            filterMotoOptions(clienteMotoSelect, motoSelect);
        });
    }

    function filterMotoOptions(clienteSelect, motoSelect) {
        var clienteId = clienteSelect.value;
        var currentValue = motoSelect.value;

        Array.prototype.forEach.call(motoSelect.options, function (option, index) {
            if (index === 0) {
                option.hidden = false;
                return;
            }

            var optionCliente = option.getAttribute('data-cliente');
            var visible = clienteId === '' || optionCliente === clienteId;
            option.hidden = !visible;

            if (!visible && option.value === currentValue) {
                motoSelect.value = '';
            }
        });
    }

    function validateReporteFechasForm(form) {
        var fechaInicio = form.querySelector('[name="fecha_inicio"]');
        var fechaFin = form.querySelector('[name="fecha_fin"]');

        if (fechaInicio) {
            fechaInicio.setCustomValidity(fechaInicio.value.trim() === '' ? 'Ingrese la fecha inicio.' : '');
        }

        if (fechaFin) {
            fechaFin.setCustomValidity(fechaFin.value.trim() === '' ? 'Ingrese la fecha fin.' : '');
        }

        if (fechaInicio && fechaFin && fechaInicio.value !== '' && fechaFin.value !== '') {
            var error = fechaInicio.value > fechaFin.value ? 'La fecha inicio no puede ser mayor que la fecha fin.' : '';
            fechaFin.setCustomValidity(error);
        }
    }

    function validateReporteClienteForm(form) {
        var cliente = form.querySelector('[name="id_cliente"]');

        if (cliente) {
            cliente.setCustomValidity(cliente.value.trim() === '' ? 'Seleccione un cliente.' : '');
        }
    }

    function validateReporteEstadoForm(form) {
        var estado = form.querySelector('[name="estado"]');

        if (estado) {
            estado.setCustomValidity(estado.value.trim() === '' ? 'Seleccione un estado.' : '');
        }
    }
});
