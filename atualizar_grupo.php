<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: meus-grupos.php");
    exit;
}

$grupo_id = $_POST['grupo_id'];
$novo_nome = trim($_POST['nome']);

if (empty($novo_nome)) {
    echo "<script>alert('Nome não pode estar vazio!'); window.history.back();</script>";
    exit;
}

try {
    $stmt = $pdo->prepare("UPDATE grupos SET nome = ? WHERE id = ? AND admin_id = ?");
    $stmt->execute([$novo_nome, $grupo_id, $_SESSION['user_id']]);
    
    if ($stmt->rowCount() > 0) {
        echo "<script>alert('Nome atualizado!'); window.location.href = 'grupo.php?id=$grupo_id';</script>";
    } else {
        echo "<script>alert('Erro: Você não tem permissão!'); window.history.back();</script>";
    }
    
} catch(PDOException $e) {
    echo "<script>alert('Erro ao atualizar!'); window.history.back();</script>";
}
?>