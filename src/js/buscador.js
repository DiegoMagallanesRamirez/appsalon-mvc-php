
document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
})

function iniciarApp() {
    buscarPorFecha();
}

/* Función para buscar las citas por fecha desde la vista del admin. */
function buscarPorFecha() {
    const fechaInput = document.querySelector('#fecha');
    fechaInput.addEventListener('input', function(e) {
        const fechaSeleccionada = e.target.value;

        window.location = `?fecha=${fechaSeleccionada}`;
    })
}