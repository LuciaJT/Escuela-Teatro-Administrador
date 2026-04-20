<?php
session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require '../config/conexion.php';

$consulta = "SELECT id, nombre, apellidos FROM alumno WHERE estado = 'matriculado' ORDER BY nombre ASC, apellidos ASC";
$resultado = $conexion->query($consulta);
$alumnos = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $alumnos[] = $fila;
    }
}

$metricas = [
    'total_alumnos' => count($alumnos),
    'bloques_pagados' => 0,
    'bloques_pendientes' => 0,
    'grupos_activos' => 0,
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alumnos Matriculados</title>
    <link rel="stylesheet" href="../css/matriculados.css?v=2">
</head>
<body>
<header class="cabecera-dashboard">
    <div class="zona-izquierda">
        <div class="marca">🎭 Academia de Teatro</div>
        <div class="subtitulo">Alumnos Matriculados</div>
    </div>

    <nav class="menu">
        <a class="enlace-nav" href="posibles.php">Posibles</a>
        <a class="enlace-nav activo" href="matriculados.php">Matriculados</a>
        <a class="enlace-nav" href="grupos.php">Grupos</a>
        <a class="enlace-nav" href="especiales.php">Especiales</a>
        <a class="enlace-nav" href="dashboard.php">Inicio</a>
        <a class="enlace-cerrar" href="../auth/cerrar_sesion.php">Cerrar sesión</a>
    </nav>
</header>

<main class="contenedor">
    <section class="header-seccion">
        <div>
            <h1>Alumnos Matriculados</h1>
            <p>Gestión de alumnos activos y pagos</p>
        </div>
    </section>

    <section class="tarjetas-metricas">
        <article class="tarjeta-metrica">
            <h3>Total Alumnos</h3>
            <p class="numero"><?php echo $metricas['total_alumnos']; ?></p>
        </article>

        <article class="tarjeta-metrica tarjeta-verde">
            <h3>Bloques Pagados</h3>
            <p class="numero numero-verde"><?php echo $metricas['bloques_pagados']; ?></p>
        </article>

        <article class="tarjeta-metrica tarjeta-roja">
            <h3>Bloques Pendientes</h3>
            <p class="numero numero-rojo"><?php echo $metricas['bloques_pendientes']; ?></p>
        </article>

        <article class="tarjeta-metrica">
            <h3>Grupos Activos</h3>
            <p class="numero"><?php echo $metricas['grupos_activos']; ?></p>
        </article>
    </section>

    <section class="tabla-container">
        <div class="tabla-header">
            <div>
                <h2>Listado de Alumnos Activos</h2>
                <p>Sistema de bloques de 4 clases</p>
            </div>
        </div>

        <div class="tabla-scroll">
            <table>
                <thead>
                    <tr>
                        <th>Nombre y Apellidos</th>
                        <th>Grupo</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (!empty($alumnos)): ?>
                        <?php foreach ($alumnos as $alumno): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($alumno['nombre'] . ' ' . $alumno['apellidos']); ?></td>
                                <td><span class="tag-grupo">Sin grupo asignado</span></td>
                                <td>
                                    <a class="boton-secundario" href="detalle_alumno.php?id=<?php echo (int)$alumno['id']; ?>">Ver más detalles</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="3">No hay alumnos matriculados.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </section>
</main>

<footer class="pie">Sistema de Gestión Academia de Teatro · 2026</footer>
</body>
</html>
