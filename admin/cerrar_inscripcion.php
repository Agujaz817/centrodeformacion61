<?php require_once '../db.php'; // Asegúrate de que la ruta sea correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id']) && isset($_POST['fecha_apertura'])) {
    $cursoId = $_POST['id'];
    $fechaApertura = $_POST['fecha_apertura'];
    
    // Crear una instancia de la clase Database
    $db = new Database();
    $conn = $db->getConnection();
    
    // Actualizar la fecha de apertura y cerrar inscripciones
    $sql = "UPDATE cursos SET fecha_apertura = ?, inscripciones_cerradas = 1 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $fechaApertura, $cursoId);
    
    if ($stmt->execute()) {
        header("Location: admin.php?mensaje=Fecha de apertura y cierre de inscripciones establecidos con éxito");
    } else {
        echo "Error al establecer la fecha de apertura: " . $conn->error;
    }
}
?>