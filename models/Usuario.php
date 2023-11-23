<?php

namespace Model;

class Usuario extends ActiveRecord {

    protected static $tabla = 'usuarios';
    protected static $columnasDB = ['id', 'nombre', 'apellido', 'email', 'password', 'telefono', 'admin', 'confirmado', 'token'];

    public $id;
    public $nombre;
    public $apellido;
    public $email;
    public $password;
    public $telefono;
    public $admin;
    public $confirmado;
    public $token;

    public function __construct($args = []) {
        $this->id = $args['id'] ?? null;
        $this->nombre = $args['nombre'] ?? '';
        $this->apellido = $args['apellido'] ?? '';
        $this->email = $args['email'] ?? '';
        $this->password = $args['password'] ?? '';
        $this->telefono = $args['telefono'] ?? '';
        $this->admin = $args['admin'] ?? '0';
        $this->confirmado = $args['confirmado'] ?? '0';
        $this->token = $args['token'] ?? '';
    }

    /**
     * Validad que los datos enviados para crear una cuenta sean correctos
     */
    public function validarNuevaCuenta() {
        if (!$this->nombre) {
            self::$alertas['error'][] = 'El nombre es obligatorio';
        }

        if (!$this->apellido) {
            self::$alertas['error'][] = 'El apellido es obligatorio';
        }

        if (!$this->telefono) {
            self::$alertas['error'][] = 'El teléfono es obligatorio';
        }

        if (!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        if (!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }

        if ( strlen($this->password) < 6) {
            self::$alertas['error'][] = 'El password debe contener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    /**
     * Valida que los datos ingresados para iniciar sesión sean correctos.
     */
    public function validarLogin() {
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        if (!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }

        return self::$alertas;
    }

    /**
     * Valida que el email sea correcto.
     */
    public function validarEmail() {
        if (!$this->email) {
            self::$alertas['error'][] = 'El email es obligatorio';
        }

        return self::$alertas;
    }

    /**
     * Valida que el password sea correcto exista.
     */
    public function validarPassword() {
        if (!$this->password) {
            self::$alertas['error'][] = 'El password es obligatorio';
        }

        if ( strlen($this->password) < 6 ) {
            self::$alertas['error'][] = 'El password debe tener al menos 6 caracteres';
        }

        return self::$alertas;
    }

    /**
     * Valida si un usuario ya se ha registrado.
     */
    public function existeUsuario() {
        $query = "SELECT * FROM " . self::$tabla . " WHERE email = '" . $this->email . "' LIMIT 1";
        $resultado = self::$db->query($query);

        // Si regresa algún resultado, ent. el usuario ya existe.
        if ($resultado->num_rows) {
            self::$alertas['error'][] = 'El usuario ya está registrado';
        }

        return $resultado;
    }

    /**
     * Se encarga de aplicar el hash al password.
     */
    public function hashearPassword() {
        $this->password = password_hash($this->password, PASSWORD_BCRYPT);
    }

    /**
     * Crea un token único para validar la cuenta.
     */
    public function crearToken() {
        $this->token = uniqid();
    }

    /**
     * Valida que el usuario este verificado y su contraseña sea correcta.
     */
    public function comprobarPasswordAndVerificado($password) {
        $resultado = password_verify($password, $this->password);   
        
        if (!$resultado || !$this->confirmado) {
            self::$alertas['error'][] = 'Password Incorrecto o cuenta no confirmada';
        } else {
            return true;
        }
    }
}