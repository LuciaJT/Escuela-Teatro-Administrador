<?php
// ─────────────────────────────────────────────────────────────────────────────
// conexion.php
// Conecta PHP con la base de datos MySQL.
// Este archivo NO se abre directamente, lo incluyen los demás con "require".
// ─────────────────────────────────────────────────────────────────────────────

// Datos de conexión a MySQL en XAMPP (los valores por defecto de XAMPP):
$servidor   = 'localhost';           // Dónde está MySQL (en tu propio ordenador)
$usuario    = 'root';                // Usuario de MySQL (el de XAMPP por defecto)
$contrasena = 'Mysql2008';                    // Contraseña (XAMPP no pone ninguna)
$basedatos  = 'escuela_teatro_db';    // El nombre de NUESTRA base de datos

// Intenta conectar usando mysqli (la librería de PHP para MySQL).
// Si falla, el programa se para y muestra el error.
$conexion = new mysqli($servidor, $usuario, $contrasena, $basedatos);

// Comprobamos si hubo error de conexión:
if ($conexion->connect_error) {
    // die() detiene el programa y muestra el mensaje
    die('Error al conectar con la base de datos: ' . $conexion->connect_error);
}

// Le decimos a MySQL que use UTF-8 para que los acentos se vean bien (á, é, ñ, etc.)
$conexion->set_charset('utf8mb4');