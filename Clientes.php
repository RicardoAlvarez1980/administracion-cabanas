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
    public function contieneNombre($parametroBusqueda)
    {
        // Aquí se verifica si el nombre del cliente contiene el parámetro de búsqueda
        // La función stripos realiza una búsqueda insensible a mayúsculas y minúsculas
        return stripos($this->nombre, $parametroBusqueda) !== false;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

        /**
         * Set the value of nombre
         *
         * @return  self
         */ 
        public function setNombre($nombre)
        {
                $this->nombre = $nombre;

                return $this;
        }

        /**
         * Set the value of direccion
         *
         * @return  self
         */ 
        public function setDireccion($direccion)
        {
                $this->direccion = $direccion;

                return $this;
        }

        /**
         * Set the value of telefono
         *
         * @return  self
         */ 
        public function setTelefono($telefono)
        {
                $this->telefono = $telefono;

                return $this;
        }

        /**
         * Set the value of email
         *
         * @return  self
         */ 
        public function setEmail($email)
        {
                $this->email = $email;

                return $this;
        }
}

?>