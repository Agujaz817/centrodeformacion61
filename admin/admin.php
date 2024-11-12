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
    header("Location: ../public/index.php"); // Redirigir si no es admin
    exit(); // Terminar el script después de redirigir
}




// Obtener cursos
$sql = "SELECT * FROM cursos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <title>Panel de Administración</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Panel de Administración</h2>
        <h3>Cursos Registrados</h3>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($result->num_rows > 0) {
                    while($row = $result->fetch_assoc()) {
                        echo "<tr>
                                <td>{$row['id']}</td>
                                <td>{$row['titulo']}</td>
                                <td>{$row['descripcion']}</td>
                                <td>
                                    <a href='editar_curso.php?id={$row['id']}' class='btn btn-warning'>Editar</a>
                                    <a href='eliminar_curso.php?id={$row['id']}' class='btn btn-danger'>Eliminar</a>
                                </td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay cursos registrados.</td></tr>";
                }
                $conn->close();
                ?>
            </tbody>
        </table>
        <a href="agregar_curso.php" class=" btn btn-success">Agregar Curso</a>
     
        <a href="../public/index.php" class=" btn btn-success">Ir al inicio</a>
    </div>
</body>
</html>