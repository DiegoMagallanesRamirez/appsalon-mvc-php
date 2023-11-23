<?php

namespace Controller;

use Model\Cita;
use Model\CitaServicio;
use MVC\Router;
use Model\Servicio;

/**
 * 
 */
class APIController {

    /**
     * Función que recupera todos los servicios.
     */
    public static function index() {
        $servicios = Servicio::all();
        echo json_encode($servicios);
    }

    /**
     * Función que se encarga de registrar una cita.
     */
    public static function guardar() {
        // Crea una instancia de Cita con la información que recibe de POST
        $cita = new Cita($_POST);
        // Almacena la cita y devuelve el ID con el que se guardó.
        // Guardar devuelve el id del último registro creado.
        $resultado = $cita->guardar();
        // Recuperamos el id de la Cita recien creada.
        $idCita = $resultado['id']; 
        // Recuperamos los servicios que fueron enviados por POST.
        $idServicios = explode(",", $_POST['servicios']);
        // Por cada servicio solicitado en la cita, creamos un registro en CitasServicio.
        foreach($idServicios as $idServicio) {
            $args = [
                'citaId' => $idCita,
                'servicioId' => $idServicio
            ];
            $citaServicio = new CitaServicio($args);
            $citaServicio->guardar();
        }

        echo json_encode(['resultado' => $resultado]);
    }

    /**
     * 
     */
    public static function eliminar() {
        
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = $_POST['id'];
            $cita = Cita::find($id);
            $cita->eliminar();
            header('Location:' . $_SERVER['HTTP_REFERER']);
        }
    }
}