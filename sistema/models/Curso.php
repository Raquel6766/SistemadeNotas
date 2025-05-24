<?php
require_once __DIR__ . "/../config/db.php";

class Curso {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function obtenerCursos() {
        $stmt = $this->conn->prepare("SELECT * FROM Curso");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarCurso($nombre, $descripcion) {
        $stmt = $this->conn->prepare("INSERT INTO Curso(nombre_curso, descripcion) VALUES (?, ?)");
        return $stmt->execute([$nombre, $descripcion]);
    }

    public function eliminarCurso($id) {
        $stmt = $this->conn->prepare("DELETE FROM Curso WHERE ID_curso = ?");
        return $stmt->execute([$id]);
    }
}
?>
