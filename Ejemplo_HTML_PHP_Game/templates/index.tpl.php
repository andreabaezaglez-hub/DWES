<?php
/** @var String $num_columns
 *  @var String $num_rows
 *  @var String $board_markup 
 *  @var array $link_pos
 */
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./public/css/zelda-botw.css">
    <title>Zelda 40th Anniversary Board Game</title>
    <style>
        body {
            background-color: gray; 
            margin: 0;
        }

        main {
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            min-height: 100vh;
        }

        h1 {
            color: #0f0f0f;
        }

        .board-container {
            background-color: red;
            display: grid;
            grid-template-columns: repeat(<?php echo $num_columns; ?>, 16px);
            grid-template-rows: repeat(<?php echo $num_rows; ?>, 16px);
            position: relative;
            border: 4px solid #114c35;
        }
/* Contenedor de la cruceta estilo NES */
        .controls-container {
            width: 160px;
            height: 160px;
            margin-top: 20px;
            display: grid;
            grid-template-columns: repeat(3, 45px);
            grid-template-rows: repeat(3, 45px);
            justify-content: center;
            align-content: center;
            gap: 4px;
        }

        /* Estilo base para cada botón de dirección */
        .controls-container a {
            background-color: #2b2b2b;
            color: #b3e91e; /* Dorado clásico Zelda */
            text-decoration: none;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            font-weight: bold;
            border: 2px solid #555;
            border-radius: 4px;
            box-shadow: inset 2px 2px 0px #444, inset -2px -2px 0px #111;
            transition: all 0.05s ease;
        }

        /* Efecto al pasar el ratón o pulsar */
        .controls-container a:hover {
            background-color: #3b3b3b;
            color: #fff;
        }

        .controls-container a:active {
            box-shadow: inset 2px 2px 0px #111, inset -2px -2px 0px #444;
            background-color: #1a1a1a;
            transform: scale(0.95);
        }

        /* Posicionamiento en cruz (D-Pad de 3x3) */
        .btn-arriba    { grid-column: 2; grid-row: 1; }
        .btn-izquierda { grid-column: 1; grid-row: 2; }
        .btn-derecha   { grid-column: 3; grid-row: 2; }
        .btn-abajo     { grid-column: 2; grid-row: 3; }

        .tile {
            width: 16px;
            height: 16px;
            background-color: yellow;
            background-image: url(./public/img/zelda_stage_bg.png);
            position: relative;
        }


      .link {
            width: 16px;
            height: 16px;
            background-image: url(./public/img/Link.png);
            position: absolute;
            top: 0;
            left: 0;
            z-index: 10;
        }

        /* ── Clases del Tileset ─────────────────── */
        .door-brown-tile { background-position: -1px -1px; }
        .stairs-corner-tile { background-position: -18px -1px; }
        .stairs-tile { background-position: -35px -1px; }
        .sand-1-tile { background-position: -52px -1px; }
        .sand-2-tile { background-position: -69px -1px; }
        .sand-3-tile { background-position: -86px -1px; }
        .water-top-left-tile { background-position: -1px -18px; }
        .water-top-tile { background-position: -18px -18px; }
        .water-top-right-tile { background-position: -35px -18px; }
        .water-left-tile { background-position: -1px -35px; }
        .water-tile { background-position: -18px -35px; }
        .water-right-tile { background-position: -35px -35px; }
        .water-bottom-left-tile { background-position: -1px -52px; }
        .water-bottom-tile { background-position: -18px -52px; }
        .water-bottom-right-tile { background-position: -35px -52px; }
        .wall-top-left-tile { background-position: -52px -18px; }
        .wall-top-tile { background-position: -69px -18px; }
        .wall-top-right-tile { background-position: -86px -18px; }
        .wall-left-tile { background-position: -52px -35px; }
        .wall-tile { background-position: -69px -35px; }
        .wall-right-tile { background-position: -86px -35px; }
        .wall-bottom-left-tile { background-position: -52px -52px; }
        .wall-entrance-tile { background-position: -86px -52px; }
        .bush-tile { background-position: -69px -52px; }
        .bush-dry-tile { background-position: -69px -69px; }
        .tombstone-tile { background-position: -86px -69px; }
        .boulder-tile { background-position: -69px -86px; }
        .grass-tile { background-position: -86px -86px; }
        .rock-brown-top-left-tile { background-position: -1px -86px; }
        .rock-brown-top-tile { background-position: -18px -86px; }
        .rock-brown-top-right-tile { background-position: -35px -86px; }
        .rock-brown-bottom-left-tile { background-position: -52px -86px; }
        .rock-brown-left-tile { background-position: -1px -103px; }
        .rock-brown-tile { background-position: -18px -103px; }
        .rock-brown-right-tile { background-position: -35px -103px; }
        .rock-grey-top-left-tile { background-position: -52px -103px; }
        .rock-grey-top-tile { background-position: -69px -103px; }
        .rock-grey-top-right-tile { background-position: -86px -103px; }
        .rock-grey-bottom-left-tile { background-position: -35px -120px; }
        .rock-grey-left-tile { background-position: -52px -120px; }
        .rock-grey-tile { background-position: -69px -120px; }
        .rock-grey-right-tile { background-position: -86px -120px; }
        .stone-floor-tile { background-position: -1px -120px; }
        .door-green-tile { background-position: -18px -120px; }
    </style>
</head>

<body>
    <main>
        <h1>Zelda 40th Anniversary</h1>
        
        <?php echo $board_markup; ?>
        
        <!-- Botones funcionales usando el array link_pos -->
        <div class="controls-container">
            <a class="btn-arriba" href="index.php?x=<?php echo $link_pos['x']; ?>&y=<?php echo $link_pos['y'] - 1; ?>">🠹</a>
            <a class="btn-izquierda" href="index.php?x=<?php echo $link_pos['x'] - 1; ?>&y=<?php echo $link_pos['y']; ?>">🠸</a>
            <a class="btn-abajo" href="index.php?x=<?php echo $link_pos['x']; ?>&y=<?php echo $link_pos['y'] + 1; ?>">🠻</a>
            <a class="btn-derecha" href="index.php?x=<?php echo $link_pos['x'] + 1; ?>&y=<?php echo $link_pos['y']; ?>">🠺</a>
        </div>
    </main>
</body>

</html>