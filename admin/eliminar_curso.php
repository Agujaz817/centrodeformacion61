<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión si no está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../db.php'; // Asegúrate de que la ruta sea correcta


// Crear una instancia de la clase Database
$db = new Database();
$conn = $db->getConnection(); // Obtener la conexión a la base de datos




// Obtener ID del curso a eliminar
$id = $_GET['id'];

// Eliminar curso de la base de datos
$sql = "DELETE FROM cursos WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo '<div class="container mt-5">';
                echo '<h2>Curso eliminado exitosamente.</h2>';
                echo '<a href="./admin.php" class="btn btn-primary">Volver al Panel</a>';
                echo "<br>";
                echo '<a href="./agregar_curso.php" class="btn btn-secondary">Cargar Otro Curso</a>';
                echo '</div>';
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>