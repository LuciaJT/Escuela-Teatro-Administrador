<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

if (($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="../css/dashboard.css?v=2">
</head>
<body class="pagina-dashboard">

<div class="cabecera-dashboard">
    <div class="marca">🎭 PUNTO DE PARTIDA</div>

    <nav>
        <span class="info-usuario">Panel de administración</span>
        <a href="../auth/cerrar_sesion.php" class="enlace-cerrar">Cerrar sesión</a>
    </nav>
</div>

<div class="contenido">
    <div class="titulo-dashboard">
        <h1>Bienvenido/a</h1>
        <p>Gestiona tu academia</p>
    </div>

    <div class="tarjetas">
        <div class="tarjeta">
            <div class="icono">👤</div>
            <h2>Posibles Alumnos</h2>
            <p>Gestión de leads e interesados</p>
            <a href="posibles.php" class="boton-acceder">Acceder</a>
        </div>

        <div class="tarjeta">
            <div class="icono">👥</div>
            <h2>Alumnos Matriculados</h2>
            <p>Gestión de alumnos activos y pagos</p>
            <a href="matriculados.php" class="boton-acceder">Acceder</a>
        </div>

        <div class="tarjeta">
            <div class="icono">📖</div>
            <h2>Grupos / Clases</h2>
            <p>Gestión de grupos y horarios</p>
            <a href="grupos.php" class="boton-acceder">Acceder</a>
        </div>

        <div class="tarjeta">
            <div class="icono">🎭</div>
            <h2>Grupos Especiales</h2>
            <p>Intensivos, salidas y talleres</p>
            <a href="especiales.php" class="boton-acceder">Acceder</a>
        </div>
    </div>
</div>

</body>
</html>
