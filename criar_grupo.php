<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO grupos (nome, admin_id) VALUES (?, ?)");
    $stmt->execute(['Novo Grupo', $_SESSION['user_id']]);
    
    $grupo_id = $pdo->lastInsertId();
    
    $stmt = $pdo->prepare("INSERT INTO membros_grupo (grupo_id, usuario_id) VALUES (?, ?)");
    $stmt->execute([$grupo_id, $_SESSION['user_id']]);
    
    header("Location: grupo.php?id=$grupo_id");
    
} catch(PDOException $e) {
    echo "<script>alert('Erro ao criar grupo!'); window.history.back();</script>";
}
?>