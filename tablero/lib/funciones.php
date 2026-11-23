<?php
const FILAS = 5;
const COLUMNAS = 8;

const PALETA = [
    'cian' => 'Cian',
    'rosa' => 'Rosa',
    'lima' => 'Lima',
    'gris' => 'Gris',
    'marron' => 'Marrón',
    'indigo' => 'Índigo',
];

if (!isset(PALETA['color'])) {
    $errores['color'] = "Selecciona un color válido.";
}

function iniciarTablero(){
    $tablero = [];

    for ($i = 0; $i < FILAS; $i++){
        $fila = [];

        for($j = 0; $j < COLUMNAS; $j++) {
            $fila[] = "";

        }

        $tablero[] = $fila;
    }

    return $tablero;
}

function pintarTablero($tablero, $fila, $columna, $color) {

    $tablero[$fila - 1][$columna - 1] = $color;

    return $tablero;
}

function pintarCasilla($color) {
    $output = '<td class="casilla';

    if ($color != "") {
        $output .= ' c-' . $color;
    }

    $output .= '"></td>';

    return $output;
}
