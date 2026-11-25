<?php
/** 
 * @var array $tablero
 * @var int $totalCasillas
 * @var float $porcentaje
 * @var array $arrayPintadas
 * @var array $filasMaximas
 */

?>

<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tablero de colores · Modelo B</title>
    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
            background: #eef1f5;
            color: #2c3e50;
            padding: 30px 15px;
        }

        .container {
            max-width: 900px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            overflow: hidden;
        }

        header {
            background: #2c3e50;
            color: #fff;
            padding: 25px 30px;
        }

        header h1 {
            font-size: 1.6em;
        }

        header p {
            opacity: 0.8;
            margin-top: 4px;
        }

        main {
            display: grid;
            grid-template-columns: 300px 1fr;
            gap: 30px;
            padding: 30px;
        }

        @media (max-width: 760px) {
            main {
                grid-template-columns: 1fr;
            }
        }

        h2 {
            font-size: 1.1em;
            margin-bottom: 15px;
            padding-bottom: 6px;
            border-bottom: 2px solid #eef1f5;
        }

        /* ---------- Mensajes ---------- */
        .mensaje {
            margin: 20px 30px 0;
            padding: 12px 16px;
            border-radius: 8px;
            font-weight: 500;
        }

        .mensaje.ok {
            background: #e8f8ef;
            color: #1e8449;
            border-left: 4px solid #27ae60;
        }

        .mensaje.error {
            background: #fdecea;
            color: #b03a2e;
            border-left: 4px solid #e74c3c;
        }

        /* ---------- Formularios ---------- */
        .campo {
            margin-bottom: 14px;
        }

        .campo label {
            display: block;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .campo input,
        .campo select {
            width: 100%;
            padding: 8px 10px;
            border: 1px solid #ccd3db;
            border-radius: 6px;
            font-size: 1em;
        }

        .campo.con-error input,
        .campo.con-error select {
            border-color: #e74c3c;
        }

        .campo .error {
            display: block;
            color: #e74c3c;
            font-size: 0.85em;
            margin-top: 4px;
        }

        .botones {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        button {
            padding: 9px 16px;
            border: none;
            border-radius: 6px;
            font-size: 0.95em;
            font-weight: 600;
            cursor: pointer;
            color: #fff;
        }

        .btn-pintar {
            background: #2980b9;
        }

        .btn-borrar {
            background: #7f8c8d;
        }

        .btn-reiniciar {
            background: #c0392b;
        }

        .btn-deshacer {
            background: #8e44ad;
        }

        button:hover {
            filter: brightness(1.1);
        }

        .acciones {
            margin-top: 25px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        /* ---------- Tablero ---------- */
        .tablero {
            border-collapse: collapse;
            margin: 0 auto;
        }

        .tablero th {
            width: 46px;
            height: 30px;
            font-size: 0.85em;
            color: #7f8c8d;
        }

        .tablero td.casilla {
            width: 46px;
            height: 46px;
            border: 1px solid #d5dbe1;
            background: #fafbfc;
        }

        /* Un color de la paleta = una clase */
        .c-cian {
            background: #00bcd4 !important;
        }

        .c-rosa {
            background: #e91e63 !important;
        }

        .c-lima {
            background: #8bc34a !important;
        }

        .c-gris {
            background: #95a5a6 !important;
        }

        .c-marron {
            background: #795548 !important;
        }

        .c-indigo {
            background: #3f51b5 !important;
        }

        /* ---------- Estadísticas ---------- */
        .estadisticas {
            margin-top: 30px;
        }

        .resumen {
            margin-bottom: 10px;
        }

        .recuento {
            list-style: none;
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .recuento li {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 5px 10px;
            background: #f4f6f8;
            border-radius: 20px;
            font-size: 0.9em;
        }

        .muestra {
            display: inline-block;
            width: 14px;
            height: 14px;
            border-radius: 50%;
        }

        footer {
            background: #f4f6f8;
            text-align: center;
            padding: 12px;
            font-size: 0.85em;
            color: #7f8c8d;
        }
    </style>
</head>

<body>
    <div class="container">

        <header>
            <h1>Tablero de colores</h1>
            <p>Desarrollo Web en Entorno Servidor · Sesiones en PHP</p>
        </header>

        <!-- MENSAJES: se muestra como mucho uno, según el resultado de la última acción -->
        <?php if (!empty($mensaje)) { ?>
            <p class="mensaje ok"><?= $mensaje ?></p>
        <?php } ?>

        <?php if (!empty($errores)) { ?>
            <p class="mensaje error">No se ha podido realizar la acción. Revisa los datos.</p>
        <?php } ?>

        <main>

            <!-- ==================== COLUMNA IZQUIERDA ==================== -->
            <section>
                <h2>Pintar o borrar casilla</h2>

                <form method="post" action="index.php" novalidate>

                    <!-- Ejemplo de campo con error: clase con-error + span.error -->
                    <div class="campo <?= isset($errores['fila']) ? 'con-error' : '' ?>">
                        <label for="fila">Fila (1-5)</label>
                        <input type="number" id="fila" name="fila" value="<?= htmlspecialchars($fila) ?>">

                        <?php if (isset($errores['fila'])) { ?>
                            <span class="error"><?= $errores['fila'] ?></span>
                        <?php } ?>
                    </div>

                    <div class="campo <?= isset($errores['columna']) ? 'con-error' : '' ?>">
                        <label for="columna">Columna (1-8)</label>
                        <input type="number" id="columna" name="columna" value="<?= htmlspecialchars($columna) ?>">

                        <?php if (isset($errores['columna'])) { ?>
                            <span class="error"><?= $errores['columna'] ?></span>
                        <?php } ?>
                    </div>

                    <div class="campo <?= isset($errores['color']) ? 'con-error' : '' ?>">
                        <label for="color">Color</label>
                        <select id="color" name="color">
                            <option value="" <?= $color == '' ? 'selected' : '' ?>>-- Elige un color --</option>
                            <option value="cian" <?= $color == 'cian' ? 'selected' : '' ?>>Cian</option>
                            <option value="rosa" <?= $color == 'rosa' ? 'selected' : '' ?>>Rosa</option>
                            <option value="lima" <?= $color == 'lima' ? 'selected' : '' ?>>Lima</option>
                            <option value="gris" <?= $color == 'gris' ? 'selected' : '' ?>>Gris</option>
                            <option value="marron" <?= $color == 'marron' ? 'selected' : '' ?>>Marrón</option>
                            <option value="indigo" <?= $color == 'indigo' ? 'selected' : '' ?>>Índigo</option>
                            </select>

                                <?php if (isset($errores['color'])) { ?>
                                    <span class="error"><?= $errores['color'] ?></span>
                                <?php } ?>
                        </div>
                    

                    <div class="botones">
                        <button type="submit" name="accion" value="pintar" class="btn-pintar">Pintar</button>
                        <button type="submit" name="accion" value="borrar" class="btn-borrar">Borrar casilla</button>
                    </div>
                </form>

                <div class="acciones">
                    <form method="post" action="index.php">
                        <button type="submit" name="accion" value="reiniciar" class="btn-reiniciar">Reiniciar
                            tablero</button>
                    </form>

                    <!-- AMPLIACIÓN -->
                    <form method="post" action="index.php">
                        <button type="submit" name="accion" value="deshacer" class="btn-deshacer">Deshacer</button>
                    </form>
                </div>
            </section>

            <!-- ==================== COLUMNA DERECHA ==================== -->
            <section>
                <h2>Tablero</h2>

                <table class="tablero">
                    <tr>
                        <th></th>
                        <th>1</th>
                        <th>2</th>
                        <th>3</th>
                        <th>4</th>
                        <th>5</th>
                        <th>6</th>
                        <th>7</th>
                        <th>8</th>
                    </tr>
                    
                    <?php foreach ($tablero as $numero => $fila) {
                        $i = 1; ?>
                    <tr>
                        <th><?= $numero + 1 ?></th>
                        <?php foreach ($fila as $color) { ?>
                            <?=  pintarCasilla($color) ?>
                        <?php } ?>
                    </tr>
                    <?php } ?>

                </table>

                <?php if ($casillasPintadas > 0){ ?>
                <div class="estadisticas">
                    <h2>Estadísticas</h2>
                    <p class="resumen">Casillas pintadas: <strong><?= $casillasPintadas ?> de <?= $totalCasillas ?></strong> (<?= $porcentaje ?> %)</p>

                    <ul class="recuento">
                        <?php foreach ($arrayPintadas as $numeroFila => $numeroPintadas) { ?>
                            <li>Fila <?= $numeroFila ?>: <?= $numeroPintadas ?></li>
                        <?php } ?>
                    </ul>
                    <p class="resumen" style="margin-top: 10px;">Fila<?= count($filasMaximas) > 1 ? 's' : '' ?> más pintada<?= count($filasMaximas) > 1 ? 's' : '' ?>: <strong><?= implode(', ', $filasMaximas) ?></strong> (<?= $maxPintadas ?> casillas)</p>
                </div>
                <?php } ?>
            </section>

        </main>

        <footer>Examen DWES · Curso 2026/2027</footer>
    </div>
</body>

</html>