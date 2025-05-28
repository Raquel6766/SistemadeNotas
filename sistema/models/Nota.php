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
                a.nombre_asignatura,
                c.grado AS nombre_curso
            FROM nota n
            JOIN lista_participante lp ON n.lista_participante_ID_lista = lp.ID_lista
            JOIN usuario u ON lp.usuario_ID_usuario = u.ID_usuario
            JOIN `asignatura-docente` ad ON lp.ID_asig_doc = ad.ID_asig_doc
            JOIN asignatura a ON ad.asignatura_ID_asignatura = a.ID_asignatura
            JOIN curso c ON n.curso_ID_curso = c.ID_curso
        ");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function agregarNota($valor, $comentarios, $id_lista, $id_curso) {
        $stmt = $this->conn->prepare("
            INSERT INTO nota(valor_nota, comentarios, lista_participante_ID_lista, curso_ID_curso)
            VALUES (?, ?, ?, ?)
        ");
        return $stmt->execute([$valor, $comentarios, $id_lista, $id_curso]);
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
