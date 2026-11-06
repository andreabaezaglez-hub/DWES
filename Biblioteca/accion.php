<?php
require_once "functions.php";

$pdo = connectDB();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("Acceso no permitido");
}

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$accion = $_POST['accion'] ?? '';

if ($id === false || $id === null) {
    die("El identificador del libro no es válido");
}

if ($accion === 'prestar') {

    $resultado = cambiarDisponible($pdo, $id, 0);
    $mensaje = $resultado === 1
        ? "Libro prestado correctamente"
        : "No se ha podido cambiar el estado";

} elseif ($accion === 'devolver') {

    $resultado = cambiarDisponible($pdo, $id, 1);
    $mensaje = $resultado === 1
        ? "Libro devuelto correctamente"
        : "No se ha podido cambiar el estado";

} elseif ($accion === 'borrar') {

    try {
        $resultado = borrarLibro($pdo, $id);

        if ($resultado === 1) {
            $mensaje = "Libro eliminado correctamente";
        } else {
            $mensaje = "Ese libro no existe";
        }

    } catch (PDOException $e) {

        if (isset($e->errorInfo[1]) && $e->errorInfo[1] === 1451) {
            $mensaje = "No se puede borrar: el libro tiene préstamos registrados";
        } else {
            $mensaje = "No se ha podido eliminar el libro";
        }
    }

} else {
    die("La acción no es válida");
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acción sobre el libro</title>
</head>
<body>

    <p><?= htmlspecialchars($mensaje) ?></p>

    <a href="index.php">Volver a la biblioteca</a>

</body>
</html>