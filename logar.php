<?php
session_start();
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        echo "<script>alert('Todos os campos são obrigatórios!'); window.history.back();</script>";
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT id, username, senha FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['senha'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            
            echo "<script>alert('Login realizado com sucesso!'); window.location.href = 'meus-grupos.php';</script>";
        } else {
            echo "<script>alert('Usuário ou senha incorretos!'); window.history.back();</script>";
        }
        
    } catch(PDOException $e) {
        echo "<script>alert('Erro: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>