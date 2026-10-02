<?php


function dump($var){
  echo '<pre>'.print_r($var,1).'</pre>';
}


function getCSVContentInArray(String $rutaCSV, String|null $nombreClave=null){
  
  //TODO: Mejora esta función para poder elegir el campo que funciona del clave;

  $contenidoArchivo = file_get_contents($rutaCSV);
  $contenidoArchivo = trim($contenidoArchivo);
  $lineasCSV =explode("\n",$contenidoArchivo);

  $encabezadoCadena = reset($lineasCSV);
  $encabezadoArray = explode(',', $encabezadoCadena);

  $encabezadoArrayNuevo = array_map('trim',$encabezadoArray);

  $arrayResultados = [];

  foreach($lineasCSV as $clave => $linea){
    if($clave != 0){
      $camposLinea = explode(',', $linea); 

      $elemento = array_combine($encabezadoArrayNuevo,$camposLinea);
      
      if(isset($nombreClave)){
        $valorClave = trim($elemento[$nombreClave]);
        $arrayResultados[$valorClave]= $elemento;
      }else{
        $arrayResultados[]= $elemento; 
      }
      
    }  
  }
  return $arrayResultados;
}


function getCochesMarkupFromData(array $data){
  $output = '';
   
  $output .= '<ul>'; 
  
  foreach($data as $clave => $valor){
    $output.='<li>';
    $output.=$valor['marca'];
    $output.='</li>';
  }

  $output .= '</ul>'; 
  
  return $output;
}

$data = [
    [
        'marca' => 'Peugeot',
        'modelo' => '208',
        'imagen' => 'img/208.avif'
    ],
    [
        'marca' => 'Skoda',
        'modelo' => 'Karoq',
        'imagen' => 'img/karoq.jpg'
    ],
    [
        'marca' => 'Dacia',
        'modelo' => 'Sandero',
        'imagen' => 'img/sandero.jpg'
    ]
];


function imprimirImagenes($data) {
    $output = '<ul>';

    foreach ($data as $valor) {

        if (file_exists($valor['imagen'])) {
            $imagen = $valor['imagen'];
        } else {
            $imagen = 'img/coche.webp';
        }

        $output .= '<li>';
        $output .= $valor['marca'] . ' - ' . $valor['modelo'];
        $output .= '<br>';
        $output .= '<img src="' . $imagen . '" alt="' . $valor['modelo'] . '">';
        $output .= '</li>';
    }

    $output .= '</ul>';

    return $output;
}

echo imprimirImagenes($data);


