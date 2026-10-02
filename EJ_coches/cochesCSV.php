<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$array_coches = [
    ['Toyota', 'Corolla', 'Blanco', '1234ABC'],
    ['Ford', 'Focus', 'Negro', '5678DEF'],
    ['Seat', 'Ibiza', 'Rojo', '9012GHI'],
    ['Renault', 'Clio', 'Azul', '3456JKL'],
    ['Peugeot', '308', 'Gris', '7890MNO'],
    ['Volkswagen', 'Golf', 'Plata', '2345PQR'],
    ['Audi', 'A4', 'Negro', '6789STU'],
    ['BMW', 'Serie 3','Blanco', '0123VWX'],
    ['Kia', 'Ceed', 'Rojo', '4567YZA'],
    ['Hyundai', 'Tucson', 'Azul', '8901BCD']
];
    
foreach ($array_coches as [$marca, $modelo, $color, $matricula]){
    if ($color == 'Rojo'){
    echo "'{$marca}' ";
        
    };
} 
?>
