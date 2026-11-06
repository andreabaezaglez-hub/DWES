<?php

function dump($var)
{
    echo '<pre>';
    var_dump($var);
    echo '</pre>';
}

function connectDB()
{
    try {
        $dsn = 'mysql:dbname=biblioteca;host=localhost;charset=utf8mb4';
        $user = 'root';
        $password = '';
        $pdo = new PDO($dsn, $user, $password);

        $pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error al conectar con la base de datos");
    }

    return $pdo;
}

/*Las funciones query(), fetchColumn(), fetch() y fetchAll() no hay que implementarlas, 
son funciones que ya existen en PDO y que nos permiten ejecutar consultas 
y obtener resultados de la base de datos. 
Por ejemplo, query() ejecuta una consulta SQL y devuelve un objeto PDOStatement, 
fetchColumn() obtiene el valor de una columna específica de la siguiente fila del conjunto de resultados, 
fetch() obtiene la siguiente fila del conjunto de resultados como un array asociativo o numérico, y 
fetchAll() obtiene todas las filas del conjunto de resultados como un array de arrays.*/

function contarDisponibles(PDO $pdo): int
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM libros WHERE disponible = 1");
    $result = $stmt->fetchColumn();
    return (int)$result;
}

function obtenerLibro(PDO $pdo, int $id): array|false
{
    $sql = "SELECT * FROM libros WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $libro = $stmt->fetch();
    return $libro;
}

function obtenerAutor(PDO $pdo, int $id): string|false
{ //Esta no entra en la practica pero era pa practicar codigo
    $sql = "SELECT autor FROM libros WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute(['id' => $id]);
    $result = $stmt->fetchColumn();
    return $result;
}

function listarLibros(PDO $pdo, ?string $genero = null): array
{
    if ($genero === null) {
        $sql = "SELECT * FROM libros ORDER BY titulo";
        $stmt = $pdo->query($sql);
    } else {
        $sql = "SELECT * FROM libros WHERE genero = :genero ORDER BY titulo";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':genero' => $genero]);
    }

    $libros = $stmt->fetchAll();
    return $libros;
}

function insertarLibro(PDO $pdo, array $datos): int
{
    $sql = "INSERT INTO libros (isbn, titulo, autor, anio, genero, disponible) 
            VALUES (:isbn, :titulo, :autor, :anio, :genero, 1)";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':isbn' => $datos['isbn'],
        ':titulo' => $datos['titulo'],
        ':autor' => $datos['autor'],
        ':anio' => $datos['anio'],
        ':genero' => $datos['genero']
    ]);

    $ultInsert = (int)$pdo->lastInsertId();

    return $ultInsert;
    //TODO: control de errores
}

function cambiarDisponible(PDO $pdo, int $id, int $disponible): int {
    $sql = "UPDATE libros
            SET disponible = :disponible
            WHERE id = :id AND disponible = :estadoAnterior";

    $stmt = $pdo->prepare($sql);

    $estadoAnterior = $disponible === 1 ? 0 : 1;

    $stmt->execute([
        ':disponible' => $disponible,
        ':id' => $id,
        ':estadoAnterior' => $estadoAnterior
    ]);

    return $stmt->rowCount();
}

function borrarLibro(PDO $pdo, int $id): int
{
    $sql = "DELETE FROM libros WHERE id = :id";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $id
    ]);

    return $stmt->rowCount();
}