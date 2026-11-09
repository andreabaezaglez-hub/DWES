-- Base de datos: dwes_examen1b
-- Torneos de eSports, jugadores y puntuación de cada jugador en cada torneo

DROP DATABASE IF EXISTS dwes_examen1b;
CREATE DATABASE dwes_examen1b CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dwes_examen1b;

-- Tabla de torneos
CREATE TABLE torneos (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nombre VARCHAR(100) NOT NULL,
    juego VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

-- Tabla de jugadores
CREATE TABLE jugadores (
    id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    nick VARCHAR(50) NOT NULL,
    pais VARCHAR(50) NOT NULL
) ENGINE=InnoDB;

-- Tabla intermedia: inscripción de cada jugador en cada torneo, con sus puntos
CREATE TABLE inscripciones (
    torneo_id INT UNSIGNED NOT NULL,
    jugador_id INT UNSIGNED NOT NULL,
    puntos INT UNSIGNED NOT NULL DEFAULT 0,
    PRIMARY KEY (torneo_id, jugador_id),
    CONSTRAINT fk_inscripcion_torneo FOREIGN KEY (torneo_id)
        REFERENCES torneos(id) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT fk_inscripcion_jugador FOREIGN KEY (jugador_id)
        REFERENCES jugadores(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- Datos de ejemplo
INSERT INTO torneos (id, nombre, juego) VALUES
(1, 'Copa de Invierno', 'Valorant'),
(2, 'Liga Rocket Masters', 'Rocket League'),
(3, 'Open de Lucha', 'Street Fighter 6');

INSERT INTO jugadores (id, nick, pais) VALUES
(1, 'NovaX', 'España'),
(2, 'Kairo', 'Portugal'),
(3, 'Lumen', 'Francia'),
(4, 'ShadowFox', 'España'),
(5, 'Pixelia', 'Italia'),
(6, 'Raptor', 'México');

INSERT INTO inscripciones (torneo_id, jugador_id, puntos) VALUES
(1, 1, 85),
(1, 2, 120),
(1, 4, 40),
(2, 2, 60),
(2, 3, 95),
(2, 5, 110),
(2, 6, 30),
(3, 1, 75),
(3, 4, 150),
(3, 6, 0);
