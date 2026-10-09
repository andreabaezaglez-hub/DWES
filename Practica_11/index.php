<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reservas Polideportivo</title>
    <link rel="stylesheet" href="https://unpkg.com/simpledotcss/simple.css">
</head>
<body>
    <button><a href="reservar.php" style="color: white;">Reservar</a></button>
    <?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function dump ($var){
    echo '<pre>'.print_r($var, 1). '</pre>';
}

$lista_reserva = file_get_contents ('datos/reservas.csv');


$lineas = explode("\n", trim($lista_reserva));

$array_reservas = [];

foreach ($lineas as $linea) {
    if (!empty(trim($linea))) { 
        $array_reservas[] = explode(",", $linea);
        
    }
}

//dump($array_reservas);


$encabezado = explode(",", trim($lineas[0]));

//dump($encabezado);


$reservas = [];



    foreach ($array_reservas as $fila) {
        $reservas[] = array_combine($encabezado, array_slice($fila, 0, count($encabezado)));
    }
    $actual = next($array_reservas);


//dump($reservas);  

echo "<table border='1'>";



for ($i = 0; $i < count($reservas); $i++) {
    echo "<tr>";

    foreach ($reservas[$i] as $clave => $valor) {
        echo "<td>" . htmlspecialchars((string)$valor) . "</td>";
    }

    echo "</tr>";
}

echo "</table>";

$filtro_pista = $_GET["PISTA"] ?? null;
$filtro_fecha = $_GET["FECHA"] ?? null;



?>

</body>
</html>
