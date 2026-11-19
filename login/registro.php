<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require 'bd.php';

if (isset($_SESSION['id'])) {
    header('Location: bienvenida.php');
    exit;
}

$nombre = "";
$email = "";
$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $nombre = trim($_POST['nombre'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';
    $repetir = $_POST['repetir'] ?? '';

    if ($nombre === '') {
        $errores['nombre'] = 'El nombre es obligatorio';
    }

    if ($email === '') {
        $errores['email'] = 'El email es obligatorio';
    }

    if ($contrasena === '') {
        $errores['contrasena'] = 'La contraseña es obligatoria';
    }

    if ($repetir === '') {
        $errores['repetir'] = 'Debes repetir la contraseña';
    }

    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errores['email'] = 'El email no tiene un formato válido';
    }

    if ($contrasena !== '' && strlen($contrasena) < 8) {
        $errores['contrasena'] = 'La contraseña debe tener al menos 8 caracteres';
    }

    if ($contrasena !== '' && $repetir !== '' && $contrasena !== $repetir) {
        $errores['repetir'] = 'Las contraseñas no coinciden';
    }


    if ($email !== '' && filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $pdo = connectDB();

        $sql = "SELECT id FROM usuarios WHERE email = :email";

        $stmt = $pdo->prepare($sql);

        $stmt->execute(['email' => $email]);

        if ($stmt->fetch()) {
            $errores['email'] = 'Este email ya está registrado';
        }
    }

    if (empty($errores)) {
        // Realizar el registro

        $hash = password_hash($contrasena, PASSWORD_DEFAULT);

        $pdo = connectDB();

        $sql = "INSERT INTO usuarios (nombre, email, contrasena)
        VALUES (:nombre, :email, :contrasena)";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            'nombre' => $nombre,
            'email' => $email,
            'contrasena' => $hash
        ]);

        $_SESSION['mensaje'] = 'Registro completado. Ya puedes iniciar sesión';

        header('Location: login.php');
        exit;
    }
}

?>


<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Registro</title>
</head>

<body>

    <h1>Registro de usuario</h1>

    <form action="registro.php" method="POST">

        <label>Nombre:</label>
        <input type="text" name="nombre"
            value="<?php echo htmlspecialchars($nombre, ENT_QUOTES, 'UTF-8'); ?>">

        <?php
        if (isset($errores['nombre'])) {
            echo '<p>' . htmlspecialchars($errores['nombre']) . '</p>';
        }
        ?>

        <br>

        <label>Email:</label>
        <input type="email" name="email"
            value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">

        <?php
        if (isset($errores['email'])) {
            echo '<p>' . htmlspecialchars($errores['email']) . '</p>';
        }
        ?>

        <br>

        <label>Contraseña:</label>
        <input type="password" name="contrasena">

        <?php
        if (isset($errores['contrasena'])) {
            echo '<p>' . htmlspecialchars($errores['contrasena']) . '</p>';
        }
        ?>

        <br>

        <label>Repetir contraseña:</label>
        <input type="password" name="repetir">

        <?php
        if (isset($errores['repetir'])) {
            echo '<p>' . htmlspecialchars($errores['repetir']) . '</p>';
        }
        ?>

        <br>

        <button type="submit">Registrarse</button>

    </form>

    <p>¿Ya tienes cuenta? <a href="login.php">Inicia sesión</a></p>

</body>

</html>