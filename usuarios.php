<?php
include("config/db.php");
include("config/auth.php");
protegerAdmin();

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nome = $_POST["nome"];
  $usuario = $_POST["usuario"];
  $senha = $_POST["senha"];
  $role = $_POST["role"];

  $hash = password_hash($senha, PASSWORD_DEFAULT);

  try {
    $stmt = $conn->prepare("INSERT INTO usuarios (nome, usuario, senha, role) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $nome, $usuario, $hash, $role);
    $stmt->execute();
  } catch (Exception $ex) {
    $erro = "Erro: usuário já existe.";
  }
}

$res = $conn->query("SELECT id, nome, usuario, role, created_at FROM usuarios ORDER BY id DESC");
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Usuários - Inventário TI</title>
  <link rel="stylesheet" href="assets/css/style02.css">
  <script src="assets/js/script.js" defer></script>
</head>
<body>
<button class="menu-toggle" id="menuToggle">
  ☰
</button>

<!-- SIDEBAR -->
<div class="sidebar">
  <h2>👥 Usuários</h2>

  <a href="index.php">🏠 Dashboard</a>
  <a href="equipamentos.php">💻 Equipamentos</a>
  <a href="historico.php">📋 Histórico</a>
  <a href="logout.php">🚪 Sair</a>
</div>

<!-- MAIN -->
<div class="main">

  <!-- CARD FORM -->
  <div class="card">

    <h1>➕ Cadastrar novo usuário</h1>

    <?php if($erro): ?>
      <div class="alert-error">
        <?= $erro ?>
      </div>
    <?php endif; ?>

    <form method="POST">

      <input name="nome" placeholder="Nome completo" required>

      <input name="usuario" placeholder="Usuário" required>

      <input name="senha" type="password" placeholder="Senha" required>

      <select name="role">
        <option value="tecnico">Técnico</option>
        <option value="admin">Admin</option>
      </select>

      <button type="submit">
        Criar usuário
      </button>

    </form>
  </div>

  <!-- CARD TABELA -->
  <div class="card">

    <div class="table-header">
      <h1>📋 Lista de usuários</h1>
      <input type="text" id="searchInput" class="busca" placeholder="🔍 Pesquisar movimentação...">
    </div><br>

    <div class="table-container" id = "filtrando">

      <table>

        <thead>
          <tr>
            <th>ID</th>
            <th>Nome</th>
            <th>Usuário</th>
            <th>Role</th>
            <th>Criado em</th>
          </tr>
        </thead>

        <tbody>

          <?php while($u = $res->fetch_assoc()): ?>

          <tr>
            <td><?= $u["id"] ?></td>

            <td><?= htmlspecialchars($u["nome"]) ?></td>

            <td><?= htmlspecialchars($u["usuario"]) ?></td>

            <td>
              <span class="badge <?= $u["role"] ?>">
                <?= $u["role"] ?>
              </span>
            </td>

            <td>
              <?= date("d/m/Y H:i", strtotime($u["created_at"])) ?>
            </td>
          </tr>

          <?php endwhile; ?>

        </tbody>

      </table>

    </div>
  </div>
</div>

</body>

</html>
