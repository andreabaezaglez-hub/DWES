<?php

// Inicialización del entorno
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include(__DIR__ . '/funciones.php');

// Número de reserva recibido por la URL
$numero = campoTexto($_GET, 'n');

$reservaEncontrada = null;

// Comprobar el número y buscar la reserva en MySQL
if (ctype_digit($numero) && (int)$numero > 0) {

    // Conexión a la base de datos
    $connexion = new mysqli(
        'localhost',
        'root',
        '',
        'reservas_deportivas'
    );

    if ($connexion->connect_error) {
        die('Error de conexión: ' . $connexion->connect_error);
    }

    $connexion->set_charset('utf8mb4');

    // Buscar la reserva por su ID
    $sql = "
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
        WHERE id = ?
        LIMIT 1
    ";

    $stmt = $connexion->prepare($sql);

    if ($stmt === false) {
        $connexion->close();
        die('Error al preparar la consulta de reserva.');
    }

    $idReserva = (int)$numero;

    $stmt->bind_param('i', $idReserva);

    if (!$stmt->execute()) {
        $stmt->close();
        $connexion->close();
        die('Error al consultar la reserva.');
    }

    $resultado = $stmt->get_result();

    if ($resultado !== false) {
        $reservaEncontrada = $resultado->fetch_assoc();
    }

    $stmt->close();
    $connexion->close();
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

    <link
        rel="stylesheet"
        href="https://unpkg.com/simpledotcss/simple.css">
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