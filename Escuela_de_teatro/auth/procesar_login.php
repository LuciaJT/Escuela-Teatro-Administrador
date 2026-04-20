<?php
// ─────────────────────────────────────────────────────────────────────────────
// procesar_login.php
// Recibe los datos del formulario de login.php, comprueba si son correctos,
// y redirige al dashboard o vuelve al login con un error.
// ─────────────────────────────────────────────────────────────────────────────

session_start();

// Incluimos el archivo de conexión para poder usar la variable $conexion.
// "require" es como "include" pero si falla, detiene el programa.
require '../config/conexion.php';

// Recogemos los datos que llegan desde el formulario.
// $_POST es un array con los datos enviados por method="POST".
// El ?? '' significa: "si no existe, usa cadena vacía" (para que no dé error).
$email      = $_POST['email']    ?? '';
$contrasena = $_POST['password'] ?? '';


// Si alguno de los campos está vacío, volvemos al login con error.
if ($email === '' || $contrasena === '') {
    header('Location: login.php?error=1');
    exit;
}

// Buscamos al usuario en la base de datos por su email.
// Usamos "prepared statements" (?) para evitar SQL injection (ataques).
// NUNCA metas variables directamente en la consulta SQL con concatenación.
$consulta = $conexion->prepare(
    'SELECT id, email, password_hash, rol
     FROM usuario
     WHERE email = ?
     LIMIT 1'
);

// Le decimos qué tipo de dato es el ? ("s" = string) y qué valor tiene
$consulta->bind_param('s', $email);
$consulta->execute();
$resultado = $consulta->get_result();

// Si no encuentra ningún usuario con ese email, login incorrecto.
if ($resultado->num_rows === 0) {
    header('Location: login.php?error=1');
    exit;
}

// Cogemos los datos del usuario encontrado.
$usuario = $resultado->fetch_assoc();

// password_verify() es una función de PHP que compara la contraseña que el
// usuario ha escrito con el hash guardado en la BD. Es así porque las
// contraseñas no se guardan en texto plano por seguridad.
if (!password_verify($contrasena, $usuario['password_hash'])) {
//if ($contrasena !== '1234'){
header('Location: login.php?error=1');
    exit;
}

// ✅ Login correcto. Guardamos los datos del usuario en la sesión.
// $_SESSION es un array que persiste mientras el usuario tenga la pestaña abierta.
$_SESSION['usuario_id']        = $usuario['id'];
$_SESSION['usuario_email']     = $usuario['email'];
$_SESSION['usuario_rol']       = $usuario['rol'];

// Mandamos al usuario al dashboard.
header('Location: ../admin/dashboard.php');
exit;