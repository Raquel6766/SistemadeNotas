<?php
require_once __DIR__ . '/../config/db.php';

class Asignatura {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function obtenerAsignaturas() {
        $stmt = $this->conn->prepare("
            SELECT a.*, c.nombre_curso 
            FROM Asignatura a
            LEFT JOIN Curso c ON a.ID_curso = c.ID_curso
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarAsignatura($nombre, $curso_id) {
        $stmt = $this->conn->prepare("INSERT INTO Asignatura(nombre_asignatura, ID_curso) VALUES (?, ?)");
        return $stmt->execute([$nombre, $curso_id]);
    }

    public function eliminarAsignatura($id) {
        $stmt = $this->conn->prepare("DELETE FROM Asignatura WHERE ID_asignatura = ?");
        return $stmt->execute([$id]);
    }
}
?>
