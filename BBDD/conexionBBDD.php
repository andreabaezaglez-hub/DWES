<?php

$dbh = new PDO(
    'mysql:host=localhost;dbname=dwes;charset=utf8mb4',
    'dwes',
    'abc123.',
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
);

// Mostrar versión del servidor
echo $dbh->getAttribute(PDO::ATTR_SERVER_VERSION);

echo '<br>';

// Ejecutar consulta y guardar el resultado
$dbs = $dbh->query(
    'SELECT cod, nombre, tlf FROM tienda ORDER BY nombre'
);

// Comprobar que la consulta se ejecutó correctamente
if ($dbs) {
    $data = $dbs->fetchAll();
    var_dump($data);
}

//index.php -> si no pongo nada, lista todas las familias de la tabla
//index.php?cod = {codfamilia}

if (isset ($_GET['cod'])) {
    // Si se pasa el cod por la URL
    $cod = $_GET['cod'];
    
} else {
    // si no se pasa el cod por la URL, mostrar todas las familias

}
// Cerrar conexión
$dbh = null;



?>
