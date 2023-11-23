let paso = 1;
const pasoInicial = 1;
const pasoFinal = 3;

const cita = {
    id: '',
    nombre: '',
    fecha: '',
    hora: '',
    servicios: []
}

/*  */
document.addEventListener('DOMContentLoaded', function() {
    iniciarApp();
});

/* Función inicial para indicar todas las funciones que se van a realizar */
function iniciarApp() {
    mostrarSeccion()    // Muestra y oculta las secciones.
    tabs();     // cambia la sección cuando se presionan los tabs.
    botonesPaginador(); // Muestra y/o oculta los botones de paginación.
    paginaAnterior(); // Da funcionamiento a los botones de paginación.
    paginaSiguiente();

    consultarAPI();     // Consulta la API en el backend de PHP

    idCliente();        // Recupera el id del cliente que solicita el servicio.
    nombreCliente();    // Añade el nombre del cliente al objeto de cita.
    seleccionarFecha(); // Añade la fecha de la cita en el objeto de cita.
    seleccionarHora();  // Añade la hora de la cita en el objeto de cita.
    mostrarResumen();   // Muestra el resumen de la cita.
}

/* Muestra y/o oculta las secciones de servicios, cita y resumen */
function mostrarSeccion() {
    // Ocultar los tabs que se están mostrando.
    const seccionAnterior = document.querySelector('.mostrar');
    if (seccionAnterior) {
        seccionAnterior.classList.remove('mostrar');
    }

    // Seleccionar la sección con el paso y mostrarlo.
    const pasoSelector = `#paso-${paso}`;
    const seccion = document.querySelector(pasoSelector);
    seccion.classList.add('mostrar');

    // Quita la clase de actual al tab anterior
    const tabAnterior = document.querySelector('.actual')
    if (tabAnterior) {
        tabAnterior.classList.remove('actual');
    }

    // Resalta el tab actual
    const tabActual = document.querySelector(`[data-paso="${paso}"]`);
    tabActual.classList.add('actual');
}

/* Actualiza el estado de los tabs dependiendo la sección. */
function tabs() {
    const botones = document.querySelectorAll('.tabs button');

    botones.forEach( boton => boton.addEventListener('click', function(e) {
        paso = parseInt(e.target.dataset.paso);

        // Se agregan estas funciones para que cada que ocurra un evento, 
        // también se apliquen estos cambios.
        mostrarSeccion();
        botonesPaginador();
    })) 
}

/* Muestra y/o oculta los botones de Anterior y Siguiendo, dependiendo la sección */
function botonesPaginador() {
    const botonAnterior = document.querySelector('#anterior');
    const botonSiguiente = document.querySelector('#siguiente');

    if (paso == 1) {
        botonAnterior.classList.add('ocultar');
        botonSiguiente.classList.remove('ocultar');
    } else if (paso == 3) {
        botonAnterior.classList.remove('ocultar');
        botonSiguiente.classList.add('ocultar');
        mostrarResumen();
    } else {
        botonAnterior.classList.remove('ocultar');
        botonSiguiente.classList.remove('ocultar');
    }
    // Muestra la sección que corresponda.
    mostrarSeccion();
}

/* Da funcionalidad al botón anterior */
function paginaAnterior() {
    const paginaAnterior = document.querySelector('#anterior');
    paginaAnterior.addEventListener('click', function() {
        // Si el paso es menor a 1 ya no hace nada.
        if (paso <= pasoInicial) return;
        // E.o.c. reduce el paso.
        paso--;
        // Actualiza los botones del paginador:
        botonesPaginador();
    });
}

/* Da funcionalidad al botón siguiente */
function paginaSiguiente() {
    const paginaSiguiente = document.querySelector('#siguiente');
    paginaSiguiente.addEventListener('click', function() {
        // Si el paso es menor a 1 ya no hace nada.
        if (paso >= pasoFinal) return;
        // E.o.c. reduce el paso.
        paso++;
        // Actualiza los botones del paginador:
        botonesPaginador();
    });
}

async function consultarAPI() {

    try {
        const url = `${location.origin}/api/servicios`;
        const resultado = await fetch(url);         // Espera a que recupere toda la respuesta
        const servicios = await resultado.json();   // Espera hasta tener todos los servicios
        mostrarServicios(servicios);
    } catch (error) {
        console.log(error);
    }
}

/* */
function mostrarServicios(servicios) {
    servicios.forEach( servicio => {
        const { id, nombre, precio } = servicio;    // Obtiene los valores del servicio.

        // Agrega el nombre del servicio a la vista.
        const nombreServicio = document.createElement('P');
        nombreServicio.classList.add('nombre-servicio');
        nombreServicio.textContent = nombre;

        // Agrega el precio a la vista.
        const precioServicio = document.createElement('P');
        precioServicio.classList.add('precio-servicio');
        precioServicio.textContent = `$${precio}`;

        // Agrega el div del servicio.
        const servicioDiv = document.createElement('DIV');
        servicioDiv.classList.add('servicio');
        servicioDiv.dataset.idServicio = id;
        servicioDiv.onclick = function() {
            seleccionarServicio(servicio);
        }

        // Agrega el nombre y precio del servicio a la vista.
        servicioDiv.appendChild(nombreServicio);
        servicioDiv.appendChild(precioServicio);

        document.querySelector('#servicios').appendChild(servicioDiv);
    });
}

/* */
function seleccionarServicio(servicio) {
    // Recuperamos el id del servicio:
    const { id } = servicio;
    // Tomamos los servicios actuales de la cita:
    const { servicios } = cita;
    // Obtenemos los servicios seleccionados.
    const divServicio = document.querySelector(`[data-id-servicio="${id}"]`);

    // Comprobar si un servicio ya fue agregado
    // Si alguno de los servicios ya AGREGADOS a la cita coincide con el id
    // del servicio que se esta seleccionando...
    if (servicios.some( agregado => agregado.id === id)) {
        // ... Ent. Ya está agregado. Hay que quitarlo.
        // De los servicios en la cita, filtramos aquellos que no coincidan
        // con el id del servicio seleccionado.
        cita.servicios = servicios.filter(agregado => agregado.id !== id);
        divServicio.classList.remove('seleccionado');
    } else {
        // No está agregado. Hay que agregarlo
        // Agregamos el nuevo servicio a los servicios:
        cita.servicios = [...servicios, servicio];
        divServicio.classList.add('seleccionado');
    }
}

/* Obtiene y agrega el id del cliente a la cita */
function idCliente() {
    cita.id = document.querySelector('#id').value;
}

/* Obtiene y agrega el nombre del cliente a la cita */
function nombreCliente() {
    cita.nombre = document.querySelector('#nombre').value;
}

/* */
function seleccionarFecha() {
    const inputFecha = document.querySelector('#fecha');
    inputFecha.addEventListener('input', function(e) {
        // Obtiene el dia de la semana:
        // 0 -> Domingo
        // 1 -> Lunes
        // ...
        // 6 -> Sábado
        const dia = new Date(e.target.value).getUTCDay();

        if ( [6,0].includes(dia) ) {
            e.target.value = '';
            mostrarAlerta('Sábados y domingos no abrimos', 'error', '.formulario');
        } else {
            cita.fecha = e.target.value;
        }
    });
}

/* */
function seleccionarHora() {
    const inputHora = document.querySelector('#hora');
    inputHora.addEventListener('input', function(e) {
        const horaCita = e.target.value;
        const hora = horaCita.split(":")[0];

        if (hora < 10 || hora > 18) {
            e.target.value = '';
            mostrarAlerta('Horario fuera de servicio', 'error', '.formulario');
        } else {
            cita.hora = e.target.value;
            // console.log(cita);
        }
    });
}

/* */
function mostrarAlerta(mensaje, tipo, elemento, desaparece = true) {
    // Revisamos si ya había una alerta antes para no mostrar más de una.
    const alertaPrevia = document.querySelector('.alerta');
    if (alertaPrevia) {
        alertaPrevia.remove();
    }

    // Scripting para generar la alerta.
    const alerta = document.createElement('DIV');
    alerta.textContent = mensaje.toUpperCase();
    alerta.classList.add('alerta');
    alerta.classList.add(tipo);

    const referencia = document.querySelector(elemento);
    referencia.appendChild(alerta);

    if (desaparece) {
        // Elimina la alerta después de 3s.
        setTimeout(() => {
            alerta.remove();
        }, 3000);
    }
}

/* */
function mostrarResumen() {
    const resumen = document.querySelector('.contenido-resumen');

    // Limpia el contenido de Resumen.
    while(resumen.firstChild) {
        resumen.removeChild(resumen.firstChild);
    }

    // Valida que la cita este completamente llena y se haya seleccionado
    // al menos un servicio.
    if (Object.values(cita).includes('') || cita.servicios.length === 0) {
        mostrarAlerta('Falta seleccionar algún servicio o datos de la cita', 'error', '.contenido-resumen', false);
        return;
    }
    
    // Formatear el div de resumen
    const { nombre, fecha, hora, servicios } = cita;

    // Heading para Servicios en Resumen
    const headingServicios = document.createElement('H3');
    headingServicios.textContent = 'Resumen de Servicios';
    resumen.appendChild(headingServicios);

    // Recorremos los servicios:
    servicios.forEach(servicio => {
        const { id, nombre, precio} = servicio;
        const contenedorServicio = document.createElement('DIV');
        contenedorServicio.classList.add('contenedor-servicio');

        const nombreServicio = document.createElement('P');
        nombreServicio.textContent = nombre;

        const precioServicio = document.createElement('P');
        precioServicio.innerHTML = `<span>Precio:</span> $${precio}`;

        contenedorServicio.appendChild(nombreServicio);
        contenedorServicio.appendChild(precioServicio);

        resumen.appendChild(contenedorServicio);
    });

    // Heading para Cita en Resumen
    const headingCita = document.createElement('H3');
    headingCita.textContent = 'Resumen de Cita';
    resumen.appendChild(headingCita);

    const nombreCliente = document.createElement('P');
    nombreCliente.innerHTML = `<span>Nombre:</span> ${nombre}`;

    // Formatear la fecha en español
    const fechaObj = new Date(fecha);
    const mes = fechaObj.getMonth();
    const dia = fechaObj.getDate() + 2;
    const anio = fechaObj.getFullYear();

    const fechaUTC = new Date( Date.UTC(anio, mes, dia) );

    const opciones = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'};
    const fechaFormateada = fechaUTC.toLocaleDateString('es-MX', opciones);

    const fechaCita = document.createElement('P');
    fechaCita.innerHTML = `<span>Fecha:</span> ${fechaFormateada}`;

    const horaCita = document.createElement('P');
    horaCita.innerHTML = `<span>Hora:</span> ${hora} horas`;

    resumen.appendChild(nombreCliente);
    resumen.appendChild(fechaCita);
    resumen.appendChild(horaCita);

    // Boton para crear reservar cita.
    const botonReservar = document.createElement('BUTTON');
    botonReservar.classList.add('boton');
    botonReservar.textContent = 'Reservar Cita';
    botonReservar.onclick = reservarCita;

    resumen.appendChild(botonReservar);
}

/* */
async function reservarCita() {
    const {nombre, fecha, hora, id, servicios} = cita;
    const idServicios = servicios.map(servicio => servicio.id); // Obtiene los id de los servicios.

    // Forma el conjunto de datos que serán enviados a la petición.
    const datos = new FormData();
    datos.append('fecha', fecha);
    datos.append('hora', hora);
    datos.append('usuarioId', id);
    datos.append('servicios', idServicios);
    
    // console.log([...datos]);
    try {
        const url = `${location.origin}/api/citas`;
        // Consulta el API y envia los datos
        const respuesta = await fetch(url, {
            method: 'POST',
            body: datos
        });
        // Obtiene la respuesta del API.
        const resultado = await respuesta.json();
        // Si la respuesta es true...
        if (resultado.resultado) {
            Swal.fire({
                icon: "success",
                title: "Cita Creada.",
                text: "Tu cita ha sido creada correctamente",
                button: "OK"
            }).then( () => {
                window.location.reload();
            });
        }
    } catch (error) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Hubo un error al guardar tu cita. Vuelve a intentarlo.",
        });
    }
}