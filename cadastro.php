<?php
require_once 'config/database.php';

$message = '';
$message_type = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];
    $password_repeat = $_POST['password_repeat'];
    
    if (empty($username) || empty($password) || empty($password_repeat)) {
        $message = 'Todos os campos são obrigatórios!';
        $message_type = 'danger';
    } elseif ($password !== $password_repeat) {
        $message = 'As senhas não coincidem!';
        $message_type = 'danger';
    } else {
        try {
            $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
            $stmt->execute([$username]);
            
            if ($stmt->rowCount() > 0) {
                $message = 'Nome de usuário já existe!';
                $message_type = 'danger';
            } else {
                $hashed_password = password_hash($password, PASSWORD_DEFAULT);
                
                $stmt = $pdo->prepare("INSERT INTO usuarios (username, senha) VALUES (?, ?)");
                $stmt->execute([$username, $hashed_password]);
                
                $message = 'Cadastro realizado com sucesso!';
                $message_type = 'success';
                
                $_POST = array();
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
    <title>Listsy - Cadastro</title>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <div class="content">
        <div class="box login-cadastro">
            <h1>Listsy</h1>
            <h2>Cadastro</h2>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <form method="POST">
                <input type="text" name="username" placeholder="Nome de Usuário" style="margin-bottom: 30px" maxlength="20" required value="<?= htmlspecialchars(($message_type === 'danger' && isset($_POST['username'])) ? $_POST['username'] : '') ?>">
                <input type="password" name="password" placeholder="Senha" required>
                <input type="password" name="password_repeat" placeholder="Repita a Senha" required>
                <div style="text-align: center;">
                    <button type="submit" class="btn btn-maingreen text-mainwhite">Cadastrar</button>
                </div>
            </form>
            <p>Já tem uma conta? <a href="login.php" style="font-weight: 700;">Clique aqui para entrar</a></p>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>