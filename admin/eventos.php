<?php

if (!isset($_SESSION['isAdmin']) || !$_SESSION['isAdmin']) {
    header('Location: ../index.php'); // Redirigir si no es admin
    exit();
}

require_once '../db.php'; // Conectar a la base de datos

$db = new Database();
$conn = $db->getConnection();

// Manejar la creación de un nuevo evento
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['agregar_evento'])) {
    $titulo = $_POST['titulo'];
    $fecha_hora = $_POST['fecha_hora'];
    $descripcion = $_POST['descripcion'];

    $sql = "INSERT INTO eventos (titulo, fecha_hora, descripcion) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('sss', $titulo, $fecha_hora, $descripcion);
    $stmt->execute();
}

// Manejar la actualización de un evento existente
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['editar_evento'])) {
    $id = $_POST['id'];
    $titulo = $_POST['titulo'];
    $fecha_hora = $_POST['fecha_hora'];
    $descripcion = $_POST['descripcion'];

    $sql_update = "UPDATE eventos SET titulo = ?, fecha_hora = ?, descripcion = ? WHERE id = ?";
    $stmt_update = $conn->prepare($sql_update);
    $stmt_update->bind_param('sssi', $titulo, $fecha_hora, $descripcion, $id);
    $stmt_update->execute();
}

// Manejar la eliminación de un evento
if (isset($_GET['eliminar'])) {
    $id = $_GET['eliminar'];
    $sql = "DELETE FROM eventos WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('i', $id);
    $stmt->execute();
}

// Obtener todos los eventos
$sql = "SELECT * FROM eventos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/css/bootstrap.min.css">
    <title>Administración de Eventos</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Agregar Evento</h2>
        <form method="POST" class="mb-4">
            <div class="mb-3">
                <label for="titulo" class="form-label">Título del evento</label>
                <input type="text" name="titulo" id="titulo" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="fecha_hora" class="form-label">Fecha y Hora</label>
                <input type="datetime-local" name="fecha_hora" id="fecha_hora" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="descripcion" class="form-label">Descripción del evento</label>
                <textarea name="descripcion" id="descripcion" class="form-control" rows="3" required></textarea>
            </div>
            <button type="submit" name="agregar_evento" class="btn btn-primary">Agregar Evento</button>
        </form>

        <h2>Eventos Existentes</h2>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Fecha y Hora</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($evento = $result->fetch_assoc()): ?>
                <tr>
                    <td><?php echo htmlspecialchars($evento['titulo']); ?></td>
                    <td><?php echo htmlspecialchars($evento['fecha_hora']); ?></td>
                    <td><?php echo htmlspecialchars($evento['descripcion']); ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="id" value="<?php echo $evento['id']; ?>">
                            <input type="text" name="titulo" value="<?php echo htmlspecialchars($evento['titulo']); ?>" required>
                            <input type="datetime-local" name ="fecha_hora" value="<?php echo date('Y-m-d\TH:i', strtotime($evento['fecha_hora'])); ?>" required>
                            <textarea name="descripcion" required><?php echo htmlspecialchars($evento['descripcion']); ?></textarea>
                            <button type="submit" name="editar_evento" class="btn btn-warning btn-sm">Actualizar</button>
                        </form>
                        <a href="?eliminar=<?php echo $evento['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('¿Estás seguro de que deseas eliminar este evento?');">Eliminar</a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.min.js"></script>
</body>
</html>