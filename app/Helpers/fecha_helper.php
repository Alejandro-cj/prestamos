<?php
function fechaPerzo($fecha){
    $datos = explode('-', $fecha);
    $anio = $datos[0]; 
    $me = ltrim($datos[1], "0"); 
    $dia = $datos[2]; 
    $mes = array("","Enero",
                  "Febrero",
                  "Marzo",
                  "Abril",
                  "Mayo",
                  "Junio",
                  "Julio",
                  "Agosto",
                  "Septiembre",
                  "Octubre",
                  "Noviembre",
                  "Diciembre");
    return $dia." de ". $mes[$me] . " de " . $anio;
}
?>