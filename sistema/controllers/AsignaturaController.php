<?php
require_once __DIR__ . '/../models/Asignatura.php';

$asignatura = new Asignatura();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nombre']) && isset($_POST['curso'])) {
        $asignatura->agregarAsignatura($_POST['nombre'], $_POST['curso']);
    }
} elseif (isset($_GET['eliminar'])) {
    $asignatura->eliminarAsignatura($_GET['eliminar']);
}

header("Location: ../views/docente/asignaturas.php");
?>
