<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$grupo_id = $_GET['grupo_id'] ?? 0;

try {
    $stmt = $pdo->prepare("SELECT id FROM membros_grupo WHERE grupo_id = ? AND usuario_id = ?");
    $stmt->execute([$grupo_id, $_SESSION['user_id']]);
    
    if ($stmt->rowCount() == 0) {
        header("Location: meus-grupos.php");
        exit;
    }
    
    $stmt = $pdo->prepare("INSERT INTO listas (nome, grupo_id, criador_id) VALUES (?, ?, ?)");
    $stmt->execute(['Nova Lista', $grupo_id, $_SESSION['user_id']]);
    
    $lista_id = $pdo->lastInsertId();
    
    header("Location: lista.php?id=$lista_id");
    
} catch(PDOException $e) {
    echo "<script>alert('Erro ao criar lista!'); window.history.back();</script>";
}
?>