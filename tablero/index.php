
<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

/* ***** INICIALIZACIÓN DEL ENTORNO ***** */

session_start();

require_once 'lib/funciones.php';

if (!isset($_SESSION['tablero'])) {
    $_SESSION['tablero'] = iniciarTablero();
}

// Variables de mensajes y formulario
$mensaje = "";
$errores = [];

$fila = "";
$columna = "";
$color = "";


/* ***** LÓGICA DE NEGOCIO ***** */

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    if (isset($_POST['accion'])) {

        $accion = $_POST['accion'] ?? '';
        $fila = $_POST['fila'] ?? "";
        $columna = $_POST['columna'] ?? "";
        $color = $_POST['color'] ?? "";

        if ($accion == 'pintar' || $accion == 'borrar') {

            $filaValida = filter_var($fila, FILTER_VALIDATE_INT);

            if ($filaValida === false || $filaValida < 1 || $filaValida > FILAS) {
                $errores['fila'] = "La fila debe estar entre 1 y " . FILAS;
            };

            $columnaValida = filter_var($columna, FILTER_VALIDATE_INT);

            if ($columnaValida === false || $columnaValida < 1 || $columnaValida > COLUMNAS) {
                $errores['columna'] = "La columna debe estar entre 1 y " . COLUMNAS;
            };

            //Distinguimos entre pintar y borrar

            if ($accion == 'pintar') {
                $colorValido = array_key_exists($color, PALETA);

                if ($colorValido === false) {
                    $errores['color'] = "Selecciona un color válido";
                }

                if (empty($errores)) {

                    $_SESSION['tablero'] = pintarTablero(
                        $_SESSION['tablero'],
                        $filaValida,
                        $columnaValida,
                        $color
                    );

                    $mensaje = "Casilla ($filaValida, $columnaValida) pintada de " . PALETA[$color] . ".";
                }
            } elseif ($accion == 'borrar') {

                if (empty($errores)) {

                    if ($_SESSION['tablero'][$filaValida - 1][$columnaValida - 1] == "") {
                        $errores['casilla'] = "La casilla ya está vacía";

                    } else {
                        $_SESSION['tablero'][$filaValida - 1][$columnaValida - 1] = "";
                        $mensaje = "Casilla ($filaValida, $columnaValida) borrada correctamente.";
                    }
                }
            }
        } elseif ($accion == 'reiniciar') {
            $_SESSION['tablero'] = iniciarTablero();
            $mensaje = "El tablero se ha reiniciado correctamente.";
        }
    }
}


/* ***** LÓGICA DE PRESENTACIÓN ***** */

// Variables del tablero y estadísticas
$tablero = $_SESSION['tablero'];

$totalCasillas = FILAS * COLUMNAS;
$casillasPintadas = 0;
$porcentaje = 0;
$arrayPintadas = [];
$maxPintadas = 0;
$filasMaximas = [];

for ($i = 0; $i < FILAS; $i++) {
    $pintadasEnFila = 0;

    for ($j = 0; $j < COLUMNAS; $j++) {

        if ($_SESSION['tablero'][$i][$j] !== "") {
            $casillasPintadas++;
            $pintadasEnFila++;
        };

    };

    $arrayPintadas[$i + 1] = $pintadasEnFila;

};

$porcentaje = round(($casillasPintadas / $totalCasillas) * 100, 1);

foreach ($arrayPintadas as $numeroPintadas) {
    if ($numeroPintadas > $maxPintadas) {
        $maxPintadas = $numeroPintadas;
    };
};

if ($casillasPintadas > 0) {
    foreach ($arrayPintadas as $numeroFila => $numeroPintadas) {
        if ($numeroPintadas == $maxPintadas) {
            $filasMaximas[] = $numeroFila;
        };
    };
};

include 'templates/index.tpl.php';
