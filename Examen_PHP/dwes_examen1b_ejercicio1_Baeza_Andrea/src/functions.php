<?php

function dump($var){
    echo '<pre>'.print_r($var,1).'</pre>';
}


function connectDB(){
    try{
        
        $dsn = 'mysql:dbname=dwes_examen1b;host=127.0.0.1;charset=utf8mb4';
        $user = "dwes_examen1b";
        $password = "dwes_examen1b";
        $pdo = new PDO($dsn, $user, $password);

        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        //echo "exito";
    }catch(PDOException $e){
        die("Error al conectar a la base de datos.");
    }

    return $pdo;
}

function getTournamentsFromDB(PDO $pdo){
    //Consulta a realizar:
    //   'SELECT t.id AS torneo_id, t.nombre, t.juego,
    //               j.nick, i.puntos
    //        FROM torneos t
    //        LEFT JOIN inscripciones i ON i.torneo_id = t.id
    //        LEFT JOIN jugadores j ON j.id = i.jugador_id
    //        ORDER BY t.nombre, i.puntos DESC';


    $stmt = $pdo->query('SELECT t.id AS torneo_id, t.nombre, t.juego,
                  j.nick, i.puntos
           FROM torneos t
           LEFT JOIN inscripciones i ON i.torneo_id = t.id
           LEFT JOIN jugadores j ON j.id = i.jugador_id
           ORDER BY t.nombre, i.puntos DESC');

    $torneos = $stmt->fetchAll();
    return $torneos;
}

