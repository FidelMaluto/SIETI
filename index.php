<?php
include("config/db.php");
include("config/auth.php");
proteger();

$total = $conn->query("SELECT COUNT(*) as n FROM equipamentos")->fetch_assoc()["n"];
$estoque = $conn->query("SELECT COUNT(*) as n FROM equipamentos WHERE status='estoque'")->fetch_assoc()["n"];
$emuso = $conn->query("SELECT COUNT(*) as n FROM equipamentos WHERE status='em_uso'")->fetch_assoc()["n"];
$manut = $conn->query("SELECT COUNT(*) as n FROM equipamentos WHERE status='manutencao'")->fetch_assoc()["n"];
$baix = $conn->query("SELECT COUNT(*) as n FROM equipamentos WHERE status='baixado'")->fetch_assoc()["n"];
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard - Inventário TI</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/script.js" defer></script>
</head>
<body>
<body>

<button class="menu-toggle" id="menuToggle">
  ☰
</button>

<div class="sidebar">
  <h2>Inventário TI</h2>

  <a href="index.php">Dashboard</a>
  <a href="equipamentos.php">Equipamentos</a>
  <a href="historico.php">Histórico</a>

  <?php if(isAdmin()): ?>
    <a href="usuarios.php">Usuários</a>
  <?php endif; ?>

  <a href="logout.php">Sair</a>
</div>

<div class="main">

  <div class="topbar">
    <h1>Dashboard</h1>
    <div class="user">
      👤 <?= $_SESSION["user"]["nome"] ?>
      (<?= strtoupper($_SESSION["user"]["role"]) ?>)
    </div>
  </div>

  <div class="grid">

    <div class="stat-card">
      <h2><?= $total ?></h2>
      <span>Total de Equipamentos</span>
    </div>

    <div class="stat-card">
      <h2><?= $estoque ?></h2>
      <span>Em Estoque</span>
    </div>

    <div class="stat-card">
      <h2><?= $emuso ?></h2>
      <span>Em Uso</span>
    </div>

    <div class="stat-card">
      <h2><?= $manut ?></h2>
      <span>Manutenção</span>
    </div>

    <div class="stat-card">
      <h2><?= $baix ?></h2>
      <span>Baixados</span>
    </div>

  </div>

</div>

</body>
</body>
</html>
