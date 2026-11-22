<?php


$productos = [
    [
        'id' => '1',
        'producto' => 'Teclado',
        'precio' => '25',
        'unidades' => '6'
    ],

    [
        'id' => '2',
        'producto' => 'Ratón',
        'precio' => '15',
        'unidades' => '9'
    ],

    [
        'id' => '3',
        'producto' => 'Monitor',
        'precio' => '150',
        'unidades' => '7'
    ],

    [
        'id' => '4',
        'producto' => 'Auriculares',
        'precio' => '35',
        'unidades' => '10'
    ],

    [
        'id' => '5',
        'producto' => 'Altavoz',
        'precio' => '45',
        'unidades' => '1'
    ],

    [
        'id' => '6',
        'producto' => 'Webcam',
        'precio' => '30',
        'unidades' => '3'
    ],
];



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de productos</title>
</head>

<body>
    <legend> Elija sus productos: </legend>
    <ul>
        <?php foreach ($productos as $producto) { ?>
            <li>
                <div class="producto">
                    <p>
                        <?php echo $producto['producto'] . " ---- " . $producto['precio'] . " €"; ?>
                    </p>

                    <form action="cesta.php" method="POST">
                        <input type="hidden" name="producto" value="<?php echo $producto['producto']; ?>">
                        <input type="hidden" name="precio" value="<?php echo $producto['precio']; ?>">

                        <button type="submit">Añadir al carrito</button>
                    </form>
                </div>
            </li>
        <?php } ?>
    </ul>
</body>

</html>