<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include(__DIR__.'/src/functions.php');

$board = getBoardFromCSV(__DIR__.'/src/board_data/board1.csv');

$num_rows = count($board);
$num_columns = count($board[0]);

// Inicializar posición por defecto en (3,3) (o la que prefieras)
if (!isset($_SESSION['link_pos'])) {
    $_SESSION['link_pos'] = ['x' => 3, 'y' => 3];
}

// Procesar movimiento desde los parámetros GET
if (isset($_GET['x'], $_GET['y'])) {
    $target_x = (int)$_GET['x'];
    $target_y = (int)$_GET['y'];

    // 1. Validar estrictamente que Link no salga de los límites del tablero
    if ($target_x >= 0 && $target_x < $num_columns && $target_y >= 0 && $target_y < $num_rows) {
        
        // 2. Obtener la casilla de destino
        $tile_destino = $board[$target_y][$target_x];
        
        // 3. Comprobar si es un obstáculo
        $esta_bloqueado = (
            str_contains($tile_destino, 'wall') || 
            str_contains($tile_destino, 'water') || 
            str_contains($tile_destino, 'boulder') || 
            str_contains($tile_destino, 'rock') ||
            str_contains($tile_destino, 'door')
        );

        // 4. ¡SOLO SE MUEVE SI NO ESTÁ BLOQUEADO!
        if (!$esta_bloqueado) {
            $_SESSION['link_pos']['x'] = $target_x;
            $_SESSION['link_pos']['y'] = $target_y;
        }
    }
}

$link_pos = $_SESSION['link_pos'];

// Generar marcado
$board_markup = getBoardMarkup($board, $link_pos['x'], $link_pos['y']);

// Cargar plantilla
include(__DIR__.'/templates/index.tpl.php');