<?php
require_once "../models/Curso.php";

$curso = new Curso();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['nombre']) && isset($_POST['descripcion'])) {
        $curso->agregarCurso($_POST['nombre'], $_POST['descripcion']);
    }
} elseif (isset($_GET['eliminar'])) {
    $curso->eliminarCurso($_GET['eliminar']);
}

header("Location: ../views/docente/cursos.php");
?>
