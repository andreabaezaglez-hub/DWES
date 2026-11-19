<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require 'bd.php';

// Si ya ha iniciado sesión, ir a bienvenida
if (isset($_SESSION['id'])) {
    header('Location: bienvenida.php');
    exit;
}

// Recuperar mensaje flash
$mensaje = '';

if (isset($_SESSION['mensaje'])) {
    $mensaje = $_SESSION['mensaje'];
    unset($_SESSION['mensaje']);
}

// Variables
$email = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $email = trim($_POST['email'] ?? '');
    $contrasena = $_POST['contrasena'] ?? '';

    // Buscar usuario por email
    $pdo = connectDB();

    $sql = "SELECT id, nombre, contrasena
            FROM usuarios
            WHERE email = :email";

    $stmt = $pdo->prepare($sql);
    $stmt->execute(['email' => $email]);

    $usuario = $stmt->fetch();

    // Comprobar contraseña
    if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {

        // Regenerar identificador de sesión
        session_regenerate_id(true);

        // Guardar datos del usuario en la sesión
        $_SESSION['id'] = $usuario['id'];
        $_SESSION['nombre'] = $usuario['nombre'];

        // Ir a la página privada
        header('Location: bienvenida.php');
        exit;

    } else {
        $error = 'Email o contraseña incorrectos';
    }
}

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Iniciar sesión</title>
</head>

<body>

    <h1>Iniciar sesión</h1>

    <?php
    if ($mensaje !== '') {
        echo '<p>' . htmlspecialchars($mensaje, ENT_QUOTES, 'UTF-8') . '</p>';
    }

    if ($error !== '') {
        echo '<p>' . htmlspecialchars($error, ENT_QUOTES, 'UTF-8') . '</p>';
    }
    ?>

    <form action="login.php" method="POST">

        <label>Email:</label>
        <input type="email" name="email"
            value="<?php echo htmlspecialchars($email, ENT_QUOTES, 'UTF-8'); ?>">

        <br>

        <label>Contraseña:</label>
        <input type="password" name="contrasena">

        <br>

        <button type="submit">Entrar</button>

    </form>

    <p>¿No tienes cuenta? <a href="registro.php">Regístrate</a></p>

</body>
</html>
