<?php

function guardarDatos($archivo, $datos) {
    $jsonDatos = json_encode($datos, JSON_PRETTY_PRINT);
    file_put_contents($archivo, $jsonDatos);
}

function cargarDatos($archivo) {
    if (file_exists($archivo)) {
        $jsonDatos = file_get_contents($archivo);
        return json_decode($jsonDatos, true);
    }
    return [];
}

?>