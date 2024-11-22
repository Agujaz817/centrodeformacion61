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
$sql_evento = "SELECT * FROM eventos ORDER BY fecha_hora DESC LIMIT 1";
$result_evento = $conn->query($sql_evento);
$evento = $result_evento->fetch_assoc();
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
    
    <?php if ($evento): ?>
    <div class="alert alert-info text-center" id="evento">
        <h4 ><strong><?php echo htmlspecialchars($evento['titulo']); ?></strong></h4>
        <p><?php echo htmlspecialchars($evento['descripcion']); ?></p>
        <p>Fecha y Hora: <?php echo htmlspecialchars($evento['fecha_hora']); ?></p>
        <div id="contador"></div>
    </div>
<?php endif; 
?>
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
           
                <a class="nav-link" href="#">Inicio</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#trayectos">Trayectos Formativos</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#nosotros">Sobre Nosotros</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="#footer">Contacto</a>
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
        <div id="trayectos"> </div>
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
        <div id="nosotros"></div>
        <div class="row">
            <div class="col-sm">
                <h2 style="text-align: center; font-weight:bold; margin-top:80px;">¿Quiénes somos?</h2>
            </div>
        </div>
        
        
        <div class="row">
            <div class="col-sm">
                <p style="text-align: center; font-size:x-large; ">El CENTRO FORMACIÓN PROFESIONAL N° 61
dependiente de la Dirección de Educación Técnico
Profesional del Consejo General de Educación funciona
en la Localidad de La Criolla desde el año 2014 y
actualmente cuenta con un anexo en la ciudad vecina
de Colonia Ayuí
<br>
Somos una institución educativa que brinda trayectos
de formación profesional y capacitación laboral para
una rápida inserción en el mercado socioproductivo
local y regional. 
<br>
La Formación Profesional permite compatibilizar la
promoción social, profesional y personal con la
productividad de la economía nacional, regional y local. </p>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <h2 style="text-align: center; font-weight:bold; margin-top:80px;" >Propósitos y Objetivos</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-sm">
                <p style="text-align: center; font-size:x-large; ">Nuestra oferta busca preparar, actualizar y desarrollar
las capacidades de las personas para el mundo del
trabajo. Capacitamos en conocimientos específicos,
competencias básicas, profesionales y sociales para que
jóvenes y adultos/as puedan mejorar sus oportunidades
de empleabilidad.
<br>
La oferta de cursos y trayectos se orienta a temáticas
como: Informática, gastronomía, herrería, electricidad,
belleza y cosmética, marroquinería, entre otras. </p>
            </div>
        </div>
    </main> 
    

    <footer class=" text-light pt-4" id="footer">
    <div class="container-fluid">
        <div class="row">
            <!-- Columna 1: Ubicación -->
            <div class="col-lg-4 col-md-6 col-12 mb-3">
                <h5><i class="bi bi-geo-alt-fill fs-5"></i> Dirección</h5>
                <p>
                    Rio Bermejo N°278, La Criolla, Dpto Concordia.<br>
                    Instalaciones del Club Juan B. Alberdi.
                </p>
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3410.1767368566343!2d-58.10936102371696!3d-31.271206174329066!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x95adecb04d7457bd%3A0x98aa53d46aa8cd3f!2sR%C3%ADo%20Bermejo%2C%20La%20Criolla%2C%20Entre%20R%C3%ADos!5e0!3m2!1ses-419!2sar!4v1731937964342!5m2!1ses-419!2sar" width="50%" height="50%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
            </div>

            <!-- Columna 2: Contacto -->
            <div class="col-lg-4 col-md-6 col-12 mb-3">
                <h5><i class="bi bi-telephone-fill fs-5"></i> Teléfono y correos electrónicos</h5>
                <p>
                    Teléfono: <a href="tel:3454123356" class="text-light">345 412-3356</a><br>
                    Correos electrónicos:<br>
                    Administración: <a href="mailto:cfplacriolla@gmail.com" class="text-light">cfplacriolla@gmail.com</a><br>
                    Rectoría: <a href="mailto:cfp61.lacriolla@gmail.com" class="text-light">cfp61.lacriolla@gmail.com</a><br>
                    Institucional: <a href="mailto:cfp61.cd@entrerios.edu.ar" class="text-light">cfp61.cd@entrerios.edu.ar</a>
                </p>
            </div>

            <!-- Columna 3: Redes sociales o enlace institucional -->
            <div class="col-lg-4 col-md-12 col-12 mb-3">
                <h5><i class="bi bi-link fs-5"></i> Dirección de Educación Técnico
                Profesional del Consejo General de Educación</h5>
                <p>
                    <a href="https://cge.entrerios.gov.ar/tecnico-profesional/" class="text-light" target="_blank">Consejo General de Educación</a>
                </p>
            </div>
        </div>
    </div>
    <div class="border-top border-light mt-3"></div> <!-- Separación visual -->
</footer>
<script>
    const eventoFecha = new Date("<?php echo $evento['fecha_hora']; ?>").getTime();

    const x = setInterval(function() {
        const ahora = new Date().getTime();
        const distancia = eventoFecha - ahora;

        const dias = Math.floor(distancia / (1000 * 60 * 60 * 24));
        const horas = Math.floor((distancia % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
        const minutos = Math.floor((distancia % (1000 * 60 * 60)) / (1000 * 60));
        const segundos = Math.floor((distancia % (1000 * 60)) / 1000);

        document.getElementById("contador").innerHTML = dias + "d " + horas + "h " + minutos + "m " + segundos + "s ";

        if (distancia < 0) {
            clearInterval(x);
            document.getElementById("contador").innerHTML = "¡El evento ha comenzado!";
        }
    }, 1000);
</script>
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>