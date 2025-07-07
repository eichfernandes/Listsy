<?php
session_start();
require_once 'config/database.php';

$message = '';
$message_type = '';

// Verificar se precisa mostrar aviso de login necessário
if (isset($_GET['redirect']) && $_GET['redirect'] === 'login_required') {
    $message = 'Você precisa fazer login para acessar esta página.';
    $message_type = 'warning';
}

// Processar login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    
    if (empty($username) || empty($password)) {
        $message = 'Todos os campos são obrigatórios!';
        $message_type = 'danger';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id, username, senha FROM usuarios WHERE username = ?");
            $stmt->execute([$username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($user && password_verify($password, $user['senha'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                header('Location: meus-grupos.php');
                exit;
            } else {
                $message = 'Usuário ou senha incorretos!';
                $message_type = 'danger';
            }
            
        } catch(PDOException $e) {
            $message = 'Erro interno. Tente novamente.';
            $message_type = 'danger';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Listsy - Login</title>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <div class="content">
        <div class="box login-cadastro">
            <h1>Listsy</h1>
            <h2>Login</h2>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="text" name="username" placeholder="Nome de Usuário" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                <input type="password" name="password" placeholder="Senha" required>
                <div style="text-align: center;">
                    <button type="submit" class="btn btn-maingreen text-mainwhite">Entrar</button>
                </div>
            </form>
            <p>Não tem uma conta? <a href="cadastro.php" style="font-weight: 700;">Cadastre-se aqui</a></p>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>