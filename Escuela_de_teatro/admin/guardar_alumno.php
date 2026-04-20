<?php
session_start();//protección

require '../config/conexion.php';//para conexión con bbdd

$nombre = $_POST['nombre'];
$apellidos = $_POST['apellidos'];
$email = $_POST['email'];
$telefono = $_POST['telefono'];
$estado = $_POST['estado'];
$nivel = $_POST['nivel'];
$tipo_interes = $_POST['tipo_interes'];
$clase_prueba = $_POST['clase_prueba'];
$fecha_interes = !empty($_POST['fecha_interes']) ? $_POST['fecha_interes'] : null;
$fecha_primera_clase = $_POST['fecha_primera_clase'];

$errores = [];//validación de datos

if (empty(trim($nombre))) {
    $errores[] = "El nombre es obligatorio.";
}

if (empty(trim($apellidos))) {
    $errores[] = "Los apellidos son obligatorios.";
}

if (empty(trim($email))) {
    $errores[] = "El email es obligatorio.";
} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $errores[] = "El email no tiene un formato válido.";
}

if (!empty($telefono) && !preg_match('/^[0-9]{9}$/', $telefono)) {
    $errores[] = "El teléfono debe tener 9 dígitos.";
}

if (!empty($errores)) {
    $_SESSION['errores_formulario'] = $errores;
    header("Location: crear_alumno.php");
    exit;

}




// Insertar alumno en la base de datos
$sql = "INSERT INTO alumno (
    nombre, apellidos, email, telefono, estado,
    nivel, tipo_interes, clase_prueba,
    fecha_interes, fecha_primera_clase
) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NULLIF(?, ''), NULLIF(?, ''))";

$stmt = $conexion->prepare($sql);

$stmt->bind_param(
    "sssssssiss", //tipo de dato que introduzco, string...integer...
    $nombre,
    $apellidos,
    $email,
    $telefono,
    $estado,
    $nivel,
    $tipo_interes,
    $clase_prueba,
    $fecha_interes,
    $fecha_primera_clase
);

$stmt->execute();

// volver a posibles
header("Location: posibles.php?mensaje=creado"); //va a mostrar alumno creado correctamente
exit;