<?php 

require_once __DIR__ . '/../includes/app.php';

use Controller\AdminController;
use Controller\APIController;
use Controller\CitaController;
use Controller\LoginController;
use Controller\ServicioController;
use MVC\Router;

$router = new Router();

// Inicio y cierre de sesión
$router->get('/', [LoginController::class, 'login']);           // Página principal
$router->post('/', [LoginController::class, 'login']);          // Página principal - Envio de Formulario

$router->get('/logout', [LoginController::class, 'logout']);    // Cierre de sesión

// Recuperar password
$router->get('/olvide', [LoginController::class, 'olvide']);    // Página para notificar que olvidó el pwd.
$router->post('/olvide', [LoginController::class, 'olvide']);   // Página para notificar que olvidó el pwd - Envio de formulario

$router->get('/reestablecer', [LoginController::class, 'reestablecer']);    // Página para recuperar pwd.
$router->post('/reestablecer', [LoginController::class, 'reestablecer']);   // Página para recuperar pwd - Envio de formulario

// Crear cuenta
$router->get('/crear-cuenta', [LoginController::class, 'crear']);    // Página para crear cuenta.
$router->post('/crear-cuenta', [LoginController::class, 'crear']);   // Página para crear cuenta - Envio de formulario

// Confirmar cuenta
$router->get('/confirmar-cuenta', [LoginController::class, 'confirmar']);

// Mensaje de Confirmación
$router->get('/mensaje', [LoginController::class, 'mensaje']);


// ÁREA PRIVADA: Necesita de haber iniciado sesión
$router->get('/cita', [CitaController::class, 'index']);
$router->get('/admin', [AdminController::class, 'index']);

// API de Citas
$router->get('/api/servicios', [APIController::class, 'index']);
$router->post('/api/citas', [APIController::class, 'guardar']);
$router->post('/api/eliminar', [APIController::class, 'eliminar']);

// CRUD de Servicios
$router->get('/servicios', [ServicioController::class, 'index']);
$router->get('/servicios/crear', [ServicioController::class, 'crear']);
$router->post('/servicios/crear', [ServicioController::class, 'crear']);
$router->get('/servicios/actualizar', [ServicioController::class, 'actualizar']);
$router->post('/servicios/actualizar', [ServicioController::class, 'actualizar']);
$router->post('/servicios/eliminar', [ServicioController::class, 'eliminar']);


// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();