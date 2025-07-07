<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php?redirect=login_required");
    exit;
}

$convite_id = $_GET['convite_id'];
$resposta = $_GET['resposta']; // 'aceitar' ou 'recusar'

try {
    // Verificar se o convite é para o usuário logado
    $stmt = $pdo->prepare("SELECT grupo_id, usuario_convidado_id FROM convites WHERE id = ? AND usuario_convidado_id = ? AND status = 'pendente'");
    $stmt->execute([$convite_id, $_SESSION['user_id']]);
    $convite = $stmt->fetch();
    
    if (!$convite) {
        echo "<script>alert('Convite não encontrado!'); window.history.back();</script>";
        exit;
    }
    
    if ($resposta === 'aceitar') {
        // Adicionar como membro
        $stmt = $pdo->prepare("INSERT INTO membros_grupo (grupo_id, usuario_id) VALUES (?, ?)");
        $stmt->execute([$convite['grupo_id'], $_SESSION['user_id']]);
        
        // Atualizar status do convite
        $stmt = $pdo->prepare("UPDATE convites SET status = 'aceito' WHERE id = ?");
        $stmt->execute([$convite_id]);
        
        echo "<script>alert('Convite aceito!'); window.location.href = 'convites.php';</script>";
    } else {
        // Recusar convite
        $stmt = $pdo->prepare("UPDATE convites SET status = 'recusado' WHERE id = ?");
        $stmt->execute([$convite_id]);
        
        echo "<script>alert('Convite recusado!'); window.location.href = 'convites.php';</script>";
    }
    
} catch(PDOException $e) {
    echo "<script>alert('Erro ao responder convite!'); window.history.back();</script>";
}
?>