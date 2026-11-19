<?php

function connectDB(){
    try{
        
        $dsn = 'mysql:dbname=login_dwes;host=localhost;charset=utf8mb4';
        $user = "login_dwes";
        $password = "Login2026.";
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

