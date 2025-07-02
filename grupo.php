<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Listsy - Grupo</title>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <div class="content">
        <div class="box">
            <!--Essa página aqui só deve ser acessada se o usuário estiver logado-->
            <div class="title">
                <!--Mude esse H1 e o title da página para o respectivo grupo acessado na navegação-->
                <h1>Família</h1>
                <!--Deve redirecionar para os membros do grupo corretamente-->
                <a href="membros.php">
                <div class="icon-text clicavel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                    <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                    </svg>
                    <h2>Convites</h2>
                </div>
                </a>
            </div>
            <!--Aqui eu entro com o grid de listas e faço a função que é usada para gerar itens no grid-->
            <div class="grid-container">
                <?php
                function criarGrupo($nomeGrupo) {
                    $nomeSeguro = htmlspecialchars($nomeGrupo);
                    // Esse nome seguro é pra evitar XSS
                    
                    // Altere a Div conforme necessário para que ao clicar nela redirecione para o grupo correto
                    return "
                    <div class=\"lista\">
                        <span>
                            <h3>$nomeSeguro</h3>
                        </span>
                    </div>
                    ";
                }

                // Chamando 3 exemplos aleatórios, aqui deve entrar um código para ler no BD e chamar no formato correto
                echo criarGrupo("Compra de Mercado");
                echo criarGrupo("Afazeres da Casa");
                echo criarGrupo("Móveis da Casa");
                ?>

                <!--Faça algo que ao clicar nessa div aqui abaixo seja criado um grupo chamado "Novo Grupo"-->
                <div class="lista novo">
                    +
                </div>
            </div>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>