<?php
session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$grupos = [
    [
        'id' => 1,
        'horario' => 'Lunes 17:30',
        'profesor' => 'Carlos',
        'nivel' => 'Iniciación Segundo Año',
        'nivel_clase' => 'iniciacion',
        'ocupacion' => 31,
        'alumnos' => 5,
        'capacidad' => 16,
        'sala' => 'Sala Teatro',
        'espacio' => 'Espacio en Blanco'
    ],
    [
        'id' => 2,
        'horario' => 'Lunes 18:00',
        'profesor' => 'Álex',
        'nivel' => 'Avanzado',
        'nivel_clase' => 'avanzado',
        'ocupacion' => 19,
        'alumnos' => 3,
        'capacidad' => 16,
        'sala' => 'Sala Blanca',
        'espacio' => 'Espacio en Blanco'
    ],
    [
        'id' => 3,
        'horario' => 'Lunes 20:00',
        'profesor' => 'Carlos',
        'nivel' => 'Iniciación',
        'nivel_clase' => 'iniciacion',
        'ocupacion' => 25,
        'alumnos' => 4,
        'capacidad' => 16,
        'sala' => 'Sala Madera',
        'espacio' => 'Espacio en Blanco'
    ]
];

$totalGrupos = count($grupos);
$totalIniciacion = 0;
$totalIntermedio = 0;
$totalAvanzado = 0;

foreach ($grupos as $grupo) {
    if ($grupo['nivel_clase'] === 'iniciacion') {
        $totalIniciacion++;
    } elseif ($grupo['nivel_clase'] === 'intermedio') {
        $totalIntermedio++;
    } elseif ($grupo['nivel_clase'] === 'avanzado') {
        $totalAvanzado++;
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grupos / Clases</title>
    <link rel="stylesheet" href="../css/grupos.css?v=2">
</head>
<body>
<header class="cabecera-dashboard">
    <div class="zona-izquierda">
        <div class="marca">🎭 Academia de Teatro</div>
        <div class="subtitulo">Grupos / Clases</div>
    </div>

    <nav class="menu">
        <a class="enlace-nav" href="posibles.php">Posibles</a>
        <a class="enlace-nav" href="matriculados.php">Matriculados</a>
        <a class="enlace-nav activo" href="grupos.php">Grupos</a>
        <a class="enlace-nav" href="especiales.php">Especiales</a>
        <a class="enlace-nav" href="dashboard.php">Inicio</a>
        <a class="enlace-cerrar" href="../auth/cerrar_sesion.php">Cerrar sesión</a>
    </nav>
</header>

<main class="contenedor">
    <section class="header-seccion">
        <div>
            <h1>Grupos / Clases</h1>
            <p>Gestión de grupos y horarios</p>
        </div>

        <a href="#" class="boton-principal">+ Nuevo Grupo</a>
    </section>

    <section class="tarjetas-metricas">
        <article class="tarjeta-metrica">
            <h3>Total Grupos</h3>
            <p class="numero"><?php echo $totalGrupos; ?></p>
        </article>
        <article class="tarjeta-metrica tarjeta-verde">
            <h3>Iniciación</h3>
            <p class="numero numero-verde"><?php echo $totalIniciacion; ?></p>
        </article>
        <article class="tarjeta-metrica tarjeta-amarilla">
            <h3>Intermedio</h3>
            <p class="numero numero-amarillo"><?php echo $totalIntermedio; ?></p>
        </article>
        <article class="tarjeta-metrica tarjeta-roja">
            <h3>Avanzado</h3>
            <p class="numero numero-rojo"><?php echo $totalAvanzado; ?></p>
        </article>
    </section>

    <section class="bloque-espacios">
        <div class="bloque-titulo"><h2>Salas y Espacios Disponibles</h2></div>
        <div class="espacios-grid">
            <article class="espacio-card">
                <h3>Espacio en Blanco</h3>
                <p class="direccion">📍 C/Mira el Sol 5</p>
                <ul><li>Sala Teatro</li><li>Sala Blanca</li><li>Sala Madera</li><li>Sala Azul</li></ul>
            </article>
            <article class="espacio-card">
                <h3>Sala ETC</h3>
                <p class="direccion">📍 C/Sombrerería 6</p>
                <ul><li>Único espacio</li></ul>
            </article>
            <article class="espacio-card">
                <h3>Sala Komodia</h3>
                <p class="direccion">📍 C/General Palanca 7</p>
                <ul><li>Único espacio</li></ul>
            </article>
        </div>
    </section>

    <section class="listado-grupos">
        <?php foreach ($grupos as $grupo): ?>
            <article class="grupo-card">
                <div class="grupo-top">
                    <div>
                        <h2><?= htmlspecialchars($grupo['horario']); ?> - <?= htmlspecialchars($grupo['nivel']); ?></h2>
                        <div class="meta-linea">
                            <span>🗓️ <?= htmlspecialchars($grupo['horario']); ?></span>
                            <span>👨‍🏫 Prof. <?= htmlspecialchars($grupo['profesor']); ?></span>
                        </div>
                        <div class="meta-linea meta-linea-secundaria">
                            <span>📍 <?= htmlspecialchars($grupo['espacio']); ?> - <?= htmlspecialchars($grupo['sala']); ?></span>
                        </div>
                    </div>
                    <div class="grupo-resumen">
                        <div class="contador"><?= (int)$grupo['alumnos']; ?>/<?= (int)$grupo['capacidad']; ?></div>
                        <div class="contador-sub">Alumnos</div>
                        <div class="plazas-libres"><?= (int)($grupo['capacidad'] - $grupo['alumnos']); ?> plazas libres</div>
                    </div>
                </div>

                <div class="ocupacion-bloque">
                    <div class="ocupacion-texto">
                        <span>Ocupación</span>
                        <span><?= (int)$grupo['ocupacion']; ?>%</span>
                    </div>
                    <div class="barra-ocupacion">
                        <div class="barra-ocupacion-valor barra-<?= htmlspecialchars($grupo['nivel_clase']); ?>" style="width: <?= (int)$grupo['ocupacion']; ?>%;"></div>
                    </div>
                </div>

                <div class="grupo-footer">
                    <div class="grupo-info-extra">
                        <span class="badge-nivel badge-<?= htmlspecialchars($grupo['nivel_clase']); ?>"><?= htmlspecialchars($grupo['nivel']); ?></span>
                    </div>
                    <div class="acciones">
                        <a href="#" class="boton-secundario">Editar</a>
                        <a href="#" class="boton-secundario">Ver alumnos (<?= (int)$grupo['alumnos']; ?>)</a>
                    </div>
                </div>
            </article>
        <?php endforeach; ?>
    </section>
</main>
</body>
</html>
