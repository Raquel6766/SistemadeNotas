<?php
require_once '../../models/Curso.php';
session_start();
if (!isset($_SESSION['rol']) || $_SESSION['rol'] != 'docente') {
    header("Location: ../login/login.php");
    exit();
}

$curso = new Curso();
$id_docente = $_SESSION['id'];
$cursos = $curso->obtenerCursosPorDocente($id_docente);
include '../templates/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Gestionar Cursos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <style>
        /* Estilos para el modal */
        .modal {
        display: none; /* Oculto por defecto */
        position: fixed;
        z-index: 1050; /* encima de todo */
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0,0,0,0.6); /* Fondo negro opaco */
        justify-content: center;
        align-items: center;
        }

        .modal-contenido {
        background-color: #fff;
        padding: 20px 30px;
        border-radius: 8px;
        max-width: 700px;
        width: 90%;
        box-shadow: 0 5px 15px rgba(0,0,0,0.3);
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <?php include '../templates/sidebar_docente.php'; ?> 
            <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 mt-4">
                <h3>Gestión de Cursos</h3>
            <button id="btnAbrirModal" class="btn mb-3" style="background-color:rgb(35, 100, 38); color: white">Agregar Curso</button>

            <!-- Modal -->
            <div id="modalFormulario" class="modal">
            <div class="modal-contenido">
                <form action="../../controllers/CursoController.php" method="POST" class="row g-3 mb-4">
                <div class="col-md-6">
                    <input type="text" name="nombre" class="form-control" placeholder="Nombre del curso" required>
                </div>
                <div class="col-md-6">
                    <input type="text" name="descripcion" class="form-control" placeholder="Docente" required>
                </div>
                <div class="col-md-12">
                        <button type="submit" class="btn btn-success me-2">Agregar</button>
                        <button type="button" id="btnCerrarModal" class="btn btn-secondary">Cancelar</button>
                </div>
                </form>
            </div>
            </div>                 

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nombre</th>
                            <th>Docente</th>
                            <th>Acciones</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($cursos as $c): ?>
                        <tr>
                            <td><?= $c['ID_curso'] ?></td>
                            <td><?= $c['grado'] ?></td>
                            <td><?= $c['nombre_usuario'] ?></td> <!-- Nombre del docente -->
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

<script>
const btnAbrirModal = document.getElementById('btnAbrirModal');
const modalFormulario = document.getElementById('modalFormulario');
const btnCerrarModal = document.getElementById('btnCerrarModal');

btnAbrirModal.addEventListener('click', () => {
  modalFormulario.style.display = 'flex';
});

btnCerrarModal.addEventListener('click', () => {
  modalFormulario.style.display = 'none';
});

// Opcional: cerrar modal si clickeas fuera del contenido
window.addEventListener('click', (e) => {
  if (e.target === modalFormulario) {
    modalFormulario.style.display = 'none';
  }
});
</script>
</body>
</html>
