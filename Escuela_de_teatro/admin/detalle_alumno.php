<?php
session_start();

// Protección
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

// Conexión BD
require '../config/conexion.php';

// Recoger ID
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Alumno no encontrado";
    exit;
}

// Consulta
$sql = "SELECT * FROM alumno WHERE id = ?";
$stmt = $conexion->prepare($sql);
$stmt->bind_param("i", $id);
$stmt->execute();

$resultado = $stmt->get_result();

if ($resultado->num_rows === 0) {
    echo "Alumno no encontrado";
    exit;
}

$alumno = $resultado->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Detalle Alumno</title>
    <link rel="stylesheet" href="../css/posibles.css?v=1">
</head>

<body>

<!-- mensaje de alumno actualizado correctamente -->
 <?php if (isset($_GET['mensaje'])): ?>
    <div class="toast" id="toast">
        <?php
            if ($_GET['mensaje'] === 'actualizado') {
                echo "Alumno actualizado correctamente";
            }
        ?>
    </div>
<?php endif; ?>

<!-- CABECERA -->
<div class="cabecera-dashboard">
    <div class="marca">🎭 PUNTO DE PARTIDA</div>

    <nav class="menu">
        <a href="dashboard.php" class="enlace-nav">Inicio</a>
        <a href="../auth/cerrar_sesion.php" class="enlace-cerrar">Cerrar sesión</a>
    </nav>
</div>

<div class="contenedor">
<div class="contenido">

    <div class="acciones-detalle">
        <a href="editar_alumno.php?id=<?= $alumno['id'] ?>" class="btn-editar">
            Editar
        </a>
        <a href="#" class="btn-eliminar">Eliminar</a>
    </div>

    <div style="margin-bottom: 20px;">
        <a href="posibles.php" class="boton-secundario">← Volver a posibles alumnos</a>
    </div>

    <h1>Detalle del alumno</h1>

    <div class="detalle-card">

    <div class="detalle-header">
        <div class="avatar">👤</div>
        <div>
            <h2><?= $alumno['nombre'] . ' ' . $alumno['apellidos'] ?></h2>
            <?php
                $estado = $alumno['estado'];
                $claseEstado = '';

                if ($estado === 'posible') {
                    $claseEstado = 'estado-posible';
                } elseif ($estado === 'matriculado') {
                    $claseEstado = 'estado-matriculado';
                } else {
                    $claseEstado = 'estado-baja';
                }
                ?>

                <span class="estado <?= $claseEstado ?>">
                    <?= ucfirst($estado) ?>
                </span>
        </div>
    </div>

    <div class="detalle-grid">

        <div class="campo">
            <span>Email</span>
            <p><?= $alumno['email'] ?? 'No disponible' ?></p>
        </div>

        <div class="campo">
            <span>Teléfono</span>
            <p><?= $alumno['telefono'] ?? 'No disponible' ?></p>
        </div>

        <div class="campo">
            <span>Nivel</span>
            <p><?= $alumno['nivel'] ?? 'No definido' ?></p>
        </div>

        <div class="campo">
            <span>Tipo de interés</span>
            <p><?= $alumno['tipo_interes'] ?? 'No definido' ?></p>
        </div>

        <div class="campo">
            <span>Fecha interés</span>
            <p><?= $alumno['fecha_interes'] ?? '-' ?></p>
        </div>

        <div class="campo">
            <span>Primera clase</span>
            <p><?= $alumno['fecha_primera_clase'] ?? '-' ?></p>
        </div>

        <div class="campo">
            <span>Clase de prueba</span>
            <p><?= $alumno['clase_prueba'] ? 'Sí' : 'No' ?></p>
        </div>

    </div>

</div>

</div>
</div>

<!-- script JS para que desaparezca solo el mensaje de actualizacion -->
 <script>
setTimeout(() => {
    const toast = document.getElementById('toast');
    if (toast) {
        toast.style.opacity = '0';
        toast.style.transition = '0.5s';
        setTimeout(() => toast.remove(), 500);
    }
}, 3000);
</script>

</body>
</html>