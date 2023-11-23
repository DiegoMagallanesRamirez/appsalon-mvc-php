<?php

namespace Controller;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

/**
 * Clase encargadad de controlar la página principal.
 */
class LoginController {

    /**
     * Renderiza y/o procesa los datos enviados desde la página principal para }
     * el inicio de sesión.
     */
    public static function login(Router $router) {
        $alertas = [];
        $auth = new Usuario;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarLogin();

            // Si pasa la validación
            if ( empty($alertas) ) {
                // Buscamos si existe el usuario con el correo.
                $usuario = Usuario::where('email', $auth->email);

                // Si el correo existe.
                if ($usuario) {
                    // Validar que el usuario este confirmado.
                    // Verificar el password del usuario.
                    if ( $usuario->comprobarPasswordAndVerificado($auth->password) ) {
                        // Iniciamos sesión:
                        session_start();

                        $_SESSION['id'] = $usuario->id;
                        $_SESSION['nombre'] = $usuario->nombre . ' ' . $usuario->apellido;
                        $_SESSION['email'] = $usuario->email;
                        $_SESSION['login'] = true;

                        // Redireccionamos dependiendo si es administrador o cliente.
                        if ($usuario->admin === '1') {
                            $_SESSION['admin'] = $usuario->admin ?? null;
                            header('Location: /admin');
                        } else {
                            header('Location: /cita');
                        }
                    }
                } else {
                    // Indicamos que el usuario no existe.
                    Usuario::setAlerta('error', 'Usuario no encontrado');
                    $alertas = Usuario::getAlertas();
                }
            }
        }

        $router->render('/auth/login', [
            'alertas' => $alertas,
            'auth' => $auth
        ]);
    }

    /**
     * Renderiza y/o procesa los datos enviados para el cierre de sesión.
     */
    public static function logout() {
        // Recuperamos la sesión actual y eliminamos su contenido.
        session_start();
        $_SESSION = [];
        header('Location: /');
    }

    /**
     * Renderiza y/o procesa los datos enviados para notificar que olvidó su pwd.
     */
    public static function olvide(Router $router) {
        $alertas = [];

        // Si envia el formulario recuperamos los datos enviados.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $auth = new Usuario($_POST);
            $alertas = $auth->validarEmail();

            // Si el formulario no está vacio.
            if ( empty($alertas) ) {
                $usuario = Usuario::where('email', $auth->email);

                // Si el correo existe...
                if ( $usuario && $usuario->confirmado === '1' ) {
                    // Generar un token
                    $usuario->crearToken();
                    $usuario->guardar();

                    // Enviar email.
                    $mail = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $mail->enviarInstrucciones();

                    // Alerta de éxito
                    Usuario::setAlerta('exito', 'Hemos enviado un correo a tu email para restaurar tu password');
                } else {
                    Usuario::setAlerta('error', 'Usuario no encontrado o no confirmado');
                }
            }
        }

        $alertas = Usuario::getAlertas();

        $router->render('/auth/olvide-password', [
            'alertas' => $alertas
        ]);
    }

    /**
     * Renderiza y/o procesa los datos enviados para recuperar su contraseña.
     */
    public static function reestablecer(Router $router) {
        $alertas = [];
        $token = $_GET['token'];
        $usuario = Usuario::where('token', $token);
        $error = false;

        // Si no existe el token dentro de la BD.
        if ( empty($usuario) ) {
            Usuario::setAlerta('error', 'Token no válido');
            $error = true;
        }

        // Recibe la nueva contraseña del usuario.
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $password = new Usuario($_POST);
            $alertas = $password->validarPassword();

            // Si no hay errores
            if ( empty($alertas) ) {
                $usuario->password = null;
                $usuario->password = $password->password;
                $usuario->hashearPassword();
                $usuario->token = null;
                $resultado = $usuario->guardar();

                // Si se actualizó correctamente ...
                if ($resultado) {
                    header('Location: /');
                }
            }
        }
        
        $alertas = Usuario::getAlertas();

        $router->render('/auth/reestablecer-password', [
            'alertas' => $alertas,
            'error' => $error
        ]);
    }

    /**
     * Renderiza y/o procesa los datos crear una cuenta.
     */
    public static function crear(Router $router) {
        // Nueva instancia de Usuario
        $usuario = new Usuario;
        // Arreglo de alertas vacía.
        $alertas = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $usuario->sincronizar($_POST);
            $alertas = $usuario->validarNuevaCuenta();

            // Validar que las alertas esten vacías:
            if ( empty($alertas) ) {
                // Validar que el usuario no este registrado.
                $resultado = $usuario->existeUsuario();

                // Recuperamos las alertas si el usuario ya existe:
                if ($resultado->num_rows) {
                    $alertas = Usuario::getAlertas();
                } else {
                    // El usuario NO está registrado.
                    // Hashear el password:
                    $usuario->hashearPassword();

                    // Crear Token único:
                    $usuario->crearToken();

                    // Enviar correo con token de validación:
                    $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
                    $email->enviarConfirmacion();

                    // Crear el usuario en la BD.
                    $resultado = $usuario->guardar();

                    // Si el usuario se registro correctamente se redirige a la pagina mensaje.
                    if ($resultado) {
                        header('Location: /mensaje');
                    }
                }
            }
        }

        // Renderizamos la vista.
        $router->render('/auth/crear-cuenta', [
            'usuario' => $usuario,
            'alertas' => $alertas
        ]);
    }

    /**
     * 
     */
    public static function confirmar(Router $router) {
        
        $alertas = [];
        $token = s($_GET['token']);

        $usuario = Usuario::where('token', $token);

        // Si no existe usuario que coincida con el token.
        if ( empty($usuario) ) {
            // Mostrar mensaje de error.
            Usuario::setAlerta('error', 'Token No Valido');
        } else {
            // Actualizamos el estado del usuario en la BD
            $usuario->confirmado = '1';
            $usuario->token = null;
            $usuario->guardar();

            Usuario::setAlerta('exito', 'Cuenta Comprobada Correctamente');
        }

        $alertas = Usuario::getAlertas();

        $router->render('/auth/confirmar-cuenta', [
            'alertas' => $alertas
        ]);
    }

    /**
     * 
     */
    public static function mensaje(Router $router) {
        $router->render('/auth/mensaje');
    }
}