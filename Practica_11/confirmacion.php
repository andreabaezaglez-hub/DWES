<?php

// Inicialización del entorno
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/funciones.php';

// Número de reserva recibido por la URL
$numero = campoTexto($_GET, 'n');

$reservaEncontrada = null;

// Comprobar el número
if (ctype_digit($numero) && (int)$numero > 0) {

    $ruta = __DIR__ . '/datos/reservas.csv';

    // Leer las reservas
    $reservas = leerReservas($ruta);

    // Buscar la reserva por su identificador
    foreach ($reservas as $reserva) {
        if ((string)($reserva['ID'] ?? '') === $numero) {
            $reservaEncontrada = $reserva;
            break;
        }
    }
}

// Si no existe, devolver un 404
if ($reservaEncontrada === null) {
    http_response_code(404);
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmación de reserva</title>
    <link rel="stylesheet" href="https://unpkg.com/simpledotcss/simple.css">
</head>

<body>

    <?php if ($reservaEncontrada === null): ?>

        <h1>Reserva no encontrada</h1>

        <p>No existe una reserva con ese número.</p>

        <p><a href="index.php">Volver al listado</a></p>

    <?php else: ?>

        <h1>Reserva confirmada</h1>

        <p>Estos son los datos de tu reserva:</p>

        <table>
            <tr>
                <th>Número de reserva</th>
                <td><?= e($reservaEncontrada['ID'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Nombre</th>
                <td><?= e($reservaEncontrada['NOMBRE'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Email</th>
                <td><?= e($reservaEncontrada['EMAIL'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Pista</th>
                <td><?= e($reservaEncontrada['PISTA'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Fecha</th>
                <td><?= e($reservaEncontrada['FECHA'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Hora</th>
                <td><?= e($reservaEncontrada['HORA'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Material</th>
                <td><?= e($reservaEncontrada['MATERIAL'] ?? '') ?></td>
            </tr>
            <tr>
                <th>Normas aceptadas</th>
                <td><?= e($reservaEncontrada['NORMAS'] ?? '') ?></td>
            </tr>
        </table>

        <p><a href="index.php">Volver al listado</a></p>

        <p><a href="reservar.php">Hacer otra reserva</a></p>

    <?php endif; ?>

</body>
</html>