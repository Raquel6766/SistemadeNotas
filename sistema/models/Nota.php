<?php
require_once __DIR__ . '/Asignatura.php';
require_once __DIR__ . '/Curso.php';
require_once __DIR__ . '/../config/db.php';

class Nota {
    private $conn;

    public function __construct() {
        $db = new Database();
        $this->conn = $db->connect();
    }

    public function obtenerNotas() {
        $stmt = $this->conn->prepare("
            SELECT n.ID_nota, n.valor_nota, n.comentarios, 
                   u.nombre_usuario AS estudiante, 
                   a.nombre_asignatura 
            FROM Nota n
            JOIN Usuario u ON n.ID_usuario = u.ID_usuario
            JOIN Asignatura a ON n.ID_asignatura = a.ID_asignatura
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarNota($valor, $comentarios, $id_usuario, $id_asignatura) {
        $stmt = $this->conn->prepare("
            INSERT INTO Nota(valor_nota, comentarios, ID_usuario, ID_asignatura)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$valor, $comentarios, $id_usuario, $id_asignatura]);
    }

    public function eliminarNota($id) {
        $stmt = $this->conn->prepare("DELETE FROM Nota WHERE ID_nota = ?");
        return $stmt->execute([$id]);
    }

    public function editarNota($id, $valor, $comentarios) {
        $stmt = $this->conn->prepare("
            UPDATE Nota SET valor_nota = ?, comentarios = ? WHERE ID_nota = ?
        ");
        return $stmt->execute([$valor, $comentarios, $id]);
    }
}
?>
