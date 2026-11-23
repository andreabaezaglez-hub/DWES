<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* ***** INICIALIZACIÓN DEL ENTORNO ***** */

session_start();

require_once 'lib/funciones.php';

if (!isset($_SESSION['tablero'])) {
    $_SESSION['tablero'] = iniciarTablero();
}




/* ***** LÓGICA DE NEGOCIO ***** */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['accion']) && $_POST['accion'] == 'pintar') {

        $fila = $_POST['fila'];
        $columna = $_POST['columna'];
        $color = $_POST['color'];

        $_SESSION['tablero'] = pintarTablero(
            $_SESSION['tablero'],
            $fila,
            $columna,
            $color
        );

        $mensaje = "Casilla ($fila, $columna) pintada de " . PALETA[$color] . ".";
    }
}


/* ***** LÓGICA DE PRESENTACIÓN ***** */

$tablero = $_SESSION['tablero'];

include 'templates/index.tpl.php';
