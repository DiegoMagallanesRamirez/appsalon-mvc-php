<?php

function debuguear($variable) : string {
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
    exit;
}

// Escapa / Sanitizar el HTML
function s($html) : string {
    $s = htmlspecialchars($html);
    return $s;
}

// Función que revisa que el usuario este autenticado.
function isAuth() : void {
    if (!isset($_SESSION['login'])) {
        header('Location: /');
    }
}

// Función que revisa si el que inicio sesión fue un admin.
function isAdmin() : void {
    if ( !isset($_SESSION['admin']) ) {
        header('Location: /');
    }
}