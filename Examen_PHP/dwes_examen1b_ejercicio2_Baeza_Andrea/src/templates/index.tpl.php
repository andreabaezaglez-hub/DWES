<?php

//Startup errors (dev mode)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Torneos de eSports</title>
    <link rel="stylesheet" href="https://cdn.simplecss.org/simple.min.css">
</head>
<body>
    <header>
        <h1>Torneos de eSports</h1>
        <p>Clasificación de los jugadores inscritos en cada torneo</p>
    </header>

    <main>
        <section>
            <h2>Copa de Invierno <small>(Valorant)</small></h2>
            <table>
                <thead>
                    <tr>
                        <th>Jugador</th>
                        <th>Puntos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Kairo</td>
                        <td>120</td>
                    </tr>
                    <tr>
                        <td>NovaX</td>
                        <td>85</td>
                    </tr>
                    <tr>
                        <td>ShadowFox</td>
                        <td>40</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Liga Rocket Masters <small>(Rocket League)</small></h2>
            <table>
                <thead>
                    <tr>
                        <th>Jugador</th>
                        <th>Puntos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Pixelia</td>
                        <td>110</td>
                    </tr>
                    <tr>
                        <td>Lumen</td>
                        <td>95</td>
                    </tr>
                    <tr>
                        <td>Kairo</td>
                        <td>60</td>
                    </tr>
                    <tr>
                        <td>Raptor</td>
                        <td>30</td>
                    </tr>
                </tbody>
            </table>
        </section>

        <section>
            <h2>Open de Lucha <small>(Street Fighter 6)</small></h2>
            <table>
                <thead>
                    <tr>
                        <th>Jugador</th>
                        <th>Puntos</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>ShadowFox</td>
                        <td>150</td>
                    </tr>
                    <tr>
                        <td>NovaX</td>
                        <td>75</td>
                    </tr>
                    <tr>
                        <td>Raptor</td>
                        <td>0</td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>

    <footer>
        <p>DWES - Examen 1B - Ejercicio 1</p>
    </footer>
</body>
</html>
