<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function dump($var){
  echo '<pre>'.print_r($var,1).'</pre>';
}


// $coches = [
//     [
//         "marca" => "Toyota",
//         "modelo" => "Corolla",
//         "color" => "Blanco",
//         "matricula" => "1234ABC"
//     ],
//     [
//         "marca" => "BMW",
//         "modelo" => "Serie 3",
//         "color" => "Negro",
//         "matricula" => "5678DEF"
//     ],
//     [
//         "marca" => "Seat",
//         "modelo" => "León",
//         "color" => "Rojo",
//         "matricula" => "9012GHI"
//     ],
//     [
//         "marca" => "Audi",
//         "modelo" => "A3",
//         "color" => "Azul",
//         "matricula" => "3456JKL"
//     ],
//     [
//         "marca" => "Volkswagen",
//         "modelo" => "Golf",
//         "color" => "Gris",
//         "matricula" => "7890MNO"
//     ],
//     [
//         "marca" => "Mercedes-Benz",
//         "modelo" => "Clase A",
//         "color" => "Plateado",
//         "matricula" => "1357PQR"
//     ],
//     [
//         "marca" => "Ford",
//         "modelo" => "Focus",
//         "color" => "Verde",
//         "matricula" => "2468STU"
//     ],
//     [
//         "marca" => "Renault",
//         "modelo" => "Clio",
//         "color" => "Amarillo",
//         "matricula" => "9753VWX"
//     ],
//     [
//         "marca" => "Peugeot",
//         "modelo" => "208",
//         "color" => "Naranja",
//         "matricula" => "8642YZA"
//     ],
//     [
//         "marca" => "Hyundai",
//         "modelo" => "Tucson",
//         "color" => "Blanco",
//         "matricula" => "4321BCD"
//     ]
// ];


$contenidoArchivo = file_get_contents(__DIR__.'/data_source/coches.csv');
$lineasCSV =explode("\n",$contenidoArchivo);

$encabezadoCadena = reset($lineasCSV);
$encabezadoArray = explode(',', $encabezadoCadena);

$encabezadoArray = array_map('trim',$encabezadoArray);


$coches = [];
foreach($lineasCSV as $clave => $linea){
  if($clave != 0){
    $camposLinea = explode(',', $linea);    
    $coche = array_combine($encabezadoArray,$camposLinea);


  }
  
}


if (($fd = fopen(__DIR__.'/data_source/coches.csv','r')) !== false) {
  if(($nombre_campos = fgetcsv($fd)) !== false){
    
    while(($nuevo_coche = fgetcsv($fd)) !== false){
      
      $coches[$nuevo_coche[3]] = array_combine($nombre_campos,$nuevo_coche);
    }
    fclose($fd);
  }
}

//TODO: Hacer aquí la selección de los coches de un determinado color

?>