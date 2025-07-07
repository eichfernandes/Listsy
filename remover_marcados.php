<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$lista_id = $_GET['lista_id'];

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
    
    $stmt = $pdo->prepare("DELETE FROM itens_lista WHERE lista_id = ? AND marcado = 1");
    $stmt->execute([$lista_id]);
    
    header("Location: lista.php?id=$lista_id");
    
} catch(PDOException $e) {
    echo "<script>alert('Erro ao remover itens!'); window.history.back();</script>";
}
?>