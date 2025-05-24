<?php
require_once '../config/db.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $usuario = $_POST['usuario'];
    $contrasena = $_POST['contrasena'];

    $db = new Database();
    $conn = $db->connect();

    $stmt = $conn->prepare("SELECT u.*, r.nombre_rol FROM Usuario u
                            JOIN rol_usuario r ON u.id_rol = r.id_rol
                            WHERE u.nombre_usuario = ? AND u.contraseña = ?");
    $stmt->execute([$usuario, $contrasena]);

    if ($stmt->rowCount() == 1) {
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        $_SESSION['id'] = $user['ID_usuario'];
        $_SESSION['rol'] = strtolower($user['nombre_rol']);

        if ($_SESSION['rol'] == 'docente') {
            header("Location: ../views/docente/dashboard.php");
        } else {
            header("Location: ../views/estudiante/dashboard.php");
        }
    } else {
        echo "<script>alert('Credenciales incorrectas'); window.location.href = '../views/login/login.php';</script>";
    }
}
?>
