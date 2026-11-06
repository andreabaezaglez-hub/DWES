<?php

require_once "functions.php";

$pdo = connectDB();

$errores = [];
$mensaje = null;
$idNuevo = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $datos = [
        'isbn' => trim($_POST['isbn'] ?? ''),
        'titulo' => trim($_POST['titulo'] ?? ''),
        'autor' => trim($_POST['autor'] ?? ''),
        'anio' => trim($_POST['anio'] ?? ''),
        'genero' => trim($_POST['genero'] ?? '')
    ];

    if ($datos['isbn'] === '') {
        $errores[] = "El ISBN es obligatorio";
    }

    if ($datos['titulo'] === '') {
        $errores[] = "El título es obligatorio";
    }

    if ($datos['autor'] === '') {
        $errores[] = "El autor es obligatorio";
    }

    // Validar año
    $anio = filter_var($datos['anio'], FILTER_VALIDATE_INT);

    if ($anio === false || $anio < 1450 || $anio > (int)date('Y')) {
        $errores[] = "El año debe estar entre 1450 y el año actual";
    }

    $generos = ['Novela', 'Ensayo', 'Poesía', 'Teatro', 'Cómic'];

    if (!in_array($datos['genero'], $generos, true)) {
        $errores[] = "El género no es válido";
    }

    if (count($errores) === 0) {

        try {
            $idNuevo = insertarLibro($pdo, [
                'isbn' => $datos['isbn'],
                'titulo' => $datos['titulo'],
                'autor' => $datos['autor'],
                'anio' => $anio,
                'genero' => $datos['genero']
            ]);

            $mensaje = "Libro añadido correctamente. ID: " . $idNuevo;
        } catch (PDOException $e) {

            if ($e->errorInfo[1] === 1062) {
                $errores[] = "Ya existe un libro con ese ISBN";
            } else {
                $errores[] = "No se ha podido añadir el libro";
            }
        }
    }
}


?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Biblioteca</title>
</head>

<body>

</body>

</html>