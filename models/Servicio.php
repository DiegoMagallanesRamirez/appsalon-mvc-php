<?php

namespace Model;

/**
 * 
 */
class Servicio extends ActiveRecord {

    protected static $tabla = 'servicios';
    protected static $columnasDB = ['id', 'nombre', 'precio'];

    public $id;
    public $nombre;
    public $precio;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->precio = $args['precio'] ?? '';
    }

    /**
     * Valida que la información del servicio sea valida.
     */
    public function validar() {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El Nombre del Servicio es obligatorio';
        }

        if (!$this->precio) {
            self::$alertas['error'][] = 'El Precio del Servicio es obligatorio';
        }

        if (!is_numeric($this->precio)) {
            self::$alertas['error'][] = 'El Precio del Servicio no es válido';
        }

        return self::$alertas;
    }
}