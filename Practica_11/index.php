<?php

// Inicialización del entorno

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include(__DIR__ . '/funciones.php');

// Conexion a base de datos
$conexion = new mysqli("localhost", "root", "", "reservas_deportivas");

// Check conexion
if ($conexion->connect_error) {
    die("Conexión fallida:" . $conexion->connect_error);
}
echo "Conexión exitosa.<br><br>";

// Set character set to UTF-8
$conexion->set_charset("utf8mb4");

// Pistas disponibles
$pistas = ['Pádel 1', 'Pádel 2', 'Tenis', 'Baloncesto'];

// Encabezados de la tabla
$encabezados = [
    'ID',
    'NOMBRE',
    'EMAIL',
    'PISTA',
    'FECHA',
    'HORA',
    'MATERIAL',
    'NORMAS'
];

// Inicializar los arrays
$reservas = [];
$reservasFiltradas = [];

// Leer los filtros de la URL
$pistaSeleccionada = campoTexto($_GET, 'pista');
$fechaSeleccionada = campoTexto($_GET, 'fecha');

// Leer las reservas
$sql_select = "
    SELECT
        id AS ID,
        nombre AS NOMBRE,
        email AS EMAIL,
        pista AS PISTA,
        fecha AS FECHA,
        TIME_FORMAT(hora, '%H:%i') AS HORA,
        material AS MATERIAL,
        IF(normas = 1, 'Sí', 'No') AS NORMAS
    FROM reservas
    ORDER BY fecha, hora
";

$result = $conexion->query($sql_select);

if ($result === false) {
    die("Error en la consulta: " . $conexion->error);
}

// Guardar los resultados en un array
while ($row = $result->fetch_assoc()) {
    $reservas[] = $row;
}

$result->free();



// Filtrar las reservas
/*foreach ($reservas as $reserva) {

    $coincidePista = (
        $pistaSeleccionada === ''
        || $reserva['PISTA'] === $pistaSeleccionada
    );

    $coincideFecha = (
        $fechaSeleccionada === ''
        || $reserva['FECHA'] === $fechaSeleccionada
    );

    if ($coincidePista && $coincideFecha) {
        $reservasFiltradas[] = $reserva;
    }
}*/

// Filtrar las reservas mediante funciones.php
$reservasFiltradas = filterReservations($reservas, $_GET);


$conexion->close();

// Cargar la plantilla HTML
require __DIR__ . '/index.tpl.php';


?>

