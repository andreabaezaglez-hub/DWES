<?php
/**
 * Variables recibidas desde reservar.php
 *
 * @var array $pistas
 * @var array $horas
 * @var array $materiales
 * @var array $material
 */
?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Reservar pista deportiva</title>

    <link
        rel="stylesheet"
        href="https://unpkg.com/simpledotcss/simple.css">
</head>

<body>

    <h1>Reservar una pista deportiva</h1>

    <p><a href="index.php">Volver al listado</a></p>

    <?php if (!empty($errores)): ?>
        <p>Hay errores en el formulario. Revísalos antes de continuar.</p>
    <?php endif; ?>

    <?php if (isset($errores['general'])): ?>
        <p><?= e($errores['general']) ?></p>
    <?php endif; ?>

    <form action="reservar.php" method="POST">

        <p>
            <label for="nombre">Nombre:</label>

            <input
                type="text"
                name="nombre"
                id="nombre"
                minlength="2"
                maxlength="60"
                required
                value="<?= e($nombre) ?>">

            <?php if (isset($errores['nombre'])): ?>
                <span><?= e($errores['nombre']) ?></span>
            <?php endif; ?>
        </p>

        <p>
            <label for="email">Email:</label>

            <input
                type="email"
                name="email"
                id="email"
                required
                value="<?= e($email) ?>">

            <?php if (isset($errores['email'])): ?>
                <span><?= e($errores['email']) ?></span>
            <?php endif; ?>
        </p>

        <p>
            <label for="pista">Pista:</label>

            <select name="pista" id="pista" required>
                <option value="">Selecciona una pista</option>

                <?php foreach ($pistas as $opcion): ?>
                    <option
                        value="<?= e($opcion) ?>"
                        <?= $pista === $opcion ? 'selected' : '' ?>>
                        <?= e($opcion) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <?php if (isset($errores['pista'])): ?>
                <span><?= e($errores['pista']) ?></span>
            <?php endif; ?>
        </p>

        <p>
            <label for="fecha">Fecha:</label>

            <input
                type="date"
                name="fecha"
                id="fecha"
                min="<?= e(date('Y-m-d')) ?>"
                max="<?= e(date('Y-m-d', strtotime('+14 days'))) ?>"
                required
                value="<?= e($fecha) ?>">

            <?php if (isset($errores['fecha'])): ?>
                <span><?= e($errores['fecha']) ?></span>
            <?php endif; ?>
        </p>

        <fieldset>
            <legend>Hora:</legend>

            <?php foreach ($horas as $opcion): ?>
                <label>
                    <input
                        type="radio"
                        name="hora"
                        value="<?= e($opcion) ?>"
                        <?= $hora === $opcion ? 'checked' : '' ?>
                        required>

                    <?= e($opcion) ?>
                </label>
            <?php endforeach; ?>

            <?php if (isset($errores['hora'])): ?>
                <p><?= e($errores['hora']) ?></p>
            <?php endif; ?>
        </fieldset>

        <fieldset>
            <legend>Material (opcional):</legend>

            <?php foreach ($materiales as $opcion): ?>
                <label>
                    <input
                        type="checkbox"
                        name="material[]"
                        value="<?= e($opcion) ?>"
                        <?= in_array($opcion, $material, true)
                            ? 'checked'
                            : '' ?>>

                    <?= e($opcion) ?>
                </label>
            <?php endforeach; ?>

            <?php if (isset($errores['material'])): ?>
                <p><?= e($errores['material']) ?></p>
            <?php endif; ?>
        </fieldset>

        <p>
            <label>
                <input
                    type="checkbox"
                    name="normas"
                    value="1"
                    <?= $normas ? 'checked' : '' ?>
                    required>

                Acepto las normas del polideportivo.
            </label>

            <?php if (isset($errores['normas'])): ?>
                <span><?= e($errores['normas']) ?></span>
            <?php endif; ?>
        </p>

        <button type="submit">Reservar</button>

    </form>

</body>
</html>