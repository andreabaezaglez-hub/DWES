<?php

// Inicialización del entorno
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/funciones.php';

// Ruta del archivo CSV
$ruta = __DIR__ . '/datos/reservas.csv';

// Pistas disponibles
$pistas = ['Pádel 1', 'Pádel 2', 'Tenis', 'Baloncesto'];

// Leer los filtros de la URL
$pistaSeleccionada = campoTexto($_GET, 'pista');
$fechaSeleccionada = campoTexto($_GET, 'fecha');

// Leer las reservas
$reservas = leerReservas($ruta);

$reservasFiltradas = [];

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

// Filtrar las reservas
foreach ($reservas as $reserva) {

    $coincidePista = (
        $pistaSeleccionada === ''
        || ($reserva['PISTA'] ?? '') === $pistaSeleccionada
    );

    $coincideFecha = (
        $fechaSeleccionada === ''
        || ($reserva['FECHA'] ?? '') === $fechaSeleccionada
    );

    if ($coincidePista && $coincideFecha) {
        $reservasFiltradas[] = $reserva;
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas del polideportivo</title>
    <link rel="stylesheet" href="https://unpkg.com/simpledotcss/simple.css">
</head>

<body>

    <h1>Reservas del polideportivo</h1>

    <p><a href="reservar.php">Hacer una reserva</a></p>

    <form method="GET" action="index.php">

        <label for="pista">Filtrar por pista:</label>

        <select name="pista" id="pista">
            <option value="">Todas las pistas</option>

            <?php foreach ($pistas as $opcion): ?>
                <option
                    value="<?= e($opcion) ?>"
                    <?= $pistaSeleccionada === $opcion ? 'selected' : '' ?>>
                    <?= e($opcion) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <label for="fecha">Filtrar por fecha:</label>

        <input
            type="date"
            name="fecha"
            id="fecha"
            value="<?= e($fechaSeleccionada) ?>">

        <button type="submit">Filtrar</button>

        <a href="index.php">Limpiar filtros</a>

    </form>

    <?php if (empty($reservasFiltradas)): ?>

        <p>No hay reservas</p>

    <?php else: ?>

        <table>
            <thead>
                <tr>
                    <?php foreach ($encabezados as $encabezado): ?>
                        <th><?= e($encabezado) ?></th>
                    <?php endforeach; ?>
                </tr>
            </thead>

            <tbody>
                <?php foreach ($reservasFiltradas as $reserva): ?>
                    <tr>
                        <?php foreach ($encabezados as $encabezado): ?>
                            <td><?= e($reserva[$encabezado] ?? '') ?></td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</body>
</html>