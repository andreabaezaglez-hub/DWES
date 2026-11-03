<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();


include(__DIR__ . '/src/functions.php');


$board = getBoardFromCSV(__DIR__ . '/src/board_data/board1.csv');

$num_rows = count($board);
$num_columns = count($board[0]);

// Recuperar la posición de Link
$x = $_SESSION['x'] ?? 1;
$y = $_SESSION['y'] ?? 1;


// Comprobamo si se ha pulsado una dirección
if (isset($_GET['direccion'])) {

    if ($_GET['direccion'] == 'arriba') {
        $y--;
    } elseif ($_GET['direccion'] == 'abajo') {
        $y++;
    } elseif ($_GET['direccion'] == 'izquierda') {
        $x--;
    } elseif ($_GET['direccion'] == 'derecha') {
        $x++;
    }


    // -------------------ESTO NO LO ENTIENDOOOOOOO -----------------------------
    // Comprobar que Link no salga del tablero
    if (
        $x >= 0 && $x < $num_columns &&
        $y >= 0 && $y < $num_rows
    ) {

        $_SESSION['x'] = $x;
        $_SESSION['y'] = $y;
    } else {

        // Recuperar la posición anterior
        $x = $_SESSION['x'] ?? 1;
        $y = $_SESSION['y'] ?? 1;
    }

    // Evitar repetir el movimiento al actualizar
    header('Location: index.php');
    exit;
}

// Guardar la posición inicial si todavía no existe
$_SESSION['x'] = $x;
$_SESSION['y'] = $y;


$board_markup = getBoardMarkup($board, $x, $y);


include(__DIR__ . '/templates/index.tpl.php');
