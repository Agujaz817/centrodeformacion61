<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db.php';

// Iniciar sesión si no está activa


// Verificar si se han enviado los datos del formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $usuario = $_POST['usuario'];
    $contraseña = $_POST['contraseña'];

    // Crear una instancia de la clase Database
    $db = new Database();
    $conexion = $db->getConnection();

    // Preparar la consulta SQL
    $query = "SELECT * FROM usuarios WHERE usuario = ? AND contraseña = ?";
    
    // Preparar la sentencia
    if ($stmt = $conexion->prepare($query)) {
        // Vincular parámetros
        $stmt->bind_param("ss", $usuario, $contraseña);
        
        // Ejecutar la sentencia
        $stmt->execute();
        
        // Obtener el resultado
        $resultado = $stmt->get_result();
        
        // Verificar si se encontró un usuario
        if ($resultado->num_rows > 0) {
            // Obtener los datos del usuario
            $filas = $resultado->fetch_assoc();
            $_SESSION['id_cargo'] = $filas['id_cargo'];
            
            // Redirigir según el cargo del usuario
            if ($filas['id_cargo'] == 1) { // Administrador
                $_SESSION['isAdmin'] = true;
                header("Location: ../admin/admin.php");
            } elseif ($filas['id_cargo'] == 2) { // Cliente
                header("Location: cliente.php");
            }
            exit();
        } else {
            // Usuario o contraseña incorrectos
            echo "Usuario o contraseña incorrectos.";
        }
        
        // Cerrar la sentencia
        $stmt->close();
    } else {
        echo "Error en la preparación de la consulta: " . $conexion->error;
    }

    // Cerrar la conexión
    $db->closeConnection();
}
?>