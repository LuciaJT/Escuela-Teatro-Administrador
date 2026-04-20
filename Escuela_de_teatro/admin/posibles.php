<?php 
session_start();

require '../config/conexion.php'; //para conexión con bbdd

// Obtener posibles alumnos
$sql = "SELECT id, nombre, apellidos FROM alumno WHERE estado = 'posible'";
$resultado = $conexion->query($sql);

//protección
if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}
?>

<!-- Cuando creamos un alumno, para mostrar el mensaje de que se ha creado bien -->
<?php if (isset($_GET['creado'])): ?>
    <div class="mensaje-ok">
        Alumno creado correctamente
    </div>
<?php endif; ?>



<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Posibles Alumnos</title>
    <link rel="stylesheet" href="../css/posibles.css?v=1">
</head>

<body>

<!-- mensaje alumno creado correctamente -->
 <?php if (isset($_GET['mensaje'])): ?>
    <div class="toast" id="toast">
        <?php
            if ($_GET['mensaje'] === 'creado') {
                echo "Alumno creado correctamente";
            }
        ?>
    </div>
<?php endif; ?>

<!-- CABECERA (reutilizar la del dashboard) -->
<div class="cabecera-dashboard">

    <div class="marca">🎭 PUNTO DE PARTIDA</div>

    <nav class="menu">
        <a href="dashboard.php" class="enlace-nav">Inicio</a>
        <a href="../auth/cerrar_sesion.php" class="enlace-cerrar">Cerrar sesión</a>
    </nav>

</div>


<div class="contenedor">

<div class="contenido">

    <!-- TÍTULO -->
    <div class="header-seccion">
        <div>
            <h1>Posibles Alumnos</h1>
            <p>Gestión de leads e interesados</p>
        </div>

        <a href="crear_alumno.php" class="boton-principal">
            + Nuevo Posible Alumno
        </a>
    </div>



    <!-- TARJETAS -->
    <div class="tarjetas-metricas">
        <div class="tarjeta-metrica">
            <p>Total Leads</p>
            <h2>6</h2>
        </div>

        <div class="tarjeta-metrica activa">
            <p>Clase de Prueba</p>
            <h2>2</h2>
        </div>

        <div class="tarjeta-metrica">
            <p>Apuntados</p>
            <h2>1</h2>
        </div>

        <div class="tarjeta-metrica">
            <p>Ex Alumnos</p>
            <h2>1</h2>
        </div>
    </div>

    <!-- FILTROS -->
    <div class="filtros">
        <span>Filtros de Búsqueda</span>
        <button class="btn-filtro">Mostrar Filtros</button>
    </div>

    <!-- TABLA -->
    <div class="tabla-container">
        <h3>Listado de Posibles Alumnos (<?= $resultado->num_rows ?>)</h3> <!-- contador de posibles alumn -->

        <table>
            <thead>
                <tr>
                    <th>Nombre y Apellidos</th>
                    <th>Grupo/Horario</th>
                    <th>Acciones</th>
                </tr>
            </thead>

            <tbody> <!-- pedimos posibles a la bbdd -->

                <?php if ($resultado->num_rows > 0): ?>
                    <?php while ($fila = $resultado->fetch_assoc()): ?> <!-- recorre cada alumno -->
                        <tr>
                            <td>
                                <?= $fila['nombre'] . ' ' . $fila['apellidos'] ?> <!-- convierte filas en arrays -->
                            </td>

                            <td class="sin-grupo">
                                Sin grupo asignado
                            </td>

                            <td>
                                <a href="detalle_alumno.php?id=<?= $fila['id'] ?>" class="boton-secundario">
                                    Ver más detalles
                                </a>
                            </td>
                        </tr>
                    <?php endwhile; ?>

                <?php else: ?>
                    <tr>
                        <td colspan="3">No hay posibles alumnos</td>
                    </tr>
                <?php endif; ?>

            </tbody>
        </table>
    </div>
</div>
</div>

</div>

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