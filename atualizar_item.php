<?php
session_start();
require_once 'config/database.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    exit;
}

$item_id = $_POST['item_id'];
$nome = trim($_POST['nome']);
$marcado = isset($_POST['marcado']) ? 1 : 0;

try {
    if (empty($nome)) {
        // Se nome vazio, deletar item
        $stmt = $pdo->prepare("DELETE FROM itens_lista WHERE id = ?");
        $stmt->execute([$item_id]);
    } else {
        // Atualizar item
        $stmt = $pdo->prepare("UPDATE itens_lista SET nome = ?, marcado = ? WHERE id = ?");
        $stmt->execute([$nome, $marcado, $item_id]);
    }
    
    echo "OK";
    
} catch(PDOException $e) {
    echo "ERRO";
}
?>