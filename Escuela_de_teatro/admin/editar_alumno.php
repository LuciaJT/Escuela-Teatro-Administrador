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

require '../config/conexion.php';

// recoger id
$id = $_GET['id'] ?? null;

if (!$id) {
    echo "Alumno no encontrado";
    exit;
}

// obtener datos actuales
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
    <title>Editar alumno</title>
    <link rel="stylesheet" href="../css/posibles.css?v=1">
</head>

<body>

<div class="cabecera-dashboard">
    <div class="marca">🎭 PUNTO DE PARTIDA</div>

    <nav class="menu">
        <a href="posibles.php" class="enlace-nav">Volver</a>
        <a href="../auth/cerrar_sesion.php" class="enlace-cerrar">Cerrar sesión</a>
    </nav>
</div>

<div class="contenedor">
<div class="contenido">

<h1>Editar alumno</h1>

<form action="actualizar_alumno.php" method="POST">

<input type="hidden" name="id" value="<?= $alumno['id'] ?>">

<div class="campo">
    <label>Nombre</label>
    <input type="text" name="nombre" value="<?= $alumno['nombre'] ?>" required>
</div>

<div class="campo">
    <label>Apellidos</label>
    <input type="text" name="apellidos" value="<?= $alumno['apellidos'] ?>" required>
</div>

<div class="campo">
    <label>Email</label>
    <input type="email" name="email" value="<?= $alumno['email'] ?>">
</div>

<div class="campo">
    <label>Teléfono</label>
    <input type="text" name="telefono" value="<?= $alumno['telefono'] ?>">
</div>

<div class="campo">
    <label>Estado</label>
    <select name="estado">
        <option value="posible" <?= $alumno['estado']=='posible' ? 'selected' : '' ?>>Posible</option>
        <option value="matriculado" <?= $alumno['estado']=='matriculado' ? 'selected' : '' ?>>Matriculado</option>
        <option value="baja" <?= $alumno['estado']=='baja' ? 'selected' : '' ?>>Baja</option>
    </select>
</div>

<div class="campo">
    <label>Nivel</label>
    <select name="nivel">
        <option value="">--</option>
        <option value="iniciacion" <?= $alumno['nivel']=='iniciacion' ? 'selected' : '' ?>>Iniciación</option>
        <option value="intermedio" <?= $alumno['nivel']=='intermedio' ? 'selected' : '' ?>>Intermedio</option>
        <option value="avanzado" <?= $alumno['nivel']=='avanzado' ? 'selected' : '' ?>>Avanzado</option>
    </select>
</div>

<div class="campo">
    <label>Tipo de interés</label>
    <select name="tipo_interes">
        <option value="">--</option>
        <option value="intensivo" <?= $alumno['tipo_interes']=='intensivo' ? 'selected' : '' ?>>Intensivo</option>
        <option value="ex_alumno" <?= $alumno['tipo_interes']=='ex_alumno' ? 'selected' : '' ?>>Ex alumno</option>
        <option value="sin_horario" <?= $alumno['tipo_interes']=='sin_horario' ? 'selected' : '' ?>>Sin horario</option>
    </select>
</div>

<div class="campo">
    <label>Clase de prueba</label>
    <select name="clase_prueba">
        <option value="0" <?= !$alumno['clase_prueba'] ? 'selected' : '' ?>>No</option>
        <option value="1" <?= $alumno['clase_prueba'] ? 'selected' : '' ?>>Sí</option>
    </select>
</div>

<div class="campo">
    <label>Fecha interés</label>
    <input type="date" name="fecha_interes" value="<?= $alumno['fecha_interes'] ?>">
</div>

<div class="campo">
    <label>Primera clase</label>
    <input type="date" name="fecha_primera_clase" value="<?= $alumno['fecha_primera_clase'] ?>">
</div>

<button type="submit" class="boton-principal">
    Guardar cambios
</button>

</form>

</div>
</div>

</body>
</html>