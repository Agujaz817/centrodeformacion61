<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db.php'; // Asegúrate de que la ruta sea correcta

// Crear una instancia de la clase Database y obtener la conexión
$db = new Database();
$conn = $db->getConnection(); // Obtener la conexión a la base de datos


// Obtener datos del formulario
$id = $_POST['id'];
$titulo = $_POST['titulo'];
$descripcion = $_POST['descripcion'];
if (isset($_FILES['imagen']) && $_FILES['imagen']['error'] == UPLOAD_ERR_OK) {
    $imagen = $_FILES['imagen']['name'];
    // Aquí puedes agregar la lógica para mover la imagen a la carpeta deseada
} else {
    // Si no se subió una nueva imagen, puedes mantener la imagen existente
    $imagen = ''; // O asignar la imagen actual del curso
}

// Actualizar datos en la base de datos
$sql = "UPDATE cursos SET titulo='$titulo', descripcion='$descripcion', imagen='$imagen' WHERE id=$id";

if ($conn->query($sql) === TRUE) {
    echo '<div class="container mt-5">';
                echo '<h2>Curso editado exitosamente.</h2>';
                echo '<a href="./admin.php" class="btn btn-primary">Volver al Panel</a>';
                echo "<br>";
                echo '<a href="./agregar_curso.php" class="btn btn-secondary">Cargar Otro Curso</a>';
                echo '</div>';
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>