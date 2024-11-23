<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db.php'; // Asegúrate de que la ruta sea correcta

// Crear una instancia de la clase Database y obtener la conexión
$db = new Database();
$conn = $db->getConnection(); // Obtener la conexión a la base de datos

// Verificar si se ha enviado el formulario
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener datos del formulario
    $titulo = $_POST['nombre_curso']; // Cambiado a 'titulo'
    $descripcion = $_POST['descripcion'];

    // Manejo de la carga de la imagen
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['foto']['tmp_name'];
        $fileName = $_FILES['foto']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));

        // Definir la ruta donde se guardará la imagen
        $uploadFileDir = '../public/IMG/';
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension; // Generar un nuevo nombre único
        $dest_path = $uploadFileDir . $newFileName;

        // Mover el archivo a la carpeta de destino
        if(move_uploaded_file($fileTmpPath, $dest_path)) {
            // Guardar la información del curso en la base de datos
            $sql = "INSERT INTO cursos (titulo, descripcion, imagen) VALUES ('$titulo', '$descripcion', '$newFileName')";
            if ($conn->query($sql) === TRUE) {
                header("Location: ./admin.php");
            } else {
                echo "Error al agregar el curso: " . $conn->error;
            }
        } else {
            echo "Error al cargar la imagen.";
        }
    } else {
        echo "Error: No se ha subido ninguna imagen.";
    }
} else {
    echo "Error: Método de solicitud no válido.";
}
$conn->close();
?>