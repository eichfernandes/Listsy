<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=login_required");
    exit;
}

$grupo_id = $_GET['grupo_id'];

try {
    // Verificar se é admin
    $stmt = $pdo->prepare("SELECT admin_id FROM grupos WHERE id = ?");
    $stmt->execute([$grupo_id]);
    $grupo = $stmt->fetch();
    
    if ($grupo['admin_id'] != $_SESSION['user_id']) {
        echo "<script>alert('Sem permissão!'); window.history.back();</script>";
        exit;
    }
    
    // Deletar grupo (CASCADE vai deletar membros, listas e itens)
    $stmt = $pdo->prepare("DELETE FROM grupos WHERE id = ?");
    $stmt->execute([$grupo_id]);
    
    echo "<script>alert('Grupo apagado!'); window.location.href = 'meus-grupos.php';</script>";
    
} catch(PDOException $e) {
    echo "<script>alert('Erro ao apagar grupo!'); window.history.back();</script>";
}
?>