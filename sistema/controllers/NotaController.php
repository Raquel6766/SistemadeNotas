<?php
require_once "../models/Nota.php";

$nota = new Nota();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['editar'])) {
        $nota->editarNota($_POST['id'], $_POST['valor_nota'], $_POST['comentarios']);
    } else {
        // Agregar nota correctamente usando la nueva estructura de la base de datos
        $nota->agregarNota(
            $_POST['valor_nota'],
            $_POST['comentarios'],
            $_POST['id_lista'],   
            $_POST['id_curso']    
        );
    }
} elseif (isset($_GET['eliminar'])) {
    $nota->eliminarNota($_GET['eliminar']);
}

header("Location: ../views/docente/notas.php");
?>
