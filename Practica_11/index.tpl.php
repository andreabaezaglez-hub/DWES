<?php

/**
 * Variables recibidas desde index.php
 *
 * @var array $pistas
 * @var string $pistaSeleccionada
 * @var string $fechaSeleccionada
 * @var array $encabezados
 * @var array $reservasFiltradas
 */

?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reservas del polideportivo</title>

    <link
        rel="stylesheet"
        href="https://unpkg.com/simpledotcss/simple.css">
</head>

<body>

    <h1>Reservas del polideportivo</h1>

    <p>
        <a href="reservar.php">Hacer una reserva</a>
    </p>

    <form method="GET" action="index.php">

        <label for="pista">Filtrar por pista:</label>

        <select name="pista" id="pista">
            <option value="">Todas las pistas</option>

            <?php foreach ($pistas as $opcion): ?>
                <option
                    value="<?= e($opcion) ?>"
                    <?= $pistaSeleccionada === $opcion
                        ? 'selected'
                        : '' ?>>
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

        <p>No hay reservas para los filtros seleccionados.</p>

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
                            <td>
                                <?= e($reserva[$encabezado] ?? '') ?>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

    <?php endif; ?>

</body>

</html>
