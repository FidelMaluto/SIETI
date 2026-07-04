<?php
include("config/db.php");
include("config/auth.php");
proteger();

$res = $conn->query("
  SELECT m.id, e.nome, e.patrimonio, m.tipo_mov, m.responsavel, m.observacao, m.data_mov
  FROM movimentacoes m
  JOIN equipamentos e ON m.equipamento_id = e.id
  ORDER BY m.data_mov DESC
");
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Histórico - Inventário TI</title>
  <link rel="stylesheet" href="assets/css/style.css">
  <script src="assets/js/script.js" defer></script>
</head>
<body>

<button class="menu-toggle" id="menuToggle">
  ☰
</button>

<div class="sidebar">
  <h2>Inventário TI</h2>

  <a href="index.php">Dashboard</a>
  <a href="equipamentos.php">Equipamentos</a>
  <a href="historico.php" class="active">Histórico</a>

  <?php if(isAdmin()): ?>
    <a href="usuarios.php">Usuários</a>
  <?php endif; ?>

  <a href="logout.php">Sair</a>
</div>

<div class="main">

  <div class="topbar">
    <h1>Histórico de Movimentações</h1>
    <div class="user">
      👤 <?= $_SESSION["user"]["nome"] ?>
    </div>
  </div>

  <div class="card">

    <div class="table-header">
      <input type="text" id="searchInput" class="busca" placeholder="Pesquisar movimentação...">
    </div>
        <br>
    <div class="table-container">
      <table id="filtrando">
        <thead>
          <tr>
            <th>ID</th>
            <th>Equipamento</th>
            <th>Patrimônio</th>
            <th>Movimentação</th>
            <th>Responsável</th>
            <th>Obs</th>
            <th>Data</th>
          </tr>
        </thead>
        <tbody>
          <?php while($m = $res->fetch_assoc()): ?>
            <tr>
              <td><?= $m["id"] ?></td>
              <td><?= htmlspecialchars($m["nome"]) ?></td>
              <td><?= htmlspecialchars($m["patrimonio"]) ?></td>
              <td>
                <span class="badge <?= $m["tipo_mov"] ?>">
                  <?= $m["tipo_mov"] ?>
                </span>
              </td>
              <td><?= htmlspecialchars($m["responsavel"]) ?></td>
              <td><?= htmlspecialchars($m["observacao"]) ?></td>
              <td><?= date("d/m/Y H:i", strtotime($m["data_mov"])) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </div>

</div>

</body>
</html>
