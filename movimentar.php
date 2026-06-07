<?php
include("config/db.php");
include("config/auth.php");
// proteger();

$id = $_GET["id"] ?? null;
if (!$id) {
  header("Location: equipamentos.php");
  exit;
}

$stmt = $conn->prepare("SELECT * FROM equipamentos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();
$res = $stmt->get_result();

if ($res->num_rows !== 1) {
  die("Equipamento não encontrado.");
}

$equip = $res->fetch_assoc();
$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $tipo_mov = $_POST["tipo_mov"];
  $responsavel = $_POST["responsavel"];
  $observacao = $_POST["observacao"];

  // regra simples:
  // emprestimo -> status em_uso
  // devolução -> status estoque
  $novo_status = ($tipo_mov === "emprestimo") ? "em_uso" : "estoque";

  $stmt = $conn->prepare("UPDATE equipamentos SET status=?, responsavel=? WHERE id=?");
  $stmt->bind_param("ssi", $novo_status, $responsavel, $id);
  $stmt->execute();

  $stmt = $conn->prepare("
    INSERT INTO movimentacoes (equipamento_id, tipo_mov, responsavel, observacao) VALUES (?, ?, ?, ?)
  ");

  $stmt->bind_param("isss", $id, $tipo_mov, $responsavel, $observacao);
  $stmt->execute();

  header("Location: equipamentos.php");
  exit;
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Movimentar Equipamento</title>
  <link rel="stylesheet" href="assets/css/style02.css">
  <script src="assets/js/script.js" defer></script>
</head>

<body>

<button class="menu-toggle" id="menuToggle">
  ☰
</button>
  
<div class="container">

  <div class="card">
    <h1>Movimentar Equipamento</h1>
    <p><a href="equipamentos.php">⬅ Voltar</a></p>
  </div>

  <div class="card">
    <h2><?= htmlspecialchars($equip["nome"]) ?></h2>
    <p><b>Status atual:</b> <?= $equip["status"] ?></p>
    <p><b>Patrimônio:</b> <?= htmlspecialchars($equip["patrimonio"]) ?></p>
    <p><b>Serial:</b> <?= htmlspecialchars($equip["serial"]) ?></p>
  </div>

  <div class="card">
    <form method="POST">
      <select name="tipo_mov" required>
        <option value="emprestimo">Empréstimo</option>
        <option value="devolucao">Devolução</option>
      </select>

      <input name="responsavel" placeholder="Responsável (nome da pessoa)" required>
      <input name="observacao" placeholder="Observação (opcional)">

      <button type="submit">Registrar</button>
    </form>
  </div>
</div>

</body>

</html>
