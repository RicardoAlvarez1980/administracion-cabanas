<?php
require_once 'menu.php';
require_once 'data.php';
class Reservas
{   

    private $numero;
    private $cliente;
    private $cabana;
    private $fechaInicio;
    private $fechaFin;
    private $reservas = [];
    public function __construct($numero, $cliente, $cabana, $fechaInicio, $fechaFin)
    {
        $this->numero = $numero;
        $this->cliente = $cliente;
        $this->cabana = $cabana;
        $this->fechaInicio = $fechaInicio;
        $this->fechaFin = $fechaFin;
    }

    public function getNumero()
    {
        return $this->numero;
    }

    public function getCliente()
    {
        return $this->cliente;
    }

    public function getCabana()
    {
        return $this->cabana;
    }

    public function getFechaInicio()
    {
        return $this->fechaInicio;
    }

    public function getFechaFin()
    {
        return $this->fechaFin;
    }

    public function setFechaInicio($fechaInicio)
    {
        $this->fechaInicio = $fechaInicio;
    }

    public function setFechaFin($fechaFin)
    {
        $this->fechaFin = $fechaFin;
    }

    public function getCostoTotal()
    {
        $costoDiario = $this->cabana->getCostoDiario();
        $diasReserva = $this->calcularDiasReserva();
        return $costoDiario * $diasReserva;
    }

    private function calcularDiasReserva()
    {
        $inicio = strtotime($this->fechaInicio);
        $fin = strtotime($this->fechaFin);
        $diferencia = $fin - $inicio;
        $diasReserva = floor($diferencia / (60 * 60 * 24));
        return $diasReserva;
    }

    public static function guardarReservas($reservas) {
        file_put_contents('reservas.json', json_encode($reservas, JSON_PRETTY_PRINT));
    }

    public static function cargarReservas() {
        if (file_exists('reservas.json')) {
            $jsonDatos = file_get_contents('reservas.json');
            return json_decode($jsonDatos);
        }
        return [];
    }

}

?>