<?php
require_once __DIR__ . "/../config/db.php";

class Curso {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Obtener todos los cursos con nombre del docente (si lo necesitas para admin)
    public function obtenerCursos() {
        $stmt = $this->conn->prepare("
            SELECT c.ID_curso, c.grado, u.nombre_usuario AS nombre_docente
            FROM curso c
            JOIN usuario u ON c.usuario_ID_usuario = u.ID_usuario
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener solo los cursos que imparte un docente
    public function obtenerCursosPorDocente($id_docente) {
        $stmt = $this->conn->prepare("
            SELECT DISTINCT c.ID_curso, c.grado
            FROM curso c
            JOIN asignatura a ON c.ID_curso = a.curso_ID_curso
            JOIN `asignatura-docente` ad ON a.ID_asignatura = ad.asignatura_ID_asignatura
            WHERE ad.usuario_ID_usuario = ?
        ");
        $stmt->execute([$id_docente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Agregar un curso nuevo con grado y docente que lo creó
    public function agregarCurso($grado, $id_docente) {
        $stmt = $this->conn->prepare("
            INSERT INTO curso(grado, usuario_ID_usuario) 
            VALUES (?, ?)
        ");
        return $stmt->execute([$grado, $id_docente]);
    }

    // Eliminar curso por ID
    public function eliminarCurso($id) {
        $stmt = $this->conn->prepare("DELETE FROM curso WHERE ID_curso = ?");
        return $stmt->execute([$id]);
    }
}

?>
