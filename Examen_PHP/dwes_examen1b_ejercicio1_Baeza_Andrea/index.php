<?php

//Startup errors (dev mode)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once(__DIR__.'/src/functions.php');

$pdo = connectDB();



//Script principal
$tournaments = getTournamentsFromDB($pdo);

include(__DIR__.'/src/templates/index.tpl.php');