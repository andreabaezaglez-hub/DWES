<?php

function dump($var)
{
    echo '<pre>' . print_r($var, 1) . '</pre>';
}



// Limpiamos y preparamos la entrada del usuario antes de procesarla.
function e($texto): string
{
    return htmlspecialchars(
        (string)$texto,
        ENT_QUOTES | ENT_SUBSTITUTE,
        'UTF-8'
    );
}

// Leer un campo de texto de forma segura
function campoTexto(array $datos, string $campo): string
{
    $valor = $datos[$campo] ?? '';

    if (!is_string($valor)) {
        return '';
    }

    return trim($valor);
}

// Leer los materiales seleccionados
function campoLista(array $datos, string $campo): array
{
    $valor = $datos[$campo] ?? [];

    if (!is_array($valor)) {
        return ['__valor_invalido__'];
    }

    foreach ($valor as $elemento) {
        if (!is_string($elemento)) {
            return ['__valor_invalido__'];
        }
    }

    return $valor;
}

// Validar los datos de la reserva
function validarReserva(
    array $datos,
    array $pistas,
    array $horas,
    array $materiales,
    mysqli $connexion,
    array &$errores
): bool {

    // Comprobar el nombre
    $longitud = preg_match_all('/./us', $datos['nombre']);

    if ($longitud === false || $longitud < 2 || $longitud > 60) {
        $errores['nombre'] =
            'El nombre debe tener entre 2 y 60 caracteres.';
    }

    // Comprobar el email
    if (filter_var($datos['email'], FILTER_VALIDATE_EMAIL) === false) {
        $errores['email'] = 'Introduce un email válido.';
    }

    // Comprobar la pista
    if (!in_array($datos['pista'], $pistas, true)) {
        $errores['pista'] = 'Selecciona una pista válida.';
    }

    // Comprobar la fecha
    $hoy = date('Y-m-d');
    $limite = date('Y-m-d', strtotime('+14 days'));
    $fecha = $datos['fecha'];

    $fechaCorrecta = false;

    if (preg_match('/^\d{4}-\d{2}-\d{2}$/D', $fecha)) {
        $fechaObjeto = DateTimeImmutable::createFromFormat(
            '!Y-m-d',
            $fecha
        );

        if (
            $fechaObjeto !== false
            && $fechaObjeto->format('Y-m-d') === $fecha
        ) {
            $fechaCorrecta = true;
        }
    }

    if (
        !$fechaCorrecta
        || $fecha < $hoy
        || $fecha > $limite
    ) {
        $errores['fecha'] =
            'La fecha debe estar entre hoy y los próximos 14 días.';
    }

    // Comprobar la hora
    if (!in_array($datos['hora'], $horas, true)) {
        $errores['hora'] = 'Selecciona una hora válida.';
    }

    // Comprobar los materiales
    foreach ($datos['material'] as $elemento) {
        if (!in_array($elemento, $materiales, true)) {
            $errores['material'] =
                'Has seleccionado un material no válido.';
            break;
        }
    }

    // Comprobar las normas
    if (!$datos['normas']) {
        $errores['normas'] = 'Debes aceptar las normas.';
    }

    // Comprobar disponibilidad en MySQL
    if (
        !isset($errores['pista'])
        && !isset($errores['fecha'])
        && !isset($errores['hora'])
    ) {
        $sql = "
            SELECT id
            FROM reservas
            WHERE pista = ?
              AND fecha = ?
              AND hora = ?
            LIMIT 1
        ";

        $stmt = $connexion->prepare($sql);

        if ($stmt === false) {
            throw new RuntimeException(
                'Error al preparar la consulta: ' . $connexion->error
            );
        }

        $hora = $datos['hora'];

        $stmt->bind_param(
            'sss',
            $datos['pista'],
            $datos['fecha'],
            $hora
        );

        if (!$stmt->execute()) {
            $error = $stmt->error;
            $stmt->close();

            throw new RuntimeException(
                'Error al comprobar la disponibilidad: ' . $error
            );
        }

        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $errores['hora'] =
                'Esa pista ya está reservada en esa fecha y hora.';
        }

        $stmt->close();
    }

    return empty($errores);
}


/**
 * Filtrar reservas utilizando parámetros de la query string.
 */
function filterReservations(array $reservas, array $filtros): array
{
    $reservasFiltradas = [];

    $pista = campoTexto($filtros, 'pista');
    $fecha = campoTexto($filtros, 'fecha');

    foreach ($reservas as $reserva) {

        // Comprobar si coincide la pista
        $coincidePista = (
            $pista === ''
            || ($reserva['PISTA'] ?? '') === $pista
        );

        // Comprobar si coincide la fecha
        $coincideFecha = (
            $fecha === ''
            || ($reserva['FECHA'] ?? '') === $fecha
        );

        // Añadir la reserva si cumple ambos filtros
        if ($coincidePista && $coincideFecha) {
            $reservasFiltradas[] = $reserva;
        }
    }

    return $reservasFiltradas;
}

?>