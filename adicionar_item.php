<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: meus-grupos.php");
    exit;
}

$lista_id = $_POST['lista_id'];
$nome_item = trim($_POST['nome_item']);

if (empty($nome_item)) {
    echo "<script>alert('Nome do item não pode estar vazio!'); window.history.back();</script>";
    exit;
}

try {
    // Verificar se é membro do grupo
    $stmt = $pdo->prepare("SELECT l.grupo_id FROM listas l 
                          JOIN membros_grupo mg ON l.grupo_id = mg.grupo_id 
                          WHERE l.id = ? AND mg.usuario_id = ?");
    $stmt->execute([$lista_id, $_SESSION['user_id']]);
    
    if ($stmt->rowCount() == 0) {
        header("Location: meus-grupos.php");
        exit;
    }
    
    $stmt = $pdo->prepare("INSERT INTO itens_lista (lista_id, nome, criador_id) VALUES (?, ?, ?)");
    $stmt->execute([$lista_id, $nome_item, $_SESSION['user_id']]);
    
    header("Location: lista.php?id=$lista_id");
    
} catch(PDOException $e) {
    echo "<script>alert('Erro ao adicionar item!'); window.history.back();</script>";
}
?>