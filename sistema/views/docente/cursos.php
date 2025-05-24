<?php
require_once '../../models/Curso.php';
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'docente') {
    header("Location: ../login/login.php");
    exit();
}

$curso = new Curso();
$cursos = $curso->obtenerCursos();
include '../templates/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestionar Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../templates/sidebar_docente.php'; ?> 
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
                <h3>Gestión de Cursos</h3>
                <form action="../../controllers/CursoController.php" method="POST" class="row g-3 mb-4">
                <div class="col-md-4">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre del curso" required>
                </div>
                <div class="col-md-4">
                    <input type="text" name="descripcion" class="form-control" placeholder="Descripción" required>
                </div>
                <div class="col-md-4">
                    <button type="submit" class="btn btn-success">Agregar Curso</button>
                </div>
                </form>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Descripción</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cursos as $c): ?>
                            <tr>
                                <td><?= $c['ID_curso'] ?></td>
                                <td><?= $c['nombre_curso'] ?></td>
                                <td><?= $c['descripcion'] ?></td>
                                <td>
                                    <a href="../../controllers/CursoController.php?eliminar=<?= $c['ID_curso'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </main>
        </div>
    </div>
</body>
</html>
