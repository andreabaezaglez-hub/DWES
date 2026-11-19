<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

// Comprobar si hay un usuario conectado
if (!isset($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$nombre = htmlspecialchars($_SESSION['nombre'], ENT_QUOTES, 'UTF-8');

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Bienvenida</title>
</head>

<body>

    <h1>Bienvenida, <?php echo $nombre; ?></h1>

    <p>Has iniciado sesión correctamente.</p>

    <a href="logout.php">Cerrar sesión</a>

</body>
</html>
