<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

require_once 'config/database.php';

$lista_id = $_GET['id'] ?? 0;
$user_id = $_SESSION['user_id'];

// Verificar se o usuário tem acesso à lista
try {
    $stmt = $pdo->prepare("
        SELECT l.*, g.nome as grupo_nome 
        FROM listas l 
        JOIN grupos g ON l.grupo_id = g.id 
        JOIN membros_grupo mg ON g.id = mg.grupo_id 
        WHERE l.id = ? AND mg.usuario_id = ?
    ");
    $stmt->execute([$lista_id, $user_id]);
    $lista = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$lista) {
        header("Location: meus-grupos.php");
        exit;
    }
} catch(PDOException $e) {
    header("Location: meus-grupos.php");
    exit;
}

// Processar ações
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        switch ($_POST['action']) {
            case 'add_item':
                $nome = trim($_POST['nome']);
                if (!empty($nome)) {
                    $stmt = $pdo->prepare("INSERT INTO itens_lista (lista_id, nome, criador_id) VALUES (?, ?, ?)");
                    $stmt->execute([$lista_id, $nome, $user_id]);
                }
                break;
                
            case 'toggle_item':
                $item_id = $_POST['item_id'];
                $stmt = $pdo->prepare("UPDATE itens_lista SET marcado = NOT marcado WHERE id = ? AND lista_id = ?");
                $stmt->execute([$item_id, $lista_id]);
                break;
                
            case 'update_item':
                $item_id = $_POST['item_id'];
                $nome = trim($_POST['nome']);
                if (empty($nome)) {
                    $stmt = $pdo->prepare("DELETE FROM itens_lista WHERE id = ? AND lista_id = ?");
                    $stmt->execute([$item_id, $lista_id]);
                } else {
                    $stmt = $pdo->prepare("UPDATE itens_lista SET nome = ? WHERE id = ? AND lista_id = ?");
                    $stmt->execute([$nome, $item_id, $lista_id]);
                }
                break;
                
            case 'remove_checked':
                $stmt = $pdo->prepare("DELETE FROM itens_lista WHERE lista_id = ? AND marcado = TRUE");
                $stmt->execute([$lista_id]);
                break;
                
            case 'update_lista_nome':
                $nome = trim($_POST['nome']);
                if (!empty($nome)) {
                    $stmt = $pdo->prepare("UPDATE listas SET nome = ? WHERE id = ?");
                    $stmt->execute([$nome, $lista_id]);
                }
                break;
        }
        header("Location: lista.php?id=$lista_id");
        exit;
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
    <title>Listsy - <?= htmlspecialchars($lista['nome']) ?></title>
    <style>
        button{background: none; border: none; color: inherit; margin: none; padding: none;}
        button:hover{filter: brightness(1.2);}
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
        <div class="box pag-membros-convites">
            <!--Essa página aqui só deve ser acessada se o usuário estiver logado-->
            <div class="title">
                <div class="editable-container">
                    <h1 id="titulo"><?= htmlspecialchars($lista['nome']) ?></h1>
                    <button class="icon-button" id="editar" title="Editar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                            <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                            <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
                        </svg>
                    </button>
                </div>
                
                <form class="editable-container hidden" id="editor" method="POST">
                    <input type="hidden" name="action" value="update_lista_nome">
                    <input type="text" name="nome" id="inputTitulo" maxlength="50" required>
                    <button type="submit" class="icon-button" id="salvar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-check-lg" viewBox="0 0 16 16">
                            <path d="M12.736 3.97a.733.733 0 0 1 1.047 0c.286.289.29.756.01 1.05L7.88 12.01a.733.733 0 0 1-1.065.02L3.217 8.384a.757.757 0 0 1 0-1.06.733.733 0 0 1 1.047 0l3.052 3.093 5.4-6.425z"/>
                        </svg>
                    </button>
                </form>
            </div>
            <form method="POST" class="add-person" style="margin-bottom:35px;">
                <input type="hidden" name="action" value="add_item">
                <input type="text" name="nome" placeholder="Adicionar item" required>
                <button type="submit" class="box-svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-plus" viewBox="0 0 16 16">
                    <path d="M8 4a.5.5 0 0 1 .5.5v3h3a.5.5 0 0 1 0 1h-3v3a.5.5 0 0 1-1 0v-3h-3a.5.5 0 0 1 0-1h3v-3A.5.5 0 0 1 8 4"/>
                    </svg>
                </button>
            </form>
            <div style="text-align: right; margin-bottom: 20px;">
                <form method="POST" onsubmit="return confirm('Tem certeza que deseja remover todos os itens marcados?')">
                    <input type="hidden" name="action" value="remove_checked">
                    <button type="submit">
                        <h3>Remover todos os itens marcados</h3>
                    </button>
                </form>
            </div>
            <div class="list-container">
                <?php
                try {
                    $stmt = $pdo->prepare("SELECT * FROM itens_lista WHERE lista_id = ? ORDER BY data_criacao ASC");
                    $stmt->execute([$lista_id]);
                    $itens = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($itens as $item) {
                        $nomeItem = htmlspecialchars($item['nome']);
                        $checked = $item['marcado'] ? 'checked' : '';
                        echo "
                        <div class='item title' style='margin: 0;'>
                            <span>
                                <input type='text' value='$nomeItem' onblur='updateItem({$item['id']}, this.value)'>
                            </span>
                            <input type='checkbox' $checked style='width: 20px; height: 20px;' onchange='toggleItem({$item['id']})'>
                        </div>
                        ";
                    }
                } catch(PDOException $e) {
                    echo "<p>Erro ao carregar itens.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
    
    <script>
    function toggleItem(itemId) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="toggle_item">
            <input type="hidden" name="item_id" value="${itemId}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
    
    function updateItem(itemId, nome) {
        const form = document.createElement('form');
        form.method = 'POST';
        form.innerHTML = `
            <input type="hidden" name="action" value="update_item">
            <input type="hidden" name="item_id" value="${itemId}">
            <input type="hidden" name="nome" value="${nome}">
        `;
        document.body.appendChild(form);
        form.submit();
    }
    
    // Funcionalidade de editar nome da lista
    const titulo = document.getElementById('titulo');
    const btnEditar = document.getElementById('editar');
    const editor = document.getElementById('editor');
    const inputTitulo = document.getElementById('inputTitulo');
    
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
</body>
</html>