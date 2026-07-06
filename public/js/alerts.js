document.addEventListener('DOMContentLoaded', function () {
    if (window.flashMessage && window.flashMessage.texto) {
        Swal.fire({
            icon: window.flashMessage.tipo || 'info',
            title: window.flashMessage.titulo || 'Mensaje',
            text: window.flashMessage.texto,
            confirmButtonText: 'Aceptar'
        });
    }
});

function confirmarEliminacion(url) {
    Swal.fire({
        title: '¿Está seguro?',
        text: 'Esta acción no se puede deshacer.',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar'
    }).then(function (result) {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });

    return false;
}

// Queda lista para futuras fases como activar, desactivar o cambiar estados.
function confirmarCambioEstado(url, accion) {
    Swal.fire({
        title: 'Confirmar acción',
        text: '¿Desea ' + accion + ' este registro?',
        icon: 'question',
        showCancelButton: true,
        confirmButtonText: 'Sí, continuar',
        cancelButtonText: 'Cancelar'
    }).then(function (result) {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });

    return false;
}
