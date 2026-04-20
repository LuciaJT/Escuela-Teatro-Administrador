<?php
// ─────────────────────────────────────────────────────────────────────────────
// http://localhost/escuela_de_teatro/auth/login.php

//login.php
// Página con el formulario de email + contraseña.
// Esta es la página de entrada del proyecto.
// ─────────────────────────────────────────────────────────────────────────────

// session_start() activa el sistema de sesiones de PHP.
// Tiene que ir SIEMPRE al principio del archivo, antes de cualquier HTML.
session_start();

// Si el usuario YA está logueado (su id está guardado en la sesión),
// no tiene sentido mostrarle el formulario de login: lo mandamos al dashboard.
if (isset($_SESSION['usuario_id'])) {
    header('Location: ../admin/dashboard.php');
    exit;
}

// Comprobamos si veníamos de un intento de login fallido.
// procesar_login.php nos manda aquí con ?error=1 si las credenciales son malas.
$mensaje_error = '';
if (isset($_GET['error'])) {
    $mensaje_error = 'Email o contraseña incorrectos';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Acceso - Punto de Partida</title>
    <link rel="stylesheet" href="../css/login.css?v=1">
</head>
<body class="pagina-login">

    <div class="tarjeta-login">
        <div class="franja-roja"></div>

        <div class="cabecera">
            <h1>PUNTO DE PARTIDA</h1>
            <p class="subtitulo">ESCUELA DE TEATRO</p>
        </div>

        <?php if ($mensaje_error !== ''): ?>
            <div class="mensaje-error">
                <?= $mensaje_error ?>
            </div>
        <?php endif; ?>

        <form action="procesar_login.php" method="POST" class="formulario">
            <div class="campo">
                <label for="email">Correo electrónico</label>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="campo">
                <label for="password">Contraseña</label>
                <input type="password" id="password" name="password" required>
            </div>

            <button type="submit" class="boton-principal">
                Entrar al escenario
            </button>
        </form>

        <p class="ayuda">
            Demo: <strong>lucia@mail.com</strong> / contraseña <strong>1234</strong>
        </p>

        <div class="franja-roja"></div>
    </div>

</body>
</html>