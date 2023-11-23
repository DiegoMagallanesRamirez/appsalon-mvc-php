<?php

namespace Classes;

use PHPMailer\PHPMailer\PHPMailer;

/**
 * Clase para enviar correos electrónicos.
 */
class Email {

    public $email;
    public $nombre;
    public $token;

    /**
     * Construye un objeto de tipo Email.
     */
    public function __construct($email, $nombre, $token) {
        $this->email = $email;
        $this->nombre = $nombre;
        $this->token = $token;
    }

    /**
     * Crea y envía un email de confirmación.
     */
    public function enviarConfirmacion() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Confirma tu cuenta';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
    
        $contenido = "<html>";
        $contenido .= "<p>Hola <strong> " . $this->nombre . "</strong>. Has creado tu cuenta en App Salon, solo debes confirmarla presionando el siguiente enlace</p>";
        $contenido .= "<p>Haz clic aquí: <a href='" . $_ENV['APP_URL'] . "/confirmar-cuenta?token=" . $this->token ."'>Confirmar cuenta</a></p>";
        $contenido .= "<p>Si tú no solicitaste esta cuenta, puedes ignorar el mensaje.</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        $mail->send();
    }

    /**
     * Crea y envía un email con las instrucciones de restauración de password.
     */
    public function enviarInstrucciones() {
        $mail = new PHPMailer();
        $mail->isSMTP();
        $mail->Host = $_ENV['EMAIL_HOST'];
        $mail->SMTPAuth = true;
        $mail->Port = $_ENV['EMAIL_PORT'];
        $mail->Username = $_ENV['EMAIL_USER'];
        $mail->Password = $_ENV['EMAIL_PASS'];

        $mail->setFrom('cuentas@appsalon.com');
        $mail->addAddress('cuentas@appsalon.com', 'AppSalon.com');
        $mail->Subject = 'Reestablece tu password';

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';
    
        $contenido = "<html>";
        $contenido .= "<p>Hola <strong> " . $this->nombre . "</strong>. Has solicitado reestablecer tu password, sigue el siguiente enlace para hacerlo.</p>";
        $contenido .= "<p>Haz clic aquí: <a href='" . $_ENV['APP_URL'] . "/reestablecer?token=" . $this->token ."'>Reestablecer Password</a></p>";
        $contenido .= "<p>Si tú no solicitaste esta cuenta, puedes ignorar el mensaje.</p>";
        $contenido .= "</html>";

        $mail->Body = $contenido;

        $mail->send();
    }
}