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
$newFileName = null; // Inicializa la variable para el nombre de la imagen

// Obtener la imagen actual de la base de datos
$sqlCurrentImage = "SELECT imagen FROM cursos WHERE id=?";
$stmtCurrent = $conn->prepare($sqlCurrentImage);
$stmtCurrent->bind_param("i", $id);
$stmtCurrent->execute();
$stmtCurrent->bind_result($currentImage);
$stmtCurrent->fetch();
$stmtCurrent->close();

// Verificar si se subió una nueva imagen
if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
    $fileTmpPath = $_FILES['foto']['tmp_name'];
    $fileName = $_FILES['foto']['name'];
    $fileNameCmps = explode(".", $fileName);
    $fileExtension = strtolower(end($fileNameCmps));

    // Definir la ruta donde se guardará la imagen
    $uploadFileDir = '../public/IMG/';
    $newFileName = md5(time() . $fileName) . '.' . $fileExtension; // Generar un nuevo nombre único
    $dest_path = $uploadFileDir . $newFileName;

    // Mover el archivo subido a la carpeta de destino
    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        // Si la imagen se subió correctamente, se puede eliminar la imagen anterior
        if ($currentImage && file_exists($uploadFileDir . $currentImage)) {
            unlink($uploadFileDir . $currentImage); // Eliminar la imagen anterior
        }
        header("Location: ./admin.php");
    } else {
        echo "Error al mover el archivo subido.";
        exit();
    }
} else {
    // Si no se subió una nueva imagen, usar la imagen actual
    $newFileName = $currentImage;
}

// Actualizar los datos del curso en la base de datos
$sqlUpdate = "UPDATE cursos SET titulo=?, descripcion=?, imagen=? WHERE id=?";
$stmtUpdate = $conn->prepare($sqlUpdate);
$stmtUpdate->bind_param("sssi", $titulo, $descripcion, $newFileName, $id);

if ($stmtUpdate->execute()) {
    header("Location: ./admin.php");
} else {
    echo "Error al actualizar el curso: " . $stmtUpdate->error;
}

$stmtUpdate->close();
$conn->close();
?>