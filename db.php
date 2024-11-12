<?php

// Definición de constantes para las credenciales de la base de datos
define('DB_SERVER', 'blmew6j0cu35083rys98-mysql.services.clever-cloud.com');
define('DB_USERNAME', 'uu2fztm52s70l8e9');
define('DB_PASSWORD', '16rSqQZlxMOpqCOpuhll');
define('DB_DATABASE', 'blmew6j0cu35083rys98');

class Database {
    private $conexion;

    public function __construct() {
        $this->connect();
    }

    private function connect() {
        $this->conexion = new mysqli(DB_SERVER, DB_USERNAME, DB_PASSWORD, DB_DATABASE);

        // Verificar la conexión
        if ($this->conexion->connect_error) {
            die("Conexión fallida: " . $this->conexion->connect_error);
        }
    }

    public function getConnection() {
        return $this->conexion;
    }

    public function closeConnection() {
        $this->conexion->close();
    }
}
?>