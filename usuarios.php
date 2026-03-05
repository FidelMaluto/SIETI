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
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Usuários - Inventário TI</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body>
<div class="container">

  <!-- HEADER -->
  <div class="sidebar">
    <h1>👥 Usuários (Admin)</h1>

    <div class="nav-links">
      <a href="index.php">🏠 Dashboard</a>
      <a href="equipamentos.php">💻 Equipamentos</a>
      <a href="historico.php">📋 Histórico</a>
      <a href="logout.php" class="logout">🚪 Sair</a>
    </div>
  </div>

  <!-- FORMULÁRIO -->
  <div class="card">
    <h2>➕ Cadastrar novo usuário</h2>

    <?php if($erro): ?>
      <div class="alert-error">
        <?= $erro ?>
      </div>
    <?php endif; ?>

    <form method="POST" class="user-form">
      <input name="nome" placeholder="Nome completo" required>
      <input name="usuario" placeholder="Usuário (login)" required>
      <input name="senha" type="password" placeholder="Senha" required>

      <select name="role">
        <option value="tecnico">Técnico</option>
        <option value="admin">Admin</option>
      </select>

      <button type="submit">Criar usuário</button>
    </form>
  </div>

  <!-- LISTA -->
  <div class="card">
    <h2>📋 Lista de usuários</h2>

    <div class="table-container">
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
              <td><?= date("d/m/Y H:i", strtotime($u["created_at"])) ?></td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>

  </div>

</div>
</body>
</html>
