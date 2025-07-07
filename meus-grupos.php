<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-LN+7fdVzj6u52u30Kp6M/trliBMCMKTyK833zpbD+pXdCLuTusPj697FH4R/5mcr" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/js/bootstrap.bundle.min.js" integrity="sha384-ndDqU0Gzau9qJ1lfW4pNLlhNTkCfHzAVBReH9diLvGRem5+R9g2FzA8ZGN954O5Q" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/main.css">
    <link rel="stylesheet" href="css/style.css">
    <title>Listsy - Grupos</title>
</head>
<body>
    <?php 
    session_start();
    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit;
    }
    include 'components/navbar.php'; 
    ?>
    <div class="content">
        <div class="box">
            <!--Essa página aqui só deve ser acessada se o usuário estiver logado-->
            <div class="title">
                <h1>Meus Grupos</h1>
                <a href="convites.php">
                <div class="icon-text clicavel">
                    <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                    <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                    </svg>
                    <h2>Convites</h2>
                </div>
                </a>
            </div>
            <!--Aqui eu entro com o grid de grupos e faço a função que é usada para gerar itens no grid-->
            <div class="grid-container">
                <?php
                require_once 'config/database.php';
                
                try {
                    $stmt = $pdo->prepare("
                        SELECT g.id, g.nome,
                               COUNT(DISTINCT mg.usuario_id) as num_membros,
                               COUNT(DISTINCT l.id) as num_listas
                        FROM grupos g
                        LEFT JOIN membros_grupo mg ON g.id = mg.grupo_id
                        LEFT JOIN listas l ON g.id = l.grupo_id
                        WHERE g.id IN (SELECT grupo_id FROM membros_grupo WHERE usuario_id = ?)
                        GROUP BY g.id, g.nome
                        ORDER BY g.data_criacao DESC
                    ");
                    $stmt->execute([$_SESSION['user_id']]);
                    $grupos = $stmt->fetchAll(PDO::FETCH_ASSOC);
                    
                    foreach ($grupos as $grupo) {
                        $nomeSeguro = htmlspecialchars($grupo['nome']);
                        echo "
                        <div class='grupo' onclick='window.location.href=\"grupo.php?id={$grupo['id']}\"'>
                            <span>
                                <h3>$nomeSeguro</h3>
                                <ul>
                                    <li>{$grupo['num_membros']} Membro(s)</li>
                                    <li>{$grupo['num_listas']} Lista(s)</li>
                                </ul>
                            </span>
                        </div>
                        ";
                    }
                } catch(PDOException $e) {
                    echo "<p>Erro ao carregar grupos.</p>";
                }
                ?>

                <div class="grupo novo" onclick="window.location.href='criar_grupo.php'">
                    +
                </div>
            </div>
        </div>
    </div>
    <?php include 'components/footer.php'; ?>
</body>
</html>