<?php
require_once 'Conexion.php';

class Cabanas {
public $numero;
public $capacidad;
public $descripcion;
public $costoDiario;
public $cabanas = [];


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

//Probamos agregarCabana() dentro de la clase para poder llamarla (Pertenece a Menu.php)
function agregarCabana()
{
    global $cabanas;

    echo "\nAgregar Cabaña\n";
    echo "Ingrese el número de la cabaña: ";
    $numero = intval(trim(fgets(STDIN)));

    // Verificar si el número de cabaña ya existe
    foreach ($cabanas as $cabana) {
        if ($cabana->getNumero() === $numero) {
            echo "Ya existe una cabaña con ese número. Intente nuevamente.\n";
            return;
        }
    }

    echo "Ingrese la capacidad de la cabaña: ";
    $capacidad = intval(trim(fgets(STDIN)));
    echo "Ingrese la descripción de la cabaña: ";
    $descripcion = trim(fgets(STDIN));
    echo "Ingrese el costo diario de la cabaña: ";
    $costoDiario = floatval(trim(fgets(STDIN)));

    $cabana = new Cabanas($numero, $capacidad, $descripcion, $costoDiario);
    $cabanas[] = $cabana;

    echo "La cabaña nº " . $cabana->getNumero() . " fue agregada exitosamente.\n";
}
}
