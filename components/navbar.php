<nav class="navbar">
  <!-- Título -->
  <div><a href="https://www.w3schools.com"><h1>Listsy</h1></a></div>
  <!-- Direita -->
  <?php $logado = 0 ?>

  <?php if ($logado ==1): ?>
  <div class="navbar_items">
    <a href="https://www.w3schools.com"><h2>Nome_de_Usuário</h2></a>
    <a href="https://www.w3schools.com"><h3>Logout<h3></a>
  </div>
  <?php endif ?>
  <?php if ($logado ==0): ?>
  <div class="navbar_items">
    <a href="https://www.w3schools.com"><h2>Cadastrar-se</h2></a>
    <a href="https://www.w3schools.com"><h3>Já tenho uma conta<h3></a>
  </div>
  <?php endif ?>
</nav>