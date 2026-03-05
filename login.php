<?php
include("config/db.php");
session_start();

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $usuario = $_POST["usuario"];
  $senha = $_POST["senha"];

  $stmt = $conn->prepare("SELECT * FROM usuarios WHERE usuario=? LIMIT 1");
  $stmt->bind_param("s", $usuario);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows === 1) {
    $u = $res->fetch_assoc();

    if (password_verify($senha, $u["senha"])) {
      $_SESSION["user"] = [
        "id" => $u["id"],
        "nome" => $u["nome"],
        "usuario" => $u["usuario"],
        "role" => $u["role"]
      ];
      header("Location: index.php");
      exit;
    }
  }

  $erro = "Usuário ou senha inválidos.";
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title>Login - Inventário TI</title>
  <link rel="stylesheet" href="assets/style.css">
</head>
<body class="login-body">

<div class="login-wrapper">

  <div class="login-card">

    <h1>🔐 Inventário TI</h1>
    <p class="subtitle">Acesse o sistema</p>

    <?php if($erro): ?>
      <div class="alert-error">
        <?= $erro ?>
      </div>
    <?php endif; ?>

    <form method="POST">

      <div class="input-group">
        <span>👤</span>
        <input name="usuario" placeholder="Usuário" required>
      </div>

      <div class="input-group">
        <span>🔒</span>
        <input name="senha" type="password" placeholder="Senha" required>
      </div>

      <button type="submit" class="btn-login">
        Entrar
      </button>

    </form>

    <p class="login-info">
      ⚠️Tens uma conta?
    </p>

  </div>

</div>

</body>
</html>
