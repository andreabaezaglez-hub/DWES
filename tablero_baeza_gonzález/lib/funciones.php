<?php

const FILAS = 5;
const COLUMNAS = 8;

const PALETA = [
    'cian' => 'Cian',
    'rosa' => 'Rosa',
    'lima' => 'Lima',
    'gris' => 'Gris',
    'marron' => 'Marrón',
    'indigo' => 'Índigo'
];


if(isset($_POST['fila']) > 5){
    $_POST['fila']=5;
};

if(!isset($_SESSION['tablero'])){
    $tablero = [["", "", "", "", ""],
        ["", "", "", "", ""],
        ["", "", "", "", ""],
        ["", "", "", "", ""],
        ["", "", "", "", ""],
        ["", "", "", "", ""],
        ["", "", "", "", ""],
        ];
}else{
    $tablero = $_SESSION['tablero'];
};

function pintarCasilla (){

    $fila = $_POST['fila'];
    $columna = $_POST['columna'];
    $color = $_POST['color'];


    if(isset($_POST['pintar'])){

        if(isset($_POST['color'])){
            $tablero[$fila][$columna]= "";
        };
    };

    header('Location: ./index.php');
    exit;

};

function borrarCasilla(){
    
    $fila = $_POST['fila'];
    $columna = $_POST['columna'];


    if(isset($_POST['borrar'])){
        $tablero[$fila][$columna]= "";
    };

    header('Location: ./index.php');
    exit;
};

function reiniciar(){

    header('Location: ./index.php');
    exit;
};

function estadisticas(){

    header('Location: ./index.php');
    exit;
};


?>