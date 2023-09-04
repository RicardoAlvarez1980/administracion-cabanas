<?php
class Conexion {
    private $host = 'berry.db.elephantsql.com';
    private $usuario = 'rhelogcx';
    private $contrasena = 'OMEaHkFP6WpmnUOkcgZwOEUtVe_A9TaB';
    private $base_de_datos = 'rhelogcx';
    private $conexion;

    private static $instancia;

    private function __construct() {
        try {
            $this->conexion = new PDO(
                "pgsql:host={$this->host};dbname={$this->base_de_datos}",
                $this->usuario,
                $this->contrasena
            );
            // Si llegamos aquí, la conexión fue exitosa
            echo "Conexión exitosa a la base de datos.";
        } catch (PDOException $e) {
            die("Error de conexión a la base de datos: " . $e->getMessage());
        }
    }

    public static function obtenerInstancia() {
        if (self::$instancia == null) {
            self::$instancia = new Conexion();
        }
        return self::$instancia;
    }

    public function obtenerConexion() {
        return $this->conexion;
    }
}

?>