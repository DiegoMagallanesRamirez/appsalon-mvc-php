<?php

namespace Controller;

use MVC\Router;

/**
 * Clase encargada de controlar las citas y servicios.
 */
class CitaController {

    /**
     * 
     */
    public static function index(Router $router) {
        // Recuperamos la sesión iniciada.
        session_start();

        // Valida que el usuario haya iniciado sesión.
        isAuth();

        $nombre = $_SESSION['nombre'];
        $id = $_SESSION['id'];

        $router->render('/cita/index', [
            'nombre' => $nombre,
            'id' => $id
        ]);
    }
}