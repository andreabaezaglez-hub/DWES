<?php

function dump ($var){
    echo '<pre>'.print_r($var, 1). '</pre>';
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

// Leer todas las reservas del CSV
function leerReservas(string $ruta): array
{
    $reservas = [];

    if (!is_file($ruta)) {
        return $reservas;
    }

    $fichero = fopen($ruta, 'r');

    if ($fichero === false) {
        return $reservas;
    }

    $encabezados = fgetcsv($fichero, 0, ',');

    if ($encabezados !== false) {
        $encabezados = array_map('trim', $encabezados);

        while (($fila = fgetcsv($fichero, 0, ',')) !== false) {

            if ($fila === [null] || count($fila) !== count($encabezados)) {
                continue;
            }

            $fila = array_map('trim', $fila);
            $reserva = array_combine($encabezados, $fila);

            if ($reserva !== false && isset($reserva['ID'])) {
                $reservas[] = $reserva;
            }
        }
    }

    fclose($fichero);

    return $reservas;
}

// Calcular el siguiente identificador
function siguienteId(array $reservas): int
{
    $mayor = 0;

    foreach ($reservas as $reserva) {
        $id = $reserva['ID'] ?? '';

        if (ctype_digit((string)$id)) {
            $id = (int)$id;

            if ($id > $mayor) {
                $mayor = $id;
            }
        }
    }

    return $mayor + 1;
}

// Validar los datos de la reserva
function validarReserva(
    array $datos,
    array $pistas,
    array $horas,
    array $materiales,
    array $reservas,
    array &$errores
): bool {

    // Comprobar el nombre
    $longitud = preg_match_all('/./us', $datos['nombre']);

    if ($longitud === false || $longitud < 2 || $longitud > 60) {
        $errores['nombre'] = 'El nombre debe tener entre 2 y 60 caracteres.';
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
        $fechaObjeto = DateTimeImmutable::createFromFormat('!Y-m-d', $fecha);

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
        $errores['fecha'] = 'La fecha debe estar entre hoy y los próximos 14 días.';
    }

    // Comprobar la hora
    if (!in_array($datos['hora'], $horas, true)) {
        $errores['hora'] = 'Selecciona una hora válida.';
    }

    // Comprobar el material
    foreach ($datos['material'] as $elemento) {
        if (!in_array($elemento, $materiales, true)) {
            $errores['material'] = 'Has seleccionado un material no válido.';
            break;
        }
    }

    // Comprobar las normas
    if (!$datos['normas']) {
        $errores['normas'] = 'Debes aceptar las normas.';
    }

    // Comprobar que no exista otra reserva para esa pista, fecha y hora
    if (
        !isset($errores['pista'])
        && !isset($errores['fecha'])
        && !isset($errores['hora'])
    ) {
        foreach ($reservas as $reserva) {
            if (
                ($reserva['PISTA'] ?? '') === $datos['pista']
                && ($reserva['FECHA'] ?? '') === $datos['fecha']
                && ($reserva['HORA'] ?? '') === $datos['hora']
            ) {
                $errores['hora'] = 'Esa pista ya está reservada en esa fecha y hora.';
                break;
            }
        }
    }

    return empty($errores);
}

?>