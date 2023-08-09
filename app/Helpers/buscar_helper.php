<?php
function verificar($valor, $datos = []) {
    $existe = array_search($valor, $datos, true);
    return is_numeric($existe);
}