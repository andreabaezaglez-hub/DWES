<?php

require_once __DIR__ . '/funciones.php';

$rutaCSV = __DIR__ . '/data_source/coches.csv';

$coches = getCSVContentInArray($rutaCSV);

dump($coches);

$output = getCochesMarkupFromData($coches);

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Coches</title>
</head>

<body>

    <?php echo $output; ?>

</body>

</html>
