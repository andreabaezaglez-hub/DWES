<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();


include(__DIR__.'/src/functions.php');


$board = getBoardFromCSV(__DIR__.'/src/board_data/board1.csv');

echo session_id();
$_SESSION['usuario'] = ['id' =>  7, 'nombre' => 'Andrea'];
$_SESSION['visitas'] = ($_SESSION['visitas'] ?? 0) + 1;

//$_SESSION['clave'] = $valor;
$valor = $_SESSION['clave'] ?? 'por defecto';
isset($_SESSION['clave']);
unset($_SESSION['clave']);

if(isset($_SESSION['usuario'])){
    echo 'Hola, ' . htmlspecialchars($_SESSION['usuario']['nombre']);
}

echo 'Has visitado esta página ' . htmlspecialchars($_SESSION['visitas']) . 'veces';

$num_rows = count($board);
$num_columns = count($board[0]);

//$link_pos = getLinkPosFromQS($num_rows,$num_columns);

$board_markup = getBoardMarkup($board);

include(__DIR__.'/templates/index.tpl.php');

?>

<p>Has visitado esta página <?=  $_SESSION['visitas'] ?> veces</p>