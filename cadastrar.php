<?php
require_once 'config/database.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $password_repeat = $_POST['password_repeat'];
    
    if (empty($username) || empty($password) || empty($password_repeat)) {
        echo "<script>alert('Todos os campos são obrigatórios!'); window.history.back();</script>";
        exit;
    }
    
    if ($password !== $password_repeat) {
        echo "<script>alert('As senhas não coincidem!'); window.history.back();</script>";
        exit;
    }
    
    try {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
        $stmt->execute([$username]);
        
        if ($stmt->rowCount() > 0) {
            echo "<script>alert('Nome de usuário já existe!'); window.history.back();</script>";
            exit;
        }
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $pdo->prepare("INSERT INTO usuarios (username, senha) VALUES (?, ?)");
        $stmt->execute([$username, $hashed_password]);
        
        echo "<script>alert('Cadastro realizado com sucesso!'); window.location.href = 'login.php';</script>";
        
    } catch(PDOException $e) {
        echo "<script>alert('Erro: " . $e->getMessage() . "'); window.history.back();</script>";
    }
}
?>