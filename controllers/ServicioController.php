<?php

namespace Controller;

use Model\Servicio;
use MVC\Router;

/**
 * Clase encargada de controlar los endpoints de Servicios.
 */
class ServicioController {
    /**
     * Función principal al entrar a la sección de servicios.
     */
    public static function index(Router $router) {
        // Recupera la información de inicio de sesión
        session_start();
        isAdmin();

        // Recuperamos los servicios.
        $servicios = Servicio::all();

        // Renderiza la vista de servicios
        $router->render('/servicios/index', [
            'nombre' => $_SESSION['nombre'],
            'servicios' => $servicios
        ]);
    }

    /**
     * Función encargada de visualizar la vista de crear un servicio.
     */
    public static function crear(Router $router) {
        // Recupera la información de inicio de sesión
        session_start();
        isAdmin();

        // Objeto de tipo servicio para almacenar la información que se recibe del formulario.
        $servicio = new Servicio;

        // Arreglo de alertas
        $alertas = [];
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if ( empty($alertas) ) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }
        
        // Renderiza la vista de crear
        $router->render('/servicios/crear', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    /**
     * Función encargada de visualizar la vista de actualizar un servicio.
     */
    public static function actualizar(Router $router) {
        // Recupera la información de inicio de sesión
        session_start();
        isAdmin();

        // Recuperamos el id del servicio.
        $id = $_GET['id'];

        if ( ! is_numeric($id) ) header('Location: /servicios');

        // Objeto de tipo servicio para almacenar la información que se recibe del formulario.
        $servicio = Servicio::find($id);

        if ( !$servicio ) header('Location: /servicios');

        // Arreglo de alertas
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $servicio->sincronizar($_POST);

            $alertas = $servicio->validar();

            if ( empty($alertas) ) {
                $servicio->guardar();
                header('Location: /servicios');
            }
        }

        // Renderiza la vista de actualizar
        $router->render('/servicios/actualizar', [
            'nombre' => $_SESSION['nombre'],
            'servicio' => $servicio,
            'alertas' => $alertas
        ]);
    }

    /**
     * Función encargada de visualizar la vista de eliminar un servicio.
     */
    public static function eliminar(Router $router) {
        session_start();
        isAdmin();

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];

            if ( !is_numeric($id) ) header('Location: /servicios');

            $servicio = Servicio::find($id);

            if ($servicio) {
                $servicio->eliminar();
                header('Location: /servicios');
            }
        }
    }
}