<?php
session_start();

if (!isset($_SESSION['usuario_id'])) {
    header("Location: ../auth/login.php");
    exit;
}

require '../config/conexion.php';

$idEvento = isset($_GET['evento']) ? intval($_GET['evento']) : 0;
$idAlumno = isset($_GET['alumno']) ? intval($_GET['alumno']) : 0;

if ($idEvento > 0 && $idAlumno > 0) {
    $consulta = "
    DELETE FROM inscripcion_evento
    WHERE evento_id = $idEvento
    AND alumno_id = $idAlumno
    LIMIT 1
    ";

    $conexion->query($consulta);
}

header("Location: gestionar_evento.php?id=" . $idEvento);
exit;