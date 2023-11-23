<?php

namespace Controller;

use Model\AdminCita;
use MVC\Router;

/**
 * Clase encargada de controlar las acciones del administrador.
 */

class AdminController {

    public static function index(Router $router) {
        session_start();    // Recuperamos la sesión actual para obtener los datos.

        isAdmin();

        // Se define la zona horaria por defecto.
        date_default_timezone_set('America/Mexico_City');

        // Obtiene la fecha que viene en el GET y si no viene nada recupera la fch del servidor.
        $fecha = $_GET['fecha'] ?? date('Y-m-d');
        $fch_exp = explode('-', $fecha);

        if ( ! checkdate($fch_exp[1], $fch_exp[2], $fch_exp[0]) ) {
            header('Location: /404');
        }

        // Consulta para obtener las citas.
        $consulta = "SELECT citas.id, citas.hora, CONCAT( usuarios.nombre, ' ', usuarios.apellido) as cliente, ";
        $consulta .= " usuarios.email, usuarios.telefono, servicios.nombre as servicio, servicios.precio  ";
        $consulta .= " FROM citas  ";
        $consulta .= " LEFT OUTER JOIN usuarios ";
        $consulta .= " ON citas.usuarioId=usuarios.id  ";
        $consulta .= " LEFT OUTER JOIN citasServicios ";
        $consulta .= " ON citasServicios.citaId=citas.id ";
        $consulta .= " LEFT OUTER JOIN servicios ";
        $consulta .= " ON servicios.id=citasServicios.servicioId ";
        $consulta .= " WHERE fecha =  '$fecha' ";

        // Ejecuta la consulta y almacena en memoria las citas.
        $citas = AdminCita::SQL($consulta);

        $router->render('/admin/index', [
            'nombre' => $_SESSION['nombre'],
            'citas' => $citas,
            'fecha' => $fecha
        ]);
    }
}