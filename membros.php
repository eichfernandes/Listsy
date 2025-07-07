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
    
    // Processar ações
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] === 'apagar_grupo') {
            try {
                // Verificar se é admin
                $stmt = $pdo->prepare("SELECT admin_id FROM grupos WHERE id = ?");
                $stmt->execute([$grupo_id]);
                $grupo_check = $stmt->fetch();
                
                if ($grupo_check['admin_id'] != $_SESSION['user_id']) {
                    $message = 'Sem permissão!';
                    $message_type = 'danger';
                } else {
                    // Deletar grupo (CASCADE vai deletar membros, listas e itens)
                    $stmt = $pdo->prepare("DELETE FROM grupos WHERE id = ?");
                    $stmt->execute([$grupo_id]);
                    
                    header("Location: meus-grupos.php");
                    exit;
                }
            } catch(PDOException $e) {
                $message = 'Erro ao apagar grupo!';
                $message_type = 'danger';
            }
        } elseif ($_POST['action'] === 'sair_grupo') {
            try {
                // Verificar se não é admin
                $stmt = $pdo->prepare("SELECT admin_id FROM grupos WHERE id = ?");
                $stmt->execute([$grupo_id]);
                $grupo_check = $stmt->fetch();
                
                if ($grupo_check['admin_id'] == $_SESSION['user_id']) {
                    $message = 'Admin não pode sair do grupo!';
                    $message_type = 'danger';
                } else {
                    $stmt = $pdo->prepare("DELETE FROM membros_grupo WHERE grupo_id = ? AND usuario_id = ?");
                    $stmt->execute([$grupo_id, $_SESSION['user_id']]);
                    
                    header("Location: meus-grupos.php");
                    exit;
                }
            } catch(PDOException $e) {
                $message = 'Erro ao sair do grupo!';
                $message_type = 'danger';
            }
        } elseif ($_POST['action'] === 'convidar') {
        $username = trim($_POST['username']);
        
        try {
            // Verificar se é admin
            $stmt = $pdo->prepare("SELECT admin_id FROM grupos WHERE id = ?");
            $stmt->execute([$grupo_id]);
            $grupo_check = $stmt->fetch();
            
            if ($grupo_check['admin_id'] != $_SESSION['user_id']) {
                $message = 'Sem permissão!';
                $message_type = 'danger';
            } elseif (empty($username)) {
                $message = 'Nome de usuário é obrigatório!';
                $message_type = 'danger';
            } else {
                // Buscar usuário
                $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE username = ?");
                $stmt->execute([$username]);
                $usuario = $stmt->fetch();
                
                if (!$usuario) {
                    $message = 'Usuário não encontrado!';
                    $message_type = 'danger';
                } else {
                    // Verificar se já é membro
                    $stmt = $pdo->prepare("SELECT id FROM membros_grupo WHERE grupo_id = ? AND usuario_id = ?");
                    $stmt->execute([$grupo_id, $usuario['id']]);
                    
                    if ($stmt->rowCount() > 0) {
                        $message = 'Usuário já é membro!';
                        $message_type = 'warning';
                    } else {
                        // Verificar se já existe convite pendente
                        $stmt = $pdo->prepare("SELECT id FROM convites WHERE grupo_id = ? AND usuario_convidado_id = ? AND status = 'pendente'");
                        $stmt->execute([$grupo_id, $usuario['id']]);
                        
                        if ($stmt->rowCount() > 0) {
                            $message = 'Já existe um convite pendente para este usuário!';
                            $message_type = 'warning';
                        } else {
                            // Criar ou atualizar convite
                            $stmt = $pdo->prepare("INSERT INTO convites (grupo_id, usuario_convidado_id, usuario_convidador_id, status) VALUES (?, ?, ?, 'pendente') ON DUPLICATE KEY UPDATE status = 'pendente', data_convite = CURRENT_TIMESTAMP, usuario_convidador_id = ?");
                            $stmt->execute([$grupo_id, $usuario['id'], $_SESSION['user_id'], $_SESSION['user_id']]);
                            
                            $message = 'Convite enviado com sucesso!';
                            $message_type = 'success';
                        }
                    }
                }
            }
        } catch(PDOException $e) {
            $message = 'Erro ao enviar convite!';
            $message_type = 'danger';
        }
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
        <div class="box pag-membros-convites">
            <!--Essa página aqui só deve ser acessada se o usuário estiver logado-->
            <div class="title">
                <h1>Membros em <?php echo htmlspecialchars($grupo['nome']); ?></h1>
            </div>
            
            <?php if (!empty($message)): ?>
                <div class="alert alert-<?= $message_type ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($message) ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
            
            <?php if ($is_admin): ?>
            <form class="add-person" style="margin-bottom:35px;" method="POST">
                <input type="hidden" name="action" value="convidar">
                <input type="text" name="username" placeholder="Usuário a adicionar" required value="<?= htmlspecialchars($_POST['username'] ?? '') ?>">
                <button type="submit" class="box-svg">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-person-fill-add" viewBox="0 0 16 16">
                    <path d="M12.5 16a3.5 3.5 0 1 0 0-7 3.5 3.5 0 0 0 0 7m.5-5v1h1a.5.5 0 0 1 0 1h-1v1a.5.5 0 0 1-1 0v-1h-1a.5.5 0 0 1 0-1h1v-1a.5.5 0 0 1 1 0m-2-6a3 3 0 1 1-6 0 3 3 0 0 1 6 0"/>
                    <path d="M2 13c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4"/>
                    </svg>
                </button>
            </form>
            <?php endif; ?>

            <!--Aqui eu entro com o grid de membros e faço a função que é usada para gerar membros no grid-->
            <div class="list-container">
                <?php
                try {
                    $stmt = $pdo->prepare("SELECT u.id, u.username FROM usuarios u 
                                          JOIN membros_grupo mg ON u.id = mg.usuario_id 
                                          WHERE mg.grupo_id = ? ORDER BY u.username");
                    $stmt->execute([$grupo_id]);
                    $membros = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($membros as $membro) {
                        $nomeSeguro = htmlspecialchars($membro['username']);
                        $is_group_admin = ($membro['id'] == $grupo['admin_id']);
                        
                        echo "<div class='membro title' style='margin: 0;'>";
                        echo "<span>$nomeSeguro";
                        
                        if ($is_group_admin) {
                            echo " <svg xmlns='http://www.w3.org/2000/svg' width='16' height='16' fill='currentColor' class='bi bi-person-fill-gear' viewBox='0 0 16 16'>
                                  <path d='M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5.256A4.5 4.5 0 0 1 8 12.5a4.5 4.5 0 0 1 1.544-3.393Q8.844 9.002 8 9c-5 0-6 3-6 4m9.886-3.54c.18-.613 1.048-.613 1.229 0l.043.148a.64.64 0 0 0 .921.382l.136-.074c.561-.306 1.175.308.87.869l-.075.136a.64.64 0 0 0 .382.92l.149.045c.612.18.612 1.048 0 1.229l-.15.043a.64.64 0 0 0-.38.921l.074.136c.305.561-.309 1.175-.87.87l-.136-.075a.64.64 0 0 0-.92.382l-.045.149c-.18.612-1.048.612-1.229 0l-.043-.15a.64.64 0 0 0-.921-.38l-.136.074c-.561.305-1.175-.309-.87-.87l.075-.136a.64.64 0 0 0-.382-.92l-.148-.045c-.613-.18-.613-1.048 0-1.229l.148-.043a.64.64 0 0 0 .382-.921l-.074-.136c-.306-.561.308-1.175.869-.87l.136.075a.64.64 0 0 0 .92-.382zM14 12.5a1.5 1.5 0 1 0-3 0 1.5 1.5 0 0 0 3 0'/>
                                  </svg>";
                        }
                        
                        echo "</span>";
                        
                        if ($is_admin && $membro['id'] != $_SESSION['user_id']) {
                            echo "<button data-bs-toggle='modal' data-bs-target='#modalRemover{$membro['id']}'>";
                            echo "<svg xmlns='http://www.w3.org/2000/svg' fill='currentColor' class='bi bi-trash3-fill' viewBox='0 0 16 16' style='max-width: 25px;'>";
                            echo "<path d='M11 1.5v1h3.5a.5.5 0 0 1 0 1h-.538l-.853 10.66A2 2 0 0 1 11.115 16h-6.23a2 2 0 0 1-1.994-1.84L2.038 3.5H1.5a.5.5 0 0 1 0-1H5v-1A1.5 1.5 0 0 1 6.5 0h3A1.5 1.5 0 0 1 11 1.5m-5 0v1h4v-1a.5.5 0 0 0-.5-.5h-3a.5.5 0 0 0-.5.5M4.5 5.029l.5 8.5a.5.5 0 1 0 .998-.06l-.5-8.5a.5.5 0 1 0-.998.06m6.53-.528a.5.5 0 0 0-.528.47l-.5 8.5a.5.5 0 0 0 .998.058l.5-8.5a.5.5 0 0 0-.47-.528M8 4.5a.5.5 0 0 0-.5.5v8.5a.5.5 0 0 0 1 0V5a.5.5 0 0 0-.5-.5'/>";
                            echo "</svg></button>";
                        }
                        
                        // Modal para remover membro
                        if ($is_admin && $membro['id'] != $_SESSION['user_id']) {
                            echo "
                            <div class='modal fade' id='modalRemover{$membro['id']}' tabindex='-1'>
                                <div class='modal-dialog'>
                                    <div class='modal-content'>
                                        <div class='modal-header'>
                                            <h5 class='modal-title'>Remover Membro</h5>
                                            <button type='button' class='btn-close' data-bs-dismiss='modal'></button>
                                        </div>
                                        <div class='modal-body'>
                                            <p>Tem certeza que deseja remover <strong>$nomeSeguro</strong> do grupo?</p>
                                        </div>
                                        <div class='modal-footer'>
                                            <button type='button' class='btn btn-secondary' data-bs-dismiss='modal'>Cancelar</button>
                                            <a href='remover_membro.php?grupo_id=$grupo_id&usuario_id={$membro['id']}' class='btn btn-danger'>Remover</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            ";
                        }
                        
                        echo "</div>";
                    }
                } catch(PDOException $e) {
                    echo "<p>Erro ao carregar membros.</p>";
                }
                ?>
            </div>
            <div style="text-align: center; margin-top: 40px;">
                <?php if ($is_admin): ?>
                    <button type="button" style="color: #dc3545;" data-bs-toggle="modal" data-bs-target="#modalApagarGrupo">
                        <h3>Apagar Grupo</h3>
                    </button>
                <?php else: ?>
                    <button type="button" data-bs-toggle="modal" data-bs-target="#modalSairGrupo">
                        <h3>Sair do Grupo</h3>
                    </button>
                <?php endif; ?>
            </div>
            
            <!-- Modal Apagar Grupo -->
            <div class="modal fade" id="modalApagarGrupo" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Apagar Grupo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p><strong>ATENÇÃO:</strong> Esta ação apagará permanentemente o grupo e todas as suas listas!</p>
                            <p>Tem certeza que deseja continuar?</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="apagar_grupo">
                                <button type="submit" class="btn btn-danger">Apagar Grupo</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Modal Sair do Grupo -->
            <div class="modal fade" id="modalSairGrupo" tabindex="-1">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Sair do Grupo</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>
                        <div class="modal-body">
                            <p>Tem certeza que deseja sair do grupo <strong><?= htmlspecialchars($grupo['nome']) ?></strong>?</p>
                            <p>Você precisará ser convidado novamente para voltar.</p>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="sair_grupo">
                                <button type="submit" class="btn btn-warning">Sair do Grupo</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>