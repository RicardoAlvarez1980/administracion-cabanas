<?php
class Clientes
{
    private $id;
    private $nombre;
    private $direccion;
    private $telefono;
    private $email;
    private $reservas;

    public function __construct($id, $nombre, $direccion, $telefono, $email)
    {
        $this->id = $id;
        $this->nombre = $nombre;
        $this->direccion = $direccion;
        $this->telefono = $telefono;
        $this->email = $email;
        $this->reservas = [];
    }

    public function getId()
    {
        return $this->id;
    }

    public function getNombre()
    {
        return $this->nombre;
    }

    public function getDireccion()
    {
        return $this->direccion;
    }

    public function getTelefono()
    {
        return $this->telefono;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getReservas()
    {
        return $this->reservas;
    }

    public function agregarReserva($reserva)
    {
        $this->reservas[] = $reserva;
    }

    public function listarReservas()
    {
        if (empty($this->reservas)) {
            echo "El cliente no tiene reservas.\n";
        } else {
            echo "Reservas del Cliente " . $this->nombre . ":\n";
            foreach ($this->reservas as $reserva) {
                echo "Número de Reserva: " . $reserva->getNumero() . "\n";
                echo "Cabaña: " . $reserva->getCabana()->getNumero() . "\n";
                echo "Fecha de inicio: " . $reserva->getFechaInicio() . "\n";
                echo "Fecha de fin: " . $reserva->getFechaFin() . "\n";
                echo "Costo de la reserva: " . $reserva->getCostoTotal() . "\n";
                echo "---------------------------\n";
            }
        }
    }
}

?>