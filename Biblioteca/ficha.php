<?php

require_once "functions.php";

$pdo = connectDB();

$id = $_GET['id'] ?? null;

$id = filter_var($id, FILTER_VALIDATE_INT);

$libro = obtenerLibro($pdo, $id);


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ficha del libro</title>
</head>

<body>

    <h1>Ficha del libro</h1>

    <?php if ($libro === false) { ?>

        <p>Libro no encontrado</p>

    <?php } else { ?>

        <p>ID: <?= htmlspecialchars((string)$libro['id']) ?></p>

        <p>ISBN: <?= htmlspecialchars($libro['isbn']) ?></p>

        <p>Título: <?= htmlspecialchars($libro['titulo']) ?></p>

        <p>Autor: <?= htmlspecialchars($libro['autor']) ?></p>

        <p>Año: <?= htmlspecialchars((string)$libro['anio']) ?></p>

        <p>Género: <?= htmlspecialchars($libro['genero']) ?></p>

        <p>Estado:
            <?= (int)$libro['disponible'] === 1 ? 'Disponible' : 'Prestado' ?>
        </p>

    <?php } ?>

    <a href="index.php">Volver al listado</a>

</body>
</html>