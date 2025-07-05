<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Listsy - Compras de Mercado</title>
    <style>
        button{background: none; border: none; color: inherit; margin: none; padding: none;}
        button:hover{filter: brightness(1.2);}
    </style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <div class="content">
        <div class="box pag-membros-convites">
            <!--Essa página aqui só deve ser acessada se o usuário estiver logado-->
            <div class="title">
                <!--Mude esse H1 e o title da página para o respectivo grupo acessado na navegação-->
                <h1>Compras de Mercado</h1>
            </div>
            <div class="add-person" style="margin-bottom:35px;">
                <input type="text" placeholder="Adicionar item">
                <!--Usar como envio para o input acima-->
                <button class="box-svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                </button>
            </div>
            <div style="text-align: right; margin-bottom: 20px;">
            <!--Já sabe o que faze aqui-->
            <!--Coloque um alert de "Tem Certeza?"-->
                <button>
                    <h3>Remover todos os itens marcados</h3>
                </button>
            </div>
            <!--Aqui eu entro com o grid de Itens e faço a função que é usada para gerar membros no grid-->
            <div class="list-container">
                <?php
                function criarItem($nomeItem, $id, $marcado = false) {
                    $nomeItem = htmlspecialchars($nomeItem);
                    // Essa parte é pra evitar XSS

                    // Se marcado for true então a checkbox ja vai estar marcada
                    $checked = $marcado ? 'checked' : '';
                    
                    // Altere a Div conforme necessário 
                    // Faça que ao alterar esse input altere no backend (não permitir nome em branco)
                    // Se ele deixar em branco e desselecionar o item ele é deletado
                    return "
                    <div class=\"item title\" style=\"margin: 0;\">
                        <span>
                            <input type=\"text\" name=\"item_nome[$id]\" value=\"$nomeItem\">
                        </span>
                        <input type=\"checkbox\" name=\"item_check[$id]\" $checked style=\"width: 20px; height: 20px;\">
                    </div>
                    ";
                }

                // Chamando alguns exemplos aleatórios, aqui deve entrar um código para ler no BD e chamar no formato correto
                echo criarItem("Pão", "123");
                echo criarItem("Tomate", "4234", true);
                echo criarItem("Presunto", "asd32", true);
                echo criarItem("Queijo", "asdasd3");
                echo criarItem("Arroz", "3asd", true);
                ?>
            </div>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>