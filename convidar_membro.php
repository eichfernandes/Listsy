<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: meus-grupos.php");
    exit;
}

$grupo_id = $_POST['grupo_id'];
$username = trim($_POST['username']);

try {
    // Verificar se é admin
    $stmt = $pdo->prepare("SELECT admin_id FROM grupos WHERE id = ?");
    $stmt->execute([$grupo_id]);
    $grupo = $stmt->fetch();
    
    if ($grupo['admin_id'] != $_SESSION['user_id']) {
        echo "<script>alert('Sem permissão!'); window.history.back();</script>";
        exit;
    }
    
    // Buscar usuário
    $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
    $stmt->execute([$username]);
    $usuario = $stmt->fetch();
    
    if (!$usuario) {
        echo "<script>alert('Usuário não encontrado!'); window.history.back();</script>";
        exit;
    }
    
    // Verificar se já é membro
    $stmt = $pdo->prepare("SELECT id FROM membros_grupo WHERE grupo_id = ? AND usuario_id = ?");
    $stmt->execute([$grupo_id, $usuario['id']]);
    
    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Usuário já é membro!'); window.history.back();</script>";
        exit;
    }
    
    // Verificar se já existe convite pendente
    $stmt = $pdo->prepare("SELECT id FROM convites WHERE grupo_id = ? AND usuario_convidado_id = ? AND status = 'pendente'");
    $stmt->execute([$grupo_id, $usuario['id']]);
    
    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Já existe um convite pendente para este usuário!'); window.history.back();</script>";
        exit;
    }
    
    // Criar ou atualizar convite
    $stmt = $pdo->prepare("INSERT INTO convites (grupo_id, usuario_convidado_id, usuario_convidador_id, status) VALUES (?, ?, ?, 'pendente') ON DUPLICATE KEY UPDATE status = 'pendente', data_convite = CURRENT_TIMESTAMP");
    $stmt->execute([$grupo_id, $usuario['id'], $_SESSION['user_id']]);
    
    echo "<script>alert('Convite enviado!'); window.history.back();</script>";
    
} catch(PDOException $e) {
    echo "<script>alert('Erro ao convidar!'); window.history.back();</script>";
}
?>