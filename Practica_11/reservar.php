<?php

// Inicialización del entorno
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include(__DIR__ . '/funciones.php');

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

// Opciones del formulario
$pistas = ['Pádel 1', 'Pádel 2', 'Tenis', 'Baloncesto'];

$horas = ['16:00', '17:00', '18:00', '19:00', '20:00', '21:00'];

$materiales = [
    'raquetas',
    'balón',
    'petos',
    'conos',
    'pelotas',
    'palas'
];

// Valores iniciales del formulario
$nombre = '';
$email = '';
$pista = '';
$fecha = '';
$hora = '';
$material = [];
$normas = false;

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Recoger los datos del formulario
    $nombre = campoTexto($_POST, 'nombre');
    $email = campoTexto($_POST, 'email');
    $pista = campoTexto($_POST, 'pista');
    $fecha = campoTexto($_POST, 'fecha');
    $hora = campoTexto($_POST, 'hora');
    $material = campoLista($_POST, 'material');
    $normas = campoTexto($_POST, 'normas') === '1';

    // Agrupar los datos
    $datos = [
        'nombre' => $nombre,
        'email' => $email,
        'pista' => $pista,
        'fecha' => $fecha,
        'hora' => $hora,
        'material' => $material,
        'normas' => $normas
    ];

    // Validar los datos usando MySQL
    $valido = validarReserva(
        $datos,
        $pistas,
        $horas,
        $materiales,
        $connexion,
        $errores
    );

    if (!$valido) {
        http_response_code(422);

    } else {

        // Convertir los materiales a texto para MySQL.
        // Si no hay materiales, guardar NULL.
        $materialTexto = empty($material)
            ? null
            : implode(';', $material);

        // Convertir las normas a 1 o 0
        $normasAceptadas = $normas ? 1 : 0;

        // Insertar la reserva.
        // El ID lo genera MySQL automáticamente.
        $sql = "
            INSERT INTO reservas
                (nombre, email, pista, fecha, hora, material, normas)
            VALUES (?, ?, ?, ?, ?, ?, ?)
        ";

        $stmt = $connexion->prepare($sql);

        if ($stmt === false) {
            $errores['general'] =
                'No se pudo preparar la inserción de la reserva.';
            http_response_code(500);

        } else {

            $stmt->bind_param(
                'ssssssi',
                $nombre,
                $email,
                $pista,
                $fecha,
                $hora,
                $materialTexto,
                $normasAceptadas
            );

            if ($stmt->execute()) {

                // Obtener el ID generado por MySQL
                $numero = $connexion->insert_id;

                $stmt->close();
                $connexion->close();

                // Redirigir a la confirmación
                header(
                    'Location: confirmacion.php?n=' . $numero,
                    true,
                    303
                );
                exit;

            } else {

                $errorMySQL = $stmt->error;

                $stmt->close();

                // Registrar el error sin mostrar detalles de la BD al usuario
                error_log('Error al guardar la reserva: ' . $errorMySQL);

                $errores['general'] =
                    'No se ha podido guardar la reserva. Inténtalo de nuevo.';

                http_response_code(500);
            }
        }
    }
}

$connexion->close();

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