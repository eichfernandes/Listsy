<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Listsy - Membros</title>
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
                <h1>Membros em Família</h1>
            </div>
            <div class="add-person" style="margin-bottom:35px;">
                <input type="text" placeholder="Usuário a adicionar">
                <!--Usar como envio para o input acima-->
                <button class="box-svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-add" viewBox="0 0 16 16">
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"/>
                    </svg>
                </button>
            </div>

            <!--Aqui eu entro com o grid de membros e faço a função que é usada para gerar membros no grid-->
            <div class="list-container">
                <?php
                function criarMembro($nomePessoa) {
                    $nomePessoa = htmlspecialchars($nomePessoa);
                    // Esse nome seguro é pra evitar XSS
                    
                    // Altere a Div conforme necessário 
                    return "
                    <div class=\"membro title\" style=\"margin: 0;\">
                        <span>
                            $nomePessoa
                        </span>

                        <!--USAR ESTE ICONE SOMENTE SE FOR O ADM-->
                        <!--
                        <svg xmlns=\"http://www.w3.org/2000/svg\" width=\"16\" height=\"16\" fill=\"currentColor\" class=\"bi bi-person-fill-gear\" viewBox=\"0 0 16 16\">
                        <path d=\"M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0\"/>
                        </svg>
                        -->

                        <!--USAR ESTE ICONE PARA OS DEMAIS-->
                        <!--TRANSFORMAR ESTE EM BOTÃO PARA DELEÇÃO-->
                        <!--COLOQUE UM DAQLES ALERT OU SLA ANTES DE CONFIRMAR A DELEÇÃO-->
                        <button>
                            <svg xmlns=\"http://www.w3.org/2000/svg\" fill=\"currentColor\" class=\"bi bi-trash3-fill\" viewBox=\"0 0 16 16\" style=\"max-width: 25px;\">
                            <path d=\"M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5\"/>
                            </svg>
                        </button>
                    </div>
                    ";
                }

                // Chamando 3 exemplos aleatórios, aqui deve entrar um código para ler no BD e chamar no formato correto
                echo criarMembro("Eich_Rafael");
                echo criarMembro("Gabriel");
                echo criarMembro("GabiEich");
                ?>
            </div>
            <div style="text-align: center; margin-top: 40px;">
            <!--Já sabe o que faze aqui-->
                <button>
                    <h3>Sair do Grupo</h3>
                </button>
            </div>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>