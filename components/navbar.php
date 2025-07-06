<nav class="navbar">
  <!-- Título -->
  <div><a href="index.php"><h1>Listsy</h1></a></div>
  <!-- Direita -->
  <?php 
  session_start();
  $logado = isset($_SESSION['user_id']) ? 1 : 0;
  ?>

  <?php if ($logado == 1): ?>
  <div class="navbar_items">
    <a href="meus-grupos.php"><h2><?php echo $_SESSION['username']; ?></h2></a>
    <a href="logout.php"><h3>Logout</h3></a>
  </div>
  <?php endif ?>
  <?php if ($logado ==0): ?>
  <div class="navbar_items">
    <a href="cadastro.php"><h2>Cadastrar-se</h2></a>
    <a href="login.php"><h3>Já tenho uma conta<h3></a>
  </div>
  <?php endif ?>
</nav>