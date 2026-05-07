<?php
include("config/db.php");
include("config/auth.php");
// proteger();

$id = $_GET["id"] ?? null;
$editando = false;

$equip = [
  "nome"=>"",
  "tipo"=>"",
  "marca"=>"",
  "modelo"=>"",
  "serial"=>"",
  "patrimonio"=>"",
  "status"=>"estoque",
  "localizacao"=>"",
  "responsavel"=>"",
  "contato"=>"",
  "observacao"=>""
];

if ($id) {
  $stmt = $conn->prepare("SELECT * FROM equipamentos WHERE id=?");
  $stmt->bind_param("i", $id);
  $stmt->execute();
  $res = $stmt->get_result();

  if ($res->num_rows === 1) {
    $equip = $res->fetch_assoc();
    $editando = true;
  }
}

$erro = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $nome = $_POST["nome"];
  $tipo = $_POST["tipo"];
  $marca = $_POST["marca"];
  $modelo = $_POST["modelo"];
  $serial = $_POST["serial"];
  $patrimonio = $_POST["patrimonio"];
  $status = $_POST["status"];
  $localizacao = $_POST["localizacao"];
  $responsavel = $_POST["responsavel"];
  $contato = $_POST["contato"];
  $observacao = $_POST["observacao"];

  try {
    if ($editando) {
      $stmt = $conn->prepare("
        UPDATE equipamentos SET
          nome=?, tipo=?, marca=?, modelo=?, serial=?, patrimonio=?, status=?,
          localizacao=?, responsavel=?, contato=?, observacao=?
        WHERE id=?
      ");
      $stmt->bind_param("sssssssssssi",
        $nome,$tipo,$marca,$modelo,$serial,$patrimonio,$status,
        $localizacao,$responsavel,$contato,$observacao,$id
      );
      $stmt->execute();
    } else {
      $stmt = $conn->prepare("
        INSERT INTO equipamentos
          (nome,tipo,marca,modelo,serial,patrimonio,status,localizacao,responsavel,contato,observacao)
        VALUES (?,?,?,?,?,?,?,?,?,?,?)
      ");
      $stmt->bind_param("sssssssssss",
        $nome,$tipo,$marca,$modelo,$serial,$patrimonio,$status,
        $localizacao,$responsavel,$contato,$observacao
      );
      $stmt->execute();
    }

    header("Location: equipamentos.php");
    exit;

  } catch (Exception $ex) {
    $erro = "Erro ao salvar. Verifique se Serial ou Patrimônio já existem.";
  }
}
?>
<!doctype html>
<html lang="pt-br">
<head>
  <meta charset="utf-8">
  <title><?= $editando ? "Editar" : "Cadastrar" ?> Equipamento</title>
  <link rel="stylesheet" href="assets/style02.css">

</head>
<body>
<div class="container">

  <div class="card">
    <h1><?= $editando ? "Editar" : "Cadastrar" ?> Equipamento</h1>
    <p><a href="equipamentos.php">⬅ Voltar</a></p>
  </div>

  <div class="card">
    <?php if($erro): ?>
      <p style="color:#fb7185"><?= $erro ?></p>
    <?php endif; ?>

    <form method="POST">
      <input name="nome" placeholder="Nome do equipamento" value="<?= htmlspecialchars($equip["nome"]) ?>" required>
      <input name="tipo" placeholder="Tipo (Notebook, Monitor, Mouse...)" value="<?= htmlspecialchars($equip["tipo"]) ?>" required>

      <input name="marca" placeholder="Marca" value="<?= htmlspecialchars($equip["marca"]) ?>">
      <input name="modelo" placeholder="Modelo" value="<?= htmlspecialchars($equip["modelo"]) ?>">

      <input name="serial" placeholder="Serial" value="<?= htmlspecialchars($equip["serial"]) ?>">
      <input name="patrimonio" placeholder="Patrimônio" value="<?= htmlspecialchars($equip["patrimonio"]) ?>">

      <select name="status">
        <option value="estoque" <?= $equip["status"]=="estoque"?"selected":"" ?>>Estoque</option>
        <option value="em_uso" <?= $equip["status"]=="em_uso"?"selected":"" ?>>Em uso</option>
        <option value="manutencao" <?= $equip["status"]=="manutencao"?"selected":"" ?>>Manutenção</option>
        <option value="baixado" <?= $equip["status"]=="baixado"?"selected":"" ?>>Baixado</option>
      </select>

      <input name="localizacao" placeholder="Localização" value="<?= htmlspecialchars($equip["localizacao"]) ?>">
      <input name="responsavel" placeholder="Responsável (quem está com o equipamento)" value="<?= htmlspecialchars($equip["responsavel"]) ?>">
      <input name="contato" placeholder="Contato" value="<?= htmlspecialchars($equip["contato"]) ?>">

      <textarea name="observacao" placeholder="Observações"><?= htmlspecialchars($equip["observacao"]) ?></textarea>

      <button type="submit">Salvar</button>
    </form>
  </div>

</div>
</body>
</html>
