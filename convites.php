<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Listsy - Convites</title>
    <style>
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
    
    $message = '';
    $message_type = '';
    
    // Processar resposta ao convite
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        $convite_id = $_POST['convite_id'];
        $resposta = $_POST['resposta']; // 'aceitar' ou 'recusar'
        
        try {
            // Verificar se o convite é para o usuário logado
            $stmt = $pdo->prepare("SELECT grupo_id, usuario_convidado_id FROM convites WHERE id = ? AND usuario_convidado_id = ? AND status = 'pendente'");
            $stmt->execute([$convite_id, $_SESSION['user_id']]);
            $convite = $stmt->fetch();
            
            if (!$convite) {
                $message = 'Convite não encontrado!';
                $message_type = 'danger';
            } else {
                if ($resposta === 'aceitar') {
                    // Adicionar como membro
                    $stmt = $pdo->prepare("INSERT INTO membros_grupo (grupo_id, usuario_id) VALUES (?, ?)");
                    $stmt->execute([$convite['grupo_id'], $_SESSION['user_id']]);
                    
                    // Atualizar status do convite
                    $stmt = $pdo->prepare("UPDATE convites SET status = 'aceito' WHERE id = ?");
                    $stmt->execute([$convite_id]);
                    
                    $message = 'Convite aceito com sucesso!';
                    $message_type = 'success';
                } else {
                    // Recusar convite
                    $stmt = $pdo->prepare("UPDATE convites SET status = 'recusado' WHERE id = ?");
                    $stmt->execute([$convite_id]);
                    
                    $message = 'Convite recusado!';
                    $message_type = 'info';
                }
            }
        } catch(PDOException $e) {
            $message = 'Erro ao responder convite!';
            $message_type = 'danger';
        }
    }
    include 'components/navbar.php'; 
    ?>
    <div class="content">
        <div class="box pag-membros-convites">
            <!--Essa página aqui só deve ser acessada se o usuário estiver logado-->
            <div class="title">
                <h1>Convites 
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                    <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                    </svg>
                </h1>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <!--bota pra aceitar os convite no alert mesmo pae-->
            <!--Clica no convite >> Quer entrar? >> Sim ou Não-->
            <div class="list-container">
                <?php
                require_once 'config/database.php';
                
                try {
                    $stmt = $pdo->prepare("
                        SELECT c.id, c.grupo_id, g.nome as grupo_nome, u.username as convidador
                        FROM convites c
                        JOIN grupos g ON c.grupo_id = g.id
                        JOIN usuarios u ON c.usuario_convidador_id = u.id
                        WHERE c.usuario_convidado_id = ? AND c.status = 'pendente'
                        ORDER BY c.data_convite DESC
                    ");
                    $stmt->execute([$_SESSION['user_id']]);
                    $convites = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    if (empty($convites)) {
                        echo "<p style='text-align: center; margin: 40px 0;'>Nenhum convite pendente.</p>";
                    } else {
                        foreach ($convites as $convite) {
                            $nomeGrupo = htmlspecialchars($convite['grupo_nome']);
                            $convidador = htmlspecialchars($convite['convidador']);
                            
                            echo "
                            <div class='convite title' style='margin: 0; justify-content: space-between;'>
                                <span>
                                    Convite de $convidador
                                    <h1>$nomeGrupo</h1>
                                </span>
                                <div style='display: flex; gap: 10px;'>
                                    <form method='POST' style='display: inline;'>
                                        <input type='hidden' name='action' value='responder'>
                                        <input type='hidden' name='convite_id' value='{$convite['id']}'>
                                        <input type='hidden' name='resposta' value='aceitar'>
                                        <button type='submit' style='background: #198754; color: white; padding: 8px 16px; border: none; border-radius: 4px;'>
                                            Aceitar
                                        </button>
                                    </form>
                                    <form method='POST' style='display: inline;'>
                                        <input type='hidden' name='action' value='responder'>
                                        <input type='hidden' name='convite_id' value='{$convite['id']}'>
                                        <input type='hidden' name='resposta' value='recusar'>
                                        <button type='submit' style='background: #dc3545; color: white; padding: 8px 16px; border: none; border-radius: 4px;'>
                                            Recusar
                                        </button>
                                    </form>
                                </div>
                            </div>
                            ";
                        }
                    }
                } catch(PDOException $e) {
                    echo "<p>Erro ao carregar convites.</p>";
                }
                ?>
            </div>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>