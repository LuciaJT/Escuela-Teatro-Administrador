<?php
// ─────────────────────────────────────────────────────────────────────────────
// cerrar_sesion.php
// Cierra la sesión del usuario y lo manda al login.
// ─────────────────────────────────────────────────────────────────────────────

session_start();

// Borra todas las variables de sesión
$_SESSION = [];

// Destruye la sesión completamente
session_destroy();

// evitar caché (opcional)
header("Cache-Control: no-store, no-cache, must-revalidate");

// Manda al usuario al login
header('Location: login.php');
exit;