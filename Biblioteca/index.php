<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once "functions.php";

$pdo = connectDB();

// Contar los libros disponibles
$disponibles = contarDisponibles($pdo);

// Géneros permitidos
$generos = ['Novela', 'Ensayo', 'Poesía', 'Teatro', 'Cómic'];

// Recoger el género enviado por GET
$genero = $_GET['genero'] ?? null;

// Comprobar que el género sea válido
if (!in_array($genero, $generos, true)) {
    $genero = null;
}

// Obtener los libros
$libros = listarLibros($pdo, $genero);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca del instituto</title>
</head>

<body>
    <h2>Libros disponibles: <?= $disponibles ?></h2>
    <form action="index.php" method="GET">

        <label for="genero">Filtrar por género:</label>

        <select name="genero" id="genero">

            <option value="Todos">Todos</option>

            <?php foreach ($generos as $g) { ?>

                <option value="<?= htmlspecialchars($g) ?>"
                    <?= $genero === $g ? 'selected' : '' ?>>

                    <?= htmlspecialchars($g) ?>

                </option>

            <?php } ?>

        </select>

        <button type="submit">Filtrar</button>

    </form>
    <?php if (count($libros) === 0) { ?>

        <p>No hay libros de ese género</p>

    <?php } else { ?>

        <table border="1">

            <tr>
                <th>Título</th>
                <th>Autor</th>
                <th>Año</th>
                <th>Género</th>
                <th>Estado</th>
                <th>Acción</th>
            </tr>

            <?php foreach ($libros as $libro) { ?>

                <tr>

                    <td>
                        <a href="ficha.php?id=<?= (int)$libro['id'] ?>">
                            <?= htmlspecialchars($libro['titulo']) ?>
                        </a>
                    </td>

                    <td><?= htmlspecialchars($libro['autor']) ?></td>

                    <td><?= htmlspecialchars((string)$libro['anio']) ?></td>

                    <td><?= htmlspecialchars($libro['genero']) ?></td>

                    <td>
                        <?= (int)$libro['disponible'] === 1 ? 'Disponible' : 'Prestado' ?>
                    </td>
                    <td>
                        <form action="accion.php" method="POST">

                            <input type="hidden" name="id" value="<?= (int)$libro['id'] ?>">

                            <?php if ((int)$libro['disponible'] === 1) { ?>

                                <input type="hidden" name="accion" value="prestar">
                                <button type="submit">Prestar</button>

                            <?php } else { ?>

                                <input type="hidden" name="accion" value="devolver">
                                <button type="submit">Devolver</button>

                            <?php } ?>

                        </form>
                        <form action="accion.php" method="POST">
                            <input type="hidden" name="id" value="<?= (int)$libro['id'] ?>">
                            <input type="hidden" name="accion" value="borrar">

                            <button type="submit">Eliminar</button>
                        </form>
                    </td>

                </tr>

            <?php } ?>

        </table>

    <?php } ?>
    <br>
    <a href="nuevo.php">Añadir libro</a>
</body>

</html>