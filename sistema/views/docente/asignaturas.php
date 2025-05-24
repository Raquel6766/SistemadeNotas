<?php
require_once __DIR__ . '/../../models/Asignatura.php';
require_once __DIR__ . '/../../models/Curso.php';
session_start();

if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'docente') {
    header("Location: ../login/login.php");
    exit();
}

$asignatura = new Asignatura();
$asignaturas = $asignatura->obtenerAsignaturas();

$curso = new Curso();
$cursos = $curso->obtenerCursos();

include __DIR__ . '/../templates/header.php';
?>
<head>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">    
</head>
<div class="container-fluid">
    <div class="row">
        <?php include __DIR__ . '/../templates/sidebar_docente.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
            <h3>Gestión de Asignaturas</h3>
            <form action="../../controllers/AsignaturaController.php" method="POST" class="row g-3 mb-4">
                <div class="col-md-5">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre de asignatura" required>
                </div>
                <div class="col-md-5">
                    <select name="curso" class="form-select" required>
                        <option value="">Seleccionar curso</option>
                        <?php foreach($cursos as $c): ?>
                            <option value="<?= $c['ID_curso'] ?>"><?= $c['nombre_curso'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary">Agregar</button>
                </div>
            </form>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Asignatura</th>
                        <th>Curso</th>
                        <th>Acciones</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($asignaturas as $a): ?>
                    <tr>
                        <td><?= $a['ID_asignatura'] ?></td>
                        <td><?= $a['nombre_asignatura'] ?></td>
                        <td><?= $a['nombre_curso'] ?></td>
                        <td>
                            <a href="../../controllers/AsignaturaController.php?eliminar=<?= $a['ID_asignatura'] ?>" class="btn btn-sm btn-danger">Eliminar</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </main>
    </div>
</div>
<?php include __DIR__ . '/../templates/footer.php'; ?>
