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
    <style>
        .editable-container {
        display: inline-flex;
        align-items: center;
        flex-wrap: wrap;
        gap: 0.5rem;
        }
        .hidden {
        display: none;
        }
        .box svg{
                height: clamp(15px, 5vw, 35px);
                width: clamp(15px, 5vw, 35px);
        }
        .box input{
            width: 42vw;
            max-width: 350px
        }
    </style>
</head>
<body>
    <?php include 'components/navbar.php'; ?>
    <div class="content">
        <div class="box">
            <!--Essa página aqui só deve ser acessada se o usuário estiver logado-->
            <div class="title">
                <!--Mude esse H1 e o title da página para o respectivo grupo acessado na navegação-->
                <div class="editable-container">
                    <h1 id="titulo">Família</h1>
                    <!--Este ícone só deve estar vísivel para o admin do grupo-->
                    <button class="icon-button" id="editar" title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                    </button>
                </div>

                <div class="editable-container hidden" id="editor">
                    <input type="text" id="inputTitulo" maxlength="40">
                    <button class="icon-button" id="salvar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                        </svg>
                    </button>
                </div>
                <!--Esse script permite a edição do titúlo da página, faça que altere no BD também-->
                <script>
                    const titulo = document.getElementById('titulo');
                    const btnEditar = document.getElementById('editar');
                    const editor = document.getElementById('editor');
                    const inputTitulo = document.getElementById('inputTitulo');
                    const btnSalvar = document.getElementById('salvar');

                    btnEditar.addEventListener('click', () => {
                        inputTitulo.value = titulo.textContent;
                        titulo.parentElement.classList.add('hidden');
                        editor.classList.remove('hidden');
                        inputTitulo.focus();
                    });

                    btnSalvar.addEventListener('click', () => {
                        titulo.textContent = inputTitulo.value.trim() || 'Sem título';
                        editor.classList.add('hidden');
                        titulo.parentElement.classList.remove('hidden');
                    });
                </script>




                <!--Deve redirecionar para os membros do grupo corretamente-->
                <a href="membros.php">
                <div class="icon-text clicavel">
                    <h2 style="text-align: right;">Ver Membros</h2>
                </div>
                </a>
            </div>
            <!--Aqui eu entro com o grid de listas e faço a função que é usada para gerar itens no grid-->
            <div class="grid-container">
                <?php
                function criarLista($nomeLista) {
                    $nomeSeguro = htmlspecialchars($nomeLista);
                    // Esse nome seguro é pra evitar XSS
                    
                    // Altere a Div conforme necessário para que ao clicar nela redirecione para a lista correta
                    return "
                    <div class=\"lista\">
                        <span>
                            <h3>$nomeSeguro</h3>
                        </span>
                    </div>
                    ";
                }

                // Chamando 3 exemplos aleatórios, aqui deve entrar um código para ler no BD e chamar no formato correto
                echo criarLista("Compra de Mercado");
                echo criarLista("Afazeres da Casa");
                echo criarLista("Móveis da Casa");
                ?>

                <!--Faça algo que ao clicar nessa div aqui abaixo seja criado uma lista chamado "Nova Lista"-->
                <div class="lista novo">
                    +
                </div>
            </div>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>