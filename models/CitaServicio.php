<?php

namespace Model;

/**
 * Modelo para la tabla CitasServicios de la BD.
 */
class CitaServicio extends ActiveRecord {

    protected static $tabla = 'citasservicios';
    protected static $columnasDB = ['id', 'citaId', 'servicioId'];

    public $id;
    public $citaId;
    public $servicioId;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->citaId = $args['citaId'] ?? '';
        $this->servicioId = $args['servicioId'] ?? '';
    }
}