<?php
require_once '../db.php'; // Asegúrate de que la ruta sea correcta

// Crear una instancia de la clase Database
$db = new Database();
$conn = $db->getConnection(); // Obtener la conexión a la base de datos

// Obtener cursos para el formulario de inscripción
$sql = "SELECT id, titulo FROM cursos WHERE inscripciones_cerradas = 0"; // Consulta para obtener los títulos de los cursos
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}

$cursos = [];
if ($result->num_rows > 0) {
    while ($curso = $result->fetch_assoc()) {
        $cursos[] = $curso; // Almacena los cursos en un array
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="shortcut icon" href="./IMG/lococfp61.png" type="image/x-icon">
    <title>Inscripción a Curso</title>
</head>
<body>
    <div class="container mt-5 contact-form">
        <h2>Formulario de Inscripción a Curso</h2>
        <form action="https://formsubmit.co/cformprof61.lacriolla@gmail.com" method="POST">
            <div class="form-group">
                <label for="nombre">Nombre Completo</label>
                <input type="text" class="form-control" id="nombre" name="nombre" required>
            </div>
            <div class="form-group">
                <label for="telefono">Número de Teléfono</label>
                <input type="tel" class="form-control" id="telefono" name="telefono" required>
            </div>
            <div class="form-group">
                <label for="email">Correo Electrónico</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="curso">Curso de Interés</label>
                <select class="form-control" id="curso" name="curso" required>
                    <option value="">Seleccione un curso</option>
                    <?php foreach ($cursos as $curso): ?>
                        <option value="<?php echo htmlspecialchars($curso['id']); ?>">
                            <?php echo htmlspecialchars($curso['titulo']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Enviar Inscripción</button>
        </form>
    </div>
    <div class="row container text-center">
        <div class="col-sm text-center" style="margin-top: 20px; align-items:center; left: 30%; top: -10px;">
            <img src="./IMG/lococfp61.png" alt="" srcset="" class="img-fluid">
        </div>
    </div>
</body>
</html>