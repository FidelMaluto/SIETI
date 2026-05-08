<?php
include("config/db.php");
include("config/auth.php");
// proteger();

$busca = $_GET["busca"] ?? "";
$status = $_GET["status"] ?? "";

$sql = "SELECT * FROM equipamentos WHERE 1=1 ";
$params = [];
$types = "";

if ($busca !== "") {
  $sql .= " AND (nome LIKE ? OR tipo LIKE ? OR marca LIKE ? OR modelo LIKE ? OR serial LIKE ? OR patrimonio LIKE ?)";
  $b = "%$busca%";
  $params = array_merge($params, [$b,$b,$b,$b,$b,$b]);
  $types .= "ssssss";
}

if ($status !== "") {
  $sql .= " AND status = ?";
  $params[] = $status;
  $types .= "s";
}

$sql .= " ORDER BY id DESC";

$stmt = $conn->prepare($sql);

if (!empty($params)) {
  $stmt->bind_param($types, ...$params);
}

$stmt->execute();
$res = $stmt->get_result();
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Equipamentos - Inventário TI</title>
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

  <a href="index.php">🏠 Dashboard</a>
  <a href="equipamentos.php" class="active">💻 Equipamentos</a>
  <a href="historico.php">📋 Histórico</a>

  <?php if(isAdmin()): ?>
    <a href="usuarios.php">👥 Usuários</a>
  <?php endif; ?>

  <a href="logout.php">🚪 Sair</a>
</div>

<div class="main">

  <div class="topbar">
    <h1>Equipamentos</h1>
    <div class="user">
      👤 <?= $_SESSION["user"]["nome"] ?>
    </div>
  </div>

  <!-- FILTROS -->
  <div class="card">

    <form method="GET" class="filter-form">

      <input 
        name="busca" 
        placeholder="🔍 Buscar por nome, serial, patrimônio..."
        value="<?= htmlspecialchars($busca) ?>"
      >

      <select name="status">
        <option value="">-- Todos os status --</option>
        <option value="estoque" <?= $status=="estoque"?"selected":"" ?>>Estoque</option>
        <option value="em_uso" <?= $status=="em_uso"?"selected":"" ?>>Em uso</option>
        <option value="manutencao" <?= $status=="manutencao"?"selected":"" ?>>Manutenção</option>
        <option value="baixado" <?= $status=="baixado"?"selected":"" ?>>Baixado</option>
      </select>

      <button type="submit">Filtrar</button>

      <a href="equip_form.php" class="btn-add">
        ➕ Novo equipamento
      </a>

    </form>

  </div>

  <!-- TABELA -->
  <div class="card">

    <div class="table-container">
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Tipo</th>
            <th>Serial</th>
            <th>Patrimônio</th>
            <th>Status</th>
            <th>Local</th>
            <th>Ações</th>
          </tr>
        </thead>
        <tbody>

          <?php while($e = $res->fetch_assoc()): ?>
          <tr>
            <td><?= $e["id"] ?></td>
            <td><?= htmlspecialchars($e["nome"]) ?></td>
            <td><?= htmlspecialchars($e["tipo"]) ?></td>
            <td><?= htmlspecialchars($e["serial"]) ?></td>
            <td><?= htmlspecialchars($e["patrimonio"]) ?></td>
            <td>
              <span class="badge <?= $e["status"] ?>">
                <?= $e["status"] ?>
              </span>
            </td>
            <td><?= htmlspecialchars($e["localizacao"]) ?></td>
            <td class="acoes">

              <a href="equip_form.php?id=<?= $e["id"] ?>" class="btn-edit">
                ✏️
              </a>

              <a href="movimentar.php?id=<?= $e["id"] ?>" class="btn-move">
                🔁
              </a>

              <?php if(isAdmin()): ?>
                <a href="equip_exclui.php?id=<?= $e["id"] ?>"
                   onclick="return confirm('Tem certeza que deseja excluir?')"
                   class="btn-delete">
                   🗑️
                </a>
              <?php endif; ?>

            </td>
          </tr>
          <?php endwhile; ?>

        </tbody>
      </table>
    </div>

  </div>

</div>

</body>
</body>
</html>
