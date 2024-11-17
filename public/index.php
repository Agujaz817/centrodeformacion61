<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Iniciar sesión si no está activa
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once '../db.php'; // Asegúrate de que la ruta sea correcta
require_once './validar.php'; // Asegúrate de que la ruta sea correcta

// Crear una instancia de la clase Database
$db = new Database();
$conn = $db->getConnection(); // Obtener la conexión a la base de datos

// Obtener cursos destacados
$sql = "SELECT * FROM cursos LIMIT 15"; // Cambia el límite según sea necesario
$result = $conn->query($sql);

// Verificar si la consulta fue exitosa
if (!$result) {
    die("Error en la consulta: " . $conn->error);
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="./styles/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Montserrat:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <title>Centro de Formación Profesional</title>
</head>
<body>
    <header>
    

<nav class="navbar navbar-light">
    <a class="" href="#">
        <img src="./IMG/lococfp61.png" alt="Logo" width="300px" style="margin-bottom: -30px; margin-top: -30px;"> 
    </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav mr-auto">
            <li class="nav-item">
           
                <a class="nav-link" href="index.php">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="trayectos.php">Trayectos Formativos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="sobre_nosotros.php">Sobre Nosotros</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="contacto.php">Contacto</a>
            </li>
            <?php if (isset($_SESSION['isAdmin']) && $_SESSION['isAdmin']): ?>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Administración
                    </a>
                    <div class="dropdown-menu" aria-labelledby="navbarDropdown">
                        <a class="dropdown-item" href="../admin/admin.php">Ver Cursos</a>
                        <a class="dropdown-item" href="../admin/agregar_curso.php">Agregar Curso</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="../admin/cerrar_sesion.php">Cerrar Sesión</a>
                    </div>
                </li>
            <?php endif; ?>
        </ul>
        <div class="social-icons">
        <a href="https://www.instagram.com" target="_blank" style="color:  #152372; ">
            <i class="bi bi-instagram" style="font-size: 30px;"></i>
        </a>
        <a href="https://www.facebook.com" target="_blank" style="color:  #152372;">
            <i class="bi bi-facebook" style="font-size: 30px;"></i>
        </a>
        <p style=" position: relative; top: 10px;  " ><strong>¡Seguinos para enterarte de todo! </strong></p>
    </div>
    </div>
   
</nav>
    </header>

    <main class="container">
        <div class="container-hero">
            <div class="bg-contrast"></div>
            <h1 class="hero">Bienvenido al Centro de Formación Profesional</h1>
        </div>
     

    
        <p class="slogan">Impulsá tus habilidades,<br> <strong>el momento es ahora.</strong></p>
        <h2 style="text-align: center; color: #cde3ef;font-weight:bold; border:#152372 solid 4px; border-radius:20px; background-color: #152372; margin-bottom:20px;">Trayectos:</h2>
        <div class="row">
            <?php
            if ($result->num_rows > 0) {
                while ($curso = $result->fetch_assoc()) {
                    $foto = isset($curso['imagen']) ? '../public/IMG/' . htmlspecialchars($curso['imagen']) : 'default.jpg'; // Imagen por defecto si no hay
                    $inscripciones_cerradas = $curso['inscripciones_cerradas'] ? 'inscripciones-cerradas' : '';
            
                    echo '<div class="col-md-4">';
                    echo '<div class="card mb-4 ' . $inscripciones_cerradas . '">';
                    echo '<img src="' . $foto . '" alt="'  . htmlspecialchars($curso['titulo']) . '" class="card-img-top">';
                    echo '<div class="card-body">';
                    echo '<h5 class="card-title">' . htmlspecialchars($curso['titulo']) . '</h5>';
                    echo '<p class="card-text">' . htmlspecialchars($curso['descripcion']) . '</p>';
                    
                    if ($curso['inscripciones_cerradas']) {
                        echo '<div class="inscripcion-cerrada">Inscripciones cerradas</div>';
                        echo '<p>Fecha de apertura: ' . htmlspecialchars($curso['fecha_apertura']) . '</p>';
                    } else {
                        echo '<a href="./inscripcion.php?id=' . htmlspecialchars($curso['id']) . '" class="btn btn-primary">Inscribirse</a>';
                    }
            
                    echo '</div>';
                    echo '</div>';
                    echo '</div>';
                }
            } else {
                echo '<p>No hay cursos destacados en este momento.</p>';
            }
            ?>
        </div>
        <div class="row">
            <div class="col-sm">
                <h2 style="text-align: center; font-weight:bold">¿Quiénes somos?</h2>
            </div>
        </div>
    </main> 
    

    <footer class="text-center">
        <p>&copy; 202 3 Centro de Formación Profesional. Todos los derechos reservados.</p>
    </footer>
 
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>