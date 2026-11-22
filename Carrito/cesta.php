<?php

session_start();

if (isset($_POST['eliminar'])) {
    eliminarProducto();
    header('Location: cesta.php');
    exit;
} elseif ($_SERVER['REQUEST_METHOD'] === 'POST') {
    llenarCesta();
    header('Location: cesta.php');
    exit;
};

function llenarCesta()
{
    if (!isset($_SESSION['cesta'])) {
        $_SESSION['cesta'] = [];
    };

    $producto = $_POST['producto'];
    $precio = $_POST['precio'];

    if (isset($_SESSION['cesta'][$producto])) {
        $_SESSION['cesta'][$producto]['cantidad'] += 1;
        $_SESSION['cesta'][$producto]['subtotal'] += $precio;
    } else {
        $_SESSION['cesta'][$producto] = ['producto' => $producto, 'cantidad' => '1', 'subtotal' => $precio];
    }
};

function calcularTotal()
{
    if (!isset($_SESSION['cesta'])) {
        $_SESSION['cesta'] = [];
    };

    $total = 0;

    foreach ($_SESSION['cesta'] as $producto) {

        $total += $producto['subtotal'];
    };

    return $total;
};

function calcularTotalProductos()
{
    if (!isset($_SESSION['cesta'])) {
        $_SESSION['cesta'] = [];
    };

    $total = 0;

    foreach ($_SESSION['cesta'] as $producto) {

        $total += $producto['cantidad'];
    };

    return $total;
};

function listarArticulosCesta()
{
    if (!isset($_SESSION['cesta'])) {
        $_SESSION['cesta'] = [];
    };

    foreach ($_SESSION['cesta'] as $producto) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($producto['producto']) . "</td>";
        echo "<td>" . htmlspecialchars($producto['cantidad']) . "</td>";
        echo "<td>" . htmlspecialchars($producto['subtotal']) . " €</td>";
        echo "<td><form action='cesta.php' method='POST'>
        <input type='hidden' name='producto' value='" . htmlspecialchars($producto['producto'], ENT_QUOTES) . "'>
        <button type='submit' name='eliminar'>Eliminar</button></form></td>";
        echo "</tr>";
    };
};

function eliminarProducto()
{
    $producto = $_POST['producto'];

    if (isset($_SESSION['cesta'][$producto])) {
        unset($_SESSION['cesta'][$producto]);
    };
};

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cesta</title>
</head>

<body>
    <p>Tu carrito de la compra:</p>
    <br>
    <table>
        <tr>
            <th>Producto</th>
            <th>Cantidad</th>
            <th>Subtotal</th>
        </tr>
        <?php listarArticulosCesta() ?>
    </table>

    <h4><?php echo "Total: " .calcularTotal() . "€"?></h4>
</body>

</html>