<?php
require 'db.php';

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $pdo->prepare("DELETE FROM usuarios WHERE id_usuario = ?");
    $stmt->execute([$id]);
    header("Location: usuarios_listar.html");
    exit;
}
?>
