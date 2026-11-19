<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Vaciar los datos de la sesión
$_SESSION = [];

// Obtener los datos de la cookie
$parametros = session_get_cookie_params();

// Borrar la cookie del navegador
setcookie(
    session_name(),
    '',
    time() - 42000,
    $parametros['path'],
    $parametros['domain'],
    $parametros['secure'],
    $parametros['httponly']
);

// Destruir la sesión
session_destroy();

// Volver al login
header('Location: login.php');
exit;
