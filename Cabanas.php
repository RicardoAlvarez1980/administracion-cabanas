<?php
require_once 'data.php';

class Cabanas
{
private $numero;
private $capacidad;
private $descripcion;
private $costoDiario;

public function __construct($numero, $capacidad, $descripcion, $costoDiario)
{
$this->numero = $numero;
$this->capacidad = $capacidad;
$this->descripcion = $descripcion;
$this->costoDiario = $costoDiario;
}

public function getNumero()
{
return $this->numero;
}

public function getCapacidad()
{
return $this->capacidad;
}

public function getDescripcion()
{
return $this->descripcion;
}

public function getCostoDiario()
{
return $this->costoDiario;
}

public function setCapacidad($capacidad)
{
$this->capacidad = $capacidad;
}

public function setDescripcion($descripcion)
{
$this->descripcion = $descripcion;
}

public function setCostoDiario($costoDiario)
{
$this->costoDiario = $costoDiario;
}

public static function guardarCabanas($cabanas) {
    file_put_contents('cabanas.json', json_encode($cabanas, JSON_PRETTY_PRINT));
}

public static function cargarCabanas() {
    if (file_exists('cabanas.json')) {
        return json_decode(file_get_contents('cabanas.json'), true);
    }
    return [];
}


}
