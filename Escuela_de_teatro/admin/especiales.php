<?php
session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require '../config/conexion.php';

$consulta = "
SELECT 
    e.id,
    e.nombre,
    e.tipo,
    e.descripcion,
    e.fecha,
    e.plazas_maximas,
    COUNT(i.id) AS apuntados
FROM evento_grupal e
LEFT JOIN inscripcion_evento i 
    ON e.id = i.evento_id 
    AND i.estado = 'inscrito'
GROUP BY e.id, e.nombre, e.tipo, e.descripcion, e.fecha, e.plazas_maximas
ORDER BY e.fecha ASC
";

$resultado = $conexion->query($consulta);
$eventos = [];

if ($resultado && $resultado->num_rows > 0) {
    while ($fila = $resultado->fetch_assoc()) {
        $fila['alumnos'] = [];

        $consultaAlumnos = "
            SELECT a.nombre, a.apellidos
            FROM inscripcion_evento i
            INNER JOIN alumno a ON i.alumno_id = a.id
            WHERE i.evento_id = " . (int)$fila['id'] . "
            AND i.estado = 'inscrito'
            ORDER BY a.nombre ASC, a.apellidos ASC
        ";

        $resAlumnos = $conexion->query($consultaAlumnos);
        if ($resAlumnos && $resAlumnos->num_rows > 0) {
            while ($al = $resAlumnos->fetch_assoc()) {
                $fila['alumnos'][] = $al['nombre'] . ' ' . $al['apellidos'];
            }
        }

        $eventos[] = $fila;
    }
}

$totalEventos = count($eventos);
$totalIntensivos = 0;
$totalSalidas = 0;
$totalTalleres = 0;

foreach ($eventos as $evento) {
    if ($evento['tipo'] === 'intensivo') {
        $totalIntensivos++;
    } elseif ($evento['tipo'] === 'salida') {
        $totalSalidas++;
    } elseif ($evento['tipo'] === 'taller') {
        $totalTalleres++;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupos Especiales</title>
    <link rel="stylesheet" href="../css/especiales.css?v=2">
</head>
<body>
<header class="cabecera-dashboard">
    <div class="zona-izquierda">
        <div class="marca">🎭 Academia de Teatro</div>
        <div class="subtitulo">Grupos Especiales</div>
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
            <h1>Grupos Especiales</h1>
            <p>Intensivos, salidas al teatro y talleres</p>
        </div>

        <a href="#" class="boton-principal">+ Nuevo Grupo Especial</a>
    </section>

    <section class="tarjetas-metricas">
        <article class="tarjeta-metrica"><h3>Total Eventos</h3><p class="numero"><?= $totalEventos; ?></p></article>
        <article class="tarjeta-metrica tarjeta-roja"><h3>Intensivos</h3><p class="numero numero-rojo"><?= $totalIntensivos; ?></p></article>
        <article class="tarjeta-metrica"><h3>Salidas Teatro</h3><p class="numero"><?= $totalSalidas; ?></p></article>
        <article class="tarjeta-metrica"><h3>Talleres</h3><p class="numero"><?= $totalTalleres; ?></p></article>
    </section>

    <section class="eventos-grid">
        <?php if (!empty($eventos)): ?>
            <?php foreach ($eventos as $evento): ?>
                <?php
                    $plazasMaximas = (int)$evento['plazas_maximas'];
                    $apuntados = (int)$evento['apuntados'];
                    $plazasLibres = max(0, $plazasMaximas - $apuntados);
                    $ocupacion = $plazasMaximas > 0 ? min(100, round(($apuntados / $plazasMaximas) * 100)) : 0;
                ?>
                <article class="evento-card">
                    <div class="evento-top">
                        <div class="evento-titulo-zona">
                            <h2><?= htmlspecialchars($evento['nombre']); ?></h2>
                            <span class="badge-tipo badge-<?= htmlspecialchars($evento['tipo']); ?>"><?= htmlspecialchars(ucfirst($evento['tipo'])); ?></span>
                        </div>
                        <div class="evento-contador"><?= $apuntados; ?><div class="evento-sub">Apuntados</div></div>
                    </div>

                    <div class="evento-meta">
                        <div class="evento-fecha">🗓️ <?= htmlspecialchars($evento['fecha']); ?></div>
                        <div class="evento-desc"><?= htmlspecialchars($evento['descripcion']); ?></div>
                    </div>

                    <div class="aforo">
                        <div class="aforo-top">
                            <span>Aforo disponible:</span>
                            <span class="plazas-libres"><?= $plazasLibres; ?> plazas libres</span>
                        </div>
                        <div class="barra-ocupacion">
                            <div class="barra-ocupacion-valor barra-<?= htmlspecialchars($evento['tipo']); ?>" style="width: <?= $ocupacion; ?>%;"></div>
                        </div>
                    </div>

                    <div class="alumnos-bloque">
                        <h3>Alumnos Apuntados:</h3>
                        <?php if (!empty($evento['alumnos'])): ?>
                            <ul class="lista-alumnos">
                                <?php foreach ($evento['alumnos'] as $alumno): ?>
                                    <li><span><?= htmlspecialchars($alumno); ?></span></li>
                                <?php endforeach; ?>
                            </ul>
                        <?php else: ?>
                            <p>No hay alumnos apuntados.</p>
                        <?php endif; ?>

                        <a href="gestionar_evento.php?id=<?= (int)$evento['id']; ?>" class="boton-gestion">👥 Gestionar Alumnos</a>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            <p>No hay eventos especiales registrados.</p>
        <?php endif; ?>
    </section>
</main>
</body>
</html>
