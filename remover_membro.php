<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$grupo_id = $_GET['grupo_id'];
$usuario_id = $_GET['usuario_id'];

try {
    // Verificar se é admin
    $stmt = $pdo->prepare("SELECT admin_id FROM grupos WHERE id = ?");
    $stmt->execute([$grupo_id]);
    $grupo = $stmt->fetch();
    
    if ($grupo['admin_id'] != $_SESSION['user_id']) {
        echo "<script>alert('Sem permissão!'); window.history.back();</script>";
        exit;
    }
    
    // Não pode remover a si mesmo
    if ($usuario_id == $_SESSION['user_id']) {
        echo "<script>alert('Não pode remover a si mesmo!'); window.history.back();</script>";
        exit;
    }
    
    $stmt = $pdo->prepare("DELETE FROM membros_grupo WHERE grupo_id = ? AND usuario_id = ?");
    $stmt->execute([$grupo_id, $usuario_id]);
    
    echo "<script>alert('Membro removido!'); window.history.back();</script>";
    
} catch(PDOException $e) {
    echo "<script>alert('Erro ao remover!'); window.history.back();</script>";
}
?>