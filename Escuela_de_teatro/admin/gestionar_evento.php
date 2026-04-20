<?php
session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    header("Location: ../auth/login.php");
    exit;
}

require '../config/conexion.php';

$idEvento = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($idEvento <= 0) {
    header('Location: especiales.php');
    exit;
}

$stmtEvento = $conexion->prepare("SELECT nombre, tipo, fecha, descripcion FROM evento_grupal WHERE id = ? LIMIT 1");
$stmtEvento->bind_param('i', $idEvento);
$stmtEvento->execute();
$resultado = $stmtEvento->get_result();
$evento = $resultado->fetch_assoc();

if (!$evento) {
    header('Location: especiales.php');
    exit;
}

$stmtAlumnos = $conexion->prepare("SELECT a.id, a.nombre, a.apellidos FROM inscripcion_evento i INNER JOIN alumno a ON i.alumno_id = a.id WHERE i.evento_id = ? AND i.estado = 'inscrito' ORDER BY a.nombre ASC, a.apellidos ASC");
$stmtAlumnos->bind_param('i', $idEvento);
$stmtAlumnos->execute();
$resAlumnos = $stmtAlumnos->get_result();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gestionar Evento</title>
<link rel="stylesheet" href="../css/especiales.css?v=2">
</head>
<body>
<header class="cabecera-dashboard">
    <div class="zona-izquierda">
        <div class="marca">🎭 Academia de Teatro</div>
        <div class="subtitulo">Gestionar Evento</div>
    </div>

    <nav class="menu">
        <a class="enlace-nav" href="posibles.php">Posibles</a>
        <a class="enlace-nav" href="matriculados.php">Matriculados</a>
        <a class="enlace-nav" href="grupos.php">Grupos</a>
        <a class="enlace-nav activo" href="especiales.php">Especiales</a>
        <a class="enlace-nav" href="dashboard.php">Inicio</a>
        <a class="enlace-cerrar" href="../auth/cerrar_sesion.php">Cerrar sesión</a>
    </nav>
</header>

<main class="contenedor">
    <section class="header-seccion">
        <div>
            <h1>Gestionar Evento</h1>
            <p><?= htmlspecialchars($evento['nombre']); ?></p>
        </div>
    </section>

    <section class="evento-card">
        <div class="evento-meta">
            <div class="evento-fecha"><strong>Tipo:</strong> <?= htmlspecialchars($evento['tipo']); ?></div>
            <div class="evento-fecha"><strong>Fecha:</strong> <?= htmlspecialchars($evento['fecha']); ?></div>
            <div class="evento-desc"><strong>Descripción:</strong> <?= htmlspecialchars($evento['descripcion']); ?></div>
        </div>

        <div class="alumnos-bloque">
            <h3>Alumnos apuntados</h3>
            <?php if ($resAlumnos && $resAlumnos->num_rows > 0): ?>
                <ul class="lista-alumnos">
                    <?php while ($alumno = $resAlumnos->fetch_assoc()): ?>
                        <li>
                            <span><?= htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellidos']); ?></span>
                            <a href="quitar_alumno_evento.php?evento=<?= $idEvento; ?>&alumno=<?= (int)$alumno['id']; ?>" class="borrar" title="Quitar alumno">❌</a>
                        </li>
                    <?php endwhile; ?>
                </ul>
            <?php else: ?>
                <p>No hay alumnos apuntados a este evento.</p>
            <?php endif; ?>

            <a href="especiales.php" class="boton-gestion" style="background:#e50914; color:white;">⬅ Volver</a>
        </div>
    </section>
</main>
</body>
</html>
