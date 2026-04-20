<?php
/* Reutilizamos el formulario de editar alumno */
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header('Location: ../auth/login.php');
    exit;
}

if ($_SESSION['usuario_rol'] !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

$errores = $_SESSION['errores_formulario'] ?? [];
$datos = $_SESSION['datos_formulario'] ?? [];

unset($_SESSION['errores_formulario'], $_SESSION['datos_formulario']);

function valorAntiguo(string $campo, string $valorPorDefecto = ''): string
{
    global $datos;
    return htmlspecialchars($datos[$campo] ?? $valorPorDefecto, ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Nuevo alumno</title>
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

        <h1>Nuevo alumno</h1>

        <?php if (!empty($errores)): ?>
            <div style="background:#f8d7da; color:#842029; padding:12px; border:1px solid #f5c2c7; border-radius:8px; margin-bottom:15px;">
                <strong>Se han encontrado errores:</strong>
                <ul style="margin:10px 0 0 20px;">
                    <?php foreach ($errores as $error): ?>
                        <li><?php echo htmlspecialchars($error, ENT_QUOTES, 'UTF-8'); ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <form action="guardar_alumno.php" method="POST">

            <div class="campo">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?php echo valorAntiguo('nombre'); ?>" required>
            </div>

            <div class="campo">
                <label for="apellidos">Apellidos</label>
                <input type="text" id="apellidos" name="apellidos" value="<?php echo valorAntiguo('apellidos'); ?>" required>
            </div>

            <div class="campo">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" value="<?php echo valorAntiguo('email'); ?>">
            </div>

            <div class="campo">
                <label for="telefono">Teléfono</label>
                <input type="text" id="telefono" name="telefono" value="<?php echo valorAntiguo('telefono'); ?>">
            </div>

            <div class="campo">
                <label for="estado">Estado</label>
                <select name="estado" id="estado">
                    <option value="posible" <?php echo valorAntiguo('estado', 'posible') === 'posible' ? 'selected' : ''; ?>>Posible</option>
                    <option value="matriculado" <?php echo valorAntiguo('estado') === 'matriculado' ? 'selected' : ''; ?>>Matriculado</option>
                    <option value="baja" <?php echo valorAntiguo('estado') === 'baja' ? 'selected' : ''; ?>>Baja</option>
                </select>
            </div>

            <div class="campo">
                <label for="nivel">Nivel</label>
                <select name="nivel" id="nivel">
                    <option value="" <?php echo valorAntiguo('nivel') === '' ? 'selected' : ''; ?>>--</option>
                    <option value="iniciacion" <?php echo valorAntiguo('nivel') === 'iniciacion' ? 'selected' : ''; ?>>Iniciación</option>
                    <option value="intermedio" <?php echo valorAntiguo('nivel') === 'intermedio' ? 'selected' : ''; ?>>Intermedio</option>
                    <option value="avanzado" <?php echo valorAntiguo('nivel') === 'avanzado' ? 'selected' : ''; ?>>Avanzado</option>
                </select>
            </div>

            <div class="campo">
                <label for="tipo_interes">Tipo de interés</label>
                <select name="tipo_interes" id="tipo_interes">
                    <option value="" <?php echo valorAntiguo('tipo_interes') === '' ? 'selected' : ''; ?>>--</option>
                    <option value="intensivo" <?php echo valorAntiguo('tipo_interes') === 'intensivo' ? 'selected' : ''; ?>>Intensivo</option>
                    <option value="ex_alumno" <?php echo valorAntiguo('tipo_interes') === 'ex_alumno' ? 'selected' : ''; ?>>Ex alumno</option>
                    <option value="sin_horario" <?php echo valorAntiguo('tipo_interes') === 'sin_horario' ? 'selected' : ''; ?>>Sin horario</option>
                </select>
            </div>

            <div class="campo">
                <label for="clase_prueba">Clase de prueba</label>
                <select name="clase_prueba" id="clase_prueba">
                    <option value="0" <?php echo valorAntiguo('clase_prueba', '0') === '0' ? 'selected' : ''; ?>>No</option>
                    <option value="1" <?php echo valorAntiguo('clase_prueba') === '1' ? 'selected' : ''; ?>>Sí</option>
                </select>
            </div>

            <div class="campo">
                <label for="fecha_interes">Fecha interés</label>
                <input type="date" id="fecha_interes" name="fecha_interes" value="<?php echo valorAntiguo('fecha_interes'); ?>">
            </div>

            <div class="campo">
                <label for="fecha_primera_clase">Primera clase</label>
                <input type="date" id="fecha_primera_clase" name="fecha_primera_clase" value="<?php echo valorAntiguo('fecha_primera_clase'); ?>">
            </div>

            <button type="submit" class="boton-principal">Crear alumno</button>
        </form>

    </div>
</div>

</body>
</html>
