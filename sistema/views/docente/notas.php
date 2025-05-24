<?php
require_once '../../models/Nota.php';
require_once '../../models/Asignatura.php';
require_once '../../models/Curso.php';
require_once '../../config/db.php';

session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'docente') {
    header("Location: ../login/login.php");
    exit();
}

$nota = new Nota();
$notas = $nota->obtenerNotas();

// Obtener usuarios (solo estudiantes)
$db = new Database();
$conn = $db->connect();
$usuarios = $conn->query("SELECT ID_usuario, nombre_usuario FROM Usuario u JOIN rol_usuario r ON u.id_rol = r.id_rol WHERE r.nombre_rol = 'Estudiante'")->fetchAll(PDO::FETCH_ASSOC);

// Obtener asignaturas
$asignaturas = $conn->query("SELECT * FROM Asignatura")->fetchAll(PDO::FETCH_ASSOC);
include '../templates/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container-fluid">
    <div class="row">
        <?php include '../templates/sidebar_docente.php'; ?>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
       <h3>Gestión de Notas</h3>
        <form action="../../controllers/NotaController.php" method="POST" class="row g-3 mb-4">
        <div class="col-md-2">
            <input type="number" name="valor_nota" class="form-control" placeholder="Nota" required>
        </div>
        <div class="col-md-3">
            <input type="text" name="comentarios" class="form-control" placeholder="Comentarios">
        </div>
        <div class="col-md-3">
            <select name="id_usuario" class="form-select" required>
                <option value="">Estudiante</option>
                <?php foreach($usuarios as $u): ?>
                    <option value="<?= $u['ID_usuario'] ?>"><?= $u['nombre_usuario'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <select name="id_asignatura" class="form-select" required>
                <option value="">Asignatura</option>
                <?php foreach($asignaturas as $a): ?>
                    <option value="<?= $a['ID_asignatura'] ?>"><?= $a['nombre_asignatura'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-1">
            <button type="submit" class="btn btn-success">Agregar</button>
        </div>
    </form>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Estudiante</th>
                <th>Asignatura</th>
                <th>Nota</th>
                <th>Comentarios</th>
                <th>Editar</th>
                <th>Eliminar</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($notas as $n): ?>
            <tr>
                <form method="POST" action="../../controllers/NotaController.php">
                    <td><?= $n['estudiante'] ?></td>
                    <td><?= $n['nombre_asignatura'] ?></td>
                    <td>
                        <input type="number" name="valor_nota" value="<?= $n['valor_nota'] ?>" class="form-control" required>
                    </td>
                    <td>
                        <input type="text" name="comentarios" value="<?= $n['comentarios'] ?>" class="form-control">
                    </td>
                    <td>
                        <input type="hidden" name="id" value="<?= $n['ID_nota'] ?>">
                        <input type="hidden" name="editar" value="1">
                        <button type="submit" class="btn btn-primary btn-sm">Guardar</button>
                    </td>
                    <td>
                        <a href="../../controllers/NotaController.php?eliminar=<?= $n['ID_nota'] ?>" class="btn btn-danger btn-sm">Eliminar</a>
                    </td>
                </form>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    </main>
    </div>
</div>
</body>
</html>
