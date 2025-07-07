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
    <?php include 'auth_check.php'; ?>
    <?php include 'components/navbar.php'; ?>
    <div class="content">
        <div class="box login-cadastro">
            <h1>Listsy</h1>
            <h2>Cadastro</h2>
            <!--Faça sua mágica-->
            <form method="POST" action="cadastrar.php">
                <input type="text" name="username" placeholder="Nome de Usuário" style="margin-bottom: 30px" maxlength="20" required>
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