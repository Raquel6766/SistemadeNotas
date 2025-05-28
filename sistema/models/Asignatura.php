<?php
require_once __DIR__ . '/../config/db.php';

class Asignatura {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    // Mostrar solo asignaturas impartidas por el docente actual
    public function obtenerAsignaturasPorDocente($id_docente) {
        $stmt = $this->conn->prepare("
            SELECT a.ID_asignatura, a.nombre_asignatura, c.grado AS nombre_curso
            FROM asignatura a
            JOIN curso c ON a.curso_ID_curso = c.ID_curso
            JOIN `asignatura-docente` ad ON a.ID_asignatura = ad.asignatura_ID_asignatura
            WHERE ad.usuario_ID_usuario = ?
        ");
        $stmt->execute([$id_docente]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarAsignatura($nombre, $curso_id) {
        $stmt = $this->conn->prepare("
            INSERT INTO asignatura(nombre_asignatura, curso_ID_curso)
            VALUES (?, ?)
        ");
        return $stmt->execute([$nombre, $curso_id]);
    }

    public function eliminarAsignatura($id) {
        $stmt = $this->conn->prepare("DELETE FROM asignatura WHERE ID_asignatura = ?");
        return $stmt->execute([$id]);
    }
}
