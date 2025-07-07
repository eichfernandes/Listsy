<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$grupo_id = $_GET['grupo_id'];

try {
    // Verificar se é admin
    $stmt = $pdo->prepare("SELECT admin_id FROM grupos WHERE id = ?");
    $stmt->execute([$grupo_id]);
    $grupo = $stmt->fetch();
    
    if ($grupo['admin_id'] == $_SESSION['user_id']) {
        echo "<script>alert('Admin não pode sair do grupo!'); window.history.back();</script>";
        exit;
    }
    
    $stmt = $pdo->prepare("DELETE FROM membros_grupo WHERE grupo_id = ? AND usuario_id = ?");
    $stmt->execute([$grupo_id, $_SESSION['user_id']]);
    
    echo "<script>alert('Você saiu do grupo!'); window.location.href = 'meus-grupos.php';</script>";
    
} catch(PDOException $e) {
    echo "<script>alert('Erro ao sair do grupo!'); window.history.back();</script>";
}
?>