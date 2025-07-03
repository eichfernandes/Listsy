<nav class="navbar">
  <!-- Título -->
  <div><a href="index.php"><h1>Listsy</h1></a></div>
  <!-- Direita -->
  <?php $logado = 1 ?>

  <?php if ($logado ==1): ?>
  <div class="navbar_items">
    <a href="meus-grupos.php"><h2>Nome_de_Usuário</h2></a>
    <button><h3>Logout<h3></button>
  </div>
  <?php endif ?>
  <?php if ($logado ==0): ?>
  <div class="navbar_items">
    <a href="cadastro.php"><h2>Cadastrar-se</h2></a>
    <a href="login.php"><h3>Já tenho uma conta<h3></a>
  </div>
  <?php endif ?>
</nav>