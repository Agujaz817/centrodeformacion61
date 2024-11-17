<?php
session_start();
require_once '../db.php'; // Asegúrate de que la ruta sea correcta

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
    $cursoId = $_POST['id'];
    
    // Crear una instancia de la clase Database y obtener la conexión
    $db = new Database();
    $conn = $db->getConnection();

    // Actualizar el estado de las inscripciones
    $sql = "UPDATE cursos SET inscripciones_cerradas = 0 WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $cursoId);
    
    if ($stmt->execute()) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
    
    $stmt->close();
    $conn->close();
}
?>