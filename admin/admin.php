<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../db.php'; // Asegúrate de que la ruta sea correcta
require_once '../public/validar.php'; // Asegúrate de que la ruta sea correcta

$db = new Database();
$conn = $db->getConnection();

if (!isset($_SESSION['isAdmin']) || $_SESSION['isAdmin'] !== true) {
    header("Location: ../public/index.php");
    exit();
}

$sql = "SELECT * FROM cursos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/styles.css">
    <link rel="shortcut icon" href="../public/IMG/favicon.png" type="image/x-icon">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Panel de Administración</title>
</head>
<body>
    <div class="container mt-5">
        <h2>Panel de Administración CFP 61</h2>
        <h3><strong>Trayectos Registrados</strong></h3>
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
                                    <a href='eliminar_curso.php?id={$row['id']}' class='btn btn-danger'>Eliminar</a>";

                        // Comprobar si las inscripciones están cerradas
                        if ($row['inscripciones_cerradas']) {
                            // Si las inscripciones están cerradas, mostrar "Abrir Inscripción"
                            echo "<button class='btn btn-success' onclick='abrirInscripcion({$row['id']})'>Abrir Inscripción</button>";
                        } else {
                            // Si las inscripciones están abiertas, mostrar "Cerrar Inscripción"
                            echo "<a href='#' class='btn btn-info' data-bs-toggle='offcanvas' data-bs-target='#offcanvasFecha' aria-controls='offcanvasFecha' onclick='setCursoId({$row['id']})'>Cerrar Inscripción</a>";
                        }

                        echo "</td>
                            </tr>";
                    }
                } else {
                    echo "<tr><td colspan='4'>No hay cursos registrados.</td></tr>";
                }
                ?>
            </tbody>
        </table>
        <a href="agregar_curso.php" class="btn btn-success">Agregar Trayecto</a>
        <a href="../public/index.php" class="btn btn-success">Ir al inicio</a>
        <br>
        <?php
        include_once './eventos.php';
        ?>
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasFecha" aria-labelledby="offcanvasFechaLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasFechaLabel">Establecer Fecha de Apertura</h5>
                <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body">
                <form id="fechaForm" action="cerrar_inscripcion.php" method="POST">
                    <input type="hidden" name="id" id="cursoId" value="">
                    <div class="mb-3">
                        <label for="fecha_apertura" class="form-label"> Fecha de Apertura</label>
                        <input type="date" class="form-control" id="fecha_apertura" name="fecha_apertura" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Establecer Fecha</button>
                </form>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function abrirInscripcion(cursoId) {
            $.ajax({
                url: 'abrir_inscripcion.php',
                type: 'POST',
                data: { id: cursoId },
                success: function(response) {
                    const result = JSON.parse(response);
                    if (result.success) {
                        alert("Inscripciones abiertas para el curso ID: " + cursoId);
                        location.reload(); // Recargar la página para ver los cambios
                    } else {
                        alert("Error al abrir inscripciones: " + result.error);
                    }
                },
                error: function(error) {
                    console.error("Error al abrir inscripciones:", error);
                }
            });
        }

        function setCursoId(cursoId) {
            document.getElementById('cursoId').value = cursoId;
        }
    </script>
</body>
</html>