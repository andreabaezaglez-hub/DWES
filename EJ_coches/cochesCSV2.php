<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function dump ($var){
    echo '<pre>'.print_r($var, 1). '</pre>';
}

$lista_coches = file_get_contents ('Coches.csv');

//dump($lista_coches);

// Separamos el csv por los saltos de lineas que son los coches
// Usamos trim() para limpiar posibles espacios o saltos vacíos al final
$lineas = explode("\n", trim($lista_coches));

$array_coches = [];

// Aqui separamos ya por comas y montamos el array final
foreach ($lineas as $linea) {
    if (!empty(trim($linea))) { // Evita procesar líneas vacías
        $array_coches[] = explode(",", $linea);
        
    }
}

dump($array_coches);

?>