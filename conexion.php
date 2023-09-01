<?php
class Conexion {
    private $host = 'batyr.db.elephantsql.com';
    private $usuario = 'nglknejg';
    private $contrasena = 'CGRGjFyVHvvVbbcGMQeIgRXR4RalYnrZ';
    private $base_de_datos = 'nglknejg';
    private $conexion;

    private static $instancia;

    private function __construct() {
        try {
            $this->conexion = new PDO(
                "pgsql:host={$this->host};dbname={$this->base_de_datos}",
                $this->usuario,
                $this->contrasena
            );
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