<?php

// Inicialización del entorno
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include __DIR__ . '/funciones.php';
include __DIR__ . '/index.php';

$fecha = $_GET["FECHA"] ?? null;
$pista = $_GET["PISTA"] ?? null;

// Comprobar si existe un parámetro de búsqueda.
// Si no se ha indicado ni fecha ni pista, mostrar un mensaje
// y detener la ejecución del programa.

function validateQueryString($campo)
{
    if (!isset($_GET[$campo]) || empty($_GET[$campo])) {
        return false;
    }
    return true;
}

// TO DO:
// Corregir la condición para que solo muestre el mensaje
// cuando NO se haya indicado ni FECHA ni PISTA.
// Evitar que el programa continúe si no hay ningún filtro.

if (!validateQueryString("FECHA") || !validateQueryString("PISTA")) {
    echo "Necesitamos minimo un parámetro de fecha o pista para poder filtrar las reservas.";
}

// Filtrar las reservas según los parámetros recibidos.
function filterReservations($reservas, $fecha, $pista)
{
    $filteredReservations = [];

    foreach ($reservas as $reserva) {
        if (($fecha === null || ($reserva['FECHA'] ?? '') === $fecha) &&
            ($pista === null || ($reserva['PISTA'] ?? '') === $pista)
        ) {
            $filteredReservations[] = $reserva;
        }
    }

    return $filteredReservations;
}

/* TO DO:
 1. Comprobar que $reservas contiene las reservas cargadas desde el CSV.
 2. Llamar a filterReservations() pasando las reservas, la fecha y la pista.
 3. Guardar el resultado de la función en una variable.
 4. Recorrer las reservas filtradas con foreach.
 5. Mostrar los campos que quieras enseñar de cada reserva.
 6. Si procede, mostrar un mensaje cuando no se encuentre ninguna reserva.
 7. Comprobar que los archivos incluidos no se están cargando
    innecesariamente o incluyendo a sí mismos. */