<?php
session_start();

if (!isset($_SESSION['usuario_id']) || ($_SESSION['usuario_rol'] ?? '') !== 'admin') {
    header('Location: ../auth/login.php');
    exit;
}

require '../config/conexion.php';

$id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
$nombre = trim($_POST['nombre'] ?? '');
$apellidos = trim($_POST['apellidos'] ?? '');
$email = trim($_POST['email'] ?? '');
$estado = $_POST['estado'] ?? 'posible';
$telefono = trim($_POST['telefono'] ?? '');
$nivel = $_POST['nivel'] ?? '';
$tipo_interes = $_POST['tipo_interes'] ?? '';
$clase_prueba = isset($_POST['clase_prueba']) ? (int)$_POST['clase_prueba'] : 0;
$fecha_interes = $_POST['fecha_interes'] ?? '';
$fecha_primera_clase = $_POST['fecha_primera_clase'] ?? '';

$sql = "UPDATE alumno SET
    nombre = ?,
    apellidos = ?,
    email = ?,
    telefono = ?,
    estado = ?,
    nivel = ?,
    tipo_interes = ?,
    clase_prueba = ?,
    fecha_interes = NULLIF(?, ''),
    fecha_primera_clase = NULLIF(?, '')
WHERE id = ?";

$stmt = $conexion->prepare($sql);
$stmt->bind_param(
    "sssssssissi",
    $nombre,
    $apellidos,
    $email,
    $telefono,
    $estado,
    $nivel,
    $tipo_interes,
    $clase_prueba,
    $fecha_interes,
    $fecha_primera_clase,
    $id
);
$stmt->execute();

header("Location: detalle_alumno.php?id=" . $id . "&mensaje=actualizado");
exit;
