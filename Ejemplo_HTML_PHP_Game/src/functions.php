<?php

function dump($var){
    echo '<pre>'.print_r($var,1).'</pre>';
}

function getBoardFromCsv(String $rutaCSV)
{
    $stream = fopen($rutaCSV, 'r');
    $tablero = [];

    if ($stream !== false) {
        while (($fila = fgetcsv($stream)) !== false) {
            $tablero[] = array_map('trim', $fila);
        }
        fclose($stream);
    }

    return $tablero;
}

function getBoardMarkup($board_data, $link_x = null, $link_y = null){
    $output = '<div class="board-container">';
    foreach($board_data as $y => $fila){
        foreach ($fila as $x => $tile_value){
            $output .= '<div class="tile '.$tile_value.'-tile">';
            
            // Si Link está aquí, pintamos su imagen
            if ($x === $link_x && $y === $link_y) {
                $output .= '<div class="link" title="Link"></div>';
            }
            
            $output .= '</div>';
        }
    }
    $output .= '</div>';

    return $output;
}