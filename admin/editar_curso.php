<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db.php'; // Asegúrate de que la ruta sea correcta
require_once '../public/validar.php'; // Asegúrate de que la ruta sea correcta

// Crear una instancia de la clase Database y obtener la conexión
$db = new Database();
$conn = $db->getConnection(); // Obtener la conexión a la base de datos

// Verificar si el usuario es un administrador
if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("Location: /index.php"); // Redirigir si no es admin
    exit(); // Terminar el script después de redirigir
}

// Obtener ID del curso a editar
$id = $_GET['id'];
$sql = "SELECT * FROM cursos WHERE id=$id";
$result = $conn->query($sql);
$curso = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Editar Curso</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Curso</h2>
        <form action="procesar_editar_curso.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?php echo $curso['id']; ?>">
            <div class="form-group">
                <label for="titulo">Título</label>
                <input type="text" class="form-control" id="titulo" name="titulo" value="<?php echo $curso['titulo']; ?>" required>
            </div>
            <div ```php
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <textarea class="form-control" id="descripcion" name="descripcion" required><?php echo $curso['descripcion']; ?></textarea>
            </div>
            <div class="form-group">
                <label for="foto">Foto del Curso</label>
                <input type="file" class="form-control-file" id="foto" name="foto">
                <img src="../public/IMG/<?php echo $curso['imagen']; ?>" alt="Imagen actual" style="max-width: 10%; margin-top: 10px;">
                <p>Imagen anterior</p>
            </div>
            <button type="submit" class="btn btn-primary">Actualizar Curso</button>
        </form>
    </div>
</body>
</html>