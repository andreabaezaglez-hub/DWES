<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);



/* ***** INICIALIZACIÓN DEL ENTORNO ***** */

session_start();

require './lib/funciones.php';


/* ***** LÓGICA DE NEGOCIO ***** */



/* ***** LÓGICA DE PRESENTACIÓN ***** */


include 'templates/index.tpl.html';

?>