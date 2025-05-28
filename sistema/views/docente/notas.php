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

// Conexión directa para obtener datos
$db = new Database();
$conn = $db->connect();

// Estudiantes
$listas = $conn->query("
    SELECT lp.ID_lista, u.nombre_usuario AS estudiante, a.nombre_asignatura
    FROM lista_participante lp
    JOIN usuario u ON lp.usuario_ID_usuario = u.ID_usuario
    JOIN `asignatura-docente` ad ON lp.ID_asig_doc = ad.ID_asig_doc
    JOIN asignatura a ON ad.asignatura_ID_asignatura = a.ID_asignatura
")->fetchAll(PDO::FETCH_ASSOC);

// Cursos
$cursos = $conn->query("SELECT * FROM Curso")->fetchAll(PDO::FETCH_ASSOC);

include '../templates/header.php';
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Notas</title>
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
            <h3>Gestión de Notas</h3>
            <!-- Botón para abrir el modal -->
            <button id="btnAbrirModal" class="btn mb-3" style="background-color:rgb(35, 100, 38); color: white">Agregar Nota</button>

            <!-- Modal -->
            <div id="modalFormulario" class="modal">
            <div class="modal-contenido">
                <h5>Agregar Nota</h5>
                <form action="../../controllers/NotaController.php" method="POST" class="row g-3 mb-4">
                    <div class="col-md-3">
                        <input type="number" name="valor_nota" class="form-control" placeholder="Nota" required>
                    </div>
                    <div class="col-md-4">
                        <input type="text" name="comentarios" class="form-control" placeholder="Comentarios">
                    </div>
                    <div class="col-md-5">
                        <select name="id_lista" class="form-select" required>
                            <option value="">Estudiante / Asignatura</option>
                            <?php foreach($listas as $l): ?>
                                <option value="<?= $l['ID_lista'] ?>">
                                    <?= $l['estudiante'] ?> - <?= $l['nombre_asignatura'] ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <select name="id_curso" class="form-select" required>
                            <option value="">Curso</option>
                            <?php foreach($cursos as $c): ?>
                                <option value="<?= $c['ID_curso'] ?>"><?= $c['grado'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-12 text-end">
                        <button type="submit" class="btn btn-success me-2">Agregar</button>
                        <button type="button" id="btnCerrarModal" class="btn btn-secondary">Cancelar</button>
                    </div>
                </form>
            </div>
            </div>

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th>Asignatura</th>
                        <th>Curso</th>
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
                            <td><?= $n['nombre_curso'] ?></td>
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
