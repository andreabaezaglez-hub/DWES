<?php

// Inicialización del entorno
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include(__DIR__ . '/funciones.php');

// Conexión a la base de datos
$conexion = new mysqli(
    'localhost',
    'root',
    '',
    'reservas_deportivas'
);

if ($conexion->connect_error) {
    die('Error de conexión: ' . $conexion->connect_error);
}

$conexion->set_charset('utf8mb4');

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
        $conexion,
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

        $stmt = $conexion->prepare($sql);

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
                $numero = $conexion->insert_id;

                $stmt->close();
                $conexion->close();

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

$conexion->close();

require __DIR__ . 'reservar.tpl.php';
?>