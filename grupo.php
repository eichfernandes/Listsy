<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Listsy - Família</title>
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
    <?php 
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php?redirect=login_required");
        exit;
    }
    
    require_once 'config/database.php';
    $grupo_id = $_GET['id'] ?? 0;
    
    $message = '';
    $message_type = '';
    
    // Processar atualização do nome
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'update_grupo_nome') {
        $nome = trim($_POST['nome']);
        
        try {
            if (empty($nome)) {
                $message = 'Nome não pode estar vazio!';
                $message_type = 'danger';
            } else {
                $stmt = $pdo->prepare("UPDATE grupos SET nome = ? WHERE id = ? AND admin_id = ?");
                $stmt->execute([$nome, $grupo_id, $_SESSION['user_id']]);
                
                if ($stmt->rowCount() == 0) {
                    $message = 'Sem permissão para alterar!';
                    $message_type = 'danger';
                }
            }
        } catch(PDOException $e) {
            $message = 'Erro ao atualizar nome!';
            $message_type = 'danger';
        }
        
        if (!empty($message)) {
            // Só redireciona se não houver erro
        } else {
            header("Location: grupo.php?id=$grupo_id");
            exit;
        }
    }
    
    try {
        $stmt = $pdo->prepare("SELECT g.nome, g.admin_id FROM grupos g 
                              JOIN membros_grupo mg ON g.id = mg.grupo_id 
                              WHERE g.id = ? AND mg.usuario_id = ?");
        $stmt->execute([$grupo_id, $_SESSION['user_id']]);
        $grupo = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$grupo) {
            header("Location: meus-grupos.php");
            exit;
        }
        
        $is_admin = ($grupo['admin_id'] == $_SESSION['user_id']);
    } catch(PDOException $e) {
        header("Location: meus-grupos.php");
        exit;
    }
    
    include 'components/navbar.php'; 
    ?>
    <div class="content">
        <div class="box">
            <!--Essa página aqui só deve ser acessada se o usuário estiver logado-->
            <div class="title">
                <!--Mude esse H1 e o title da página para o respectivo grupo acessado na navegação-->
                <div class="editable-container">
                    <h1 id="titulo"><?php echo htmlspecialchars($grupo['nome']); ?></h1>
                    <?php if ($is_admin): ?>
                    <button class="icon-button" id="editar" title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                    </button>
                    <?php endif; ?>
                </div>

                <?php if (!empty($message)): ?>
                    <div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert" style="margin-top: 15px;">
                        <?= htmlspecialchars($message) ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>
                
                <form class="editable-container hidden" id="editor" method="POST">
                    <input type="hidden" name="action" value="update_grupo_nome">
                    <input type="text" name="nome" id="inputTitulo" maxlength="40" required>
                    <button type="submit" class="icon-button" id="salvar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                        </svg>
                    </button>
                </form>
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

                    document.getElementById('editor').addEventListener('submit', (e) => {
                        if (!inputTitulo.value.trim()) {
                            e.preventDefault();
                            alert('Nome não pode estar vazio!');
                        }
                    });
                </script>




                <!--Deve redirecionar para os membros do grupo corretamente-->
                <a href="membros.php?id=<?php echo $grupo_id; ?>">
                <div class="icon-text clicavel">
                    <h2 style="text-align: right;">Ver Membros</h2>
                </div>
                </a>
            </div>
            <!--Aqui eu entro com o grid de listas e faço a função que é usada para gerar itens no grid-->
            <div class="grid-container">
                <?php
                try {
                    $stmt = $pdo->prepare("SELECT id, nome FROM listas WHERE grupo_id = ? ORDER BY data_criacao DESC");
                    $stmt->execute([$grupo_id]);
                    $listas = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($listas as $lista) {
                        $nomeSeguro = htmlspecialchars($lista['nome']);
                        echo "
                        <div class='lista' onclick='window.location.href=\"lista.php?id={$lista['id']}\"'>
                            <span>
                                <h3>$nomeSeguro</h3>
                            </span>
                        </div>
                        ";
                    }
                } catch(PDOException $e) {
                    echo "<p>Erro ao carregar listas.</p>";
                }
                ?>

                <div class="lista novo" onclick="window.location.href='criar_lista.php?grupo_id=<?php echo $grupo_id; ?>'">
                    +
                </div>
            </div>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>