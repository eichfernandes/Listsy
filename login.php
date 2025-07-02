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
            <!--Faça sua mágica-->
            <forms>
                <input type="text" id="username" placeholder="Nome de Usuário">
                <input type="password" id="password" placeholder="Senha">
                <div style="text-align: center;">
                    <button type="button" class="btn btn-maingreen text-mainwhite">Entrar</a>
                </div>
            </forms>
            <p>Não tem uma conta? <a href="cadastro.php" style="font-weight: 700;">Cadastre-se aqui</a></p>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>