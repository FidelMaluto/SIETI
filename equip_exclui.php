<?php
include("config/db.php");
include("config/auth.php");
protegerAdmin();

$id = $_GET["id"] ?? null;

if (!$id) {
  header("Location: equipamentos.php");
  exit;
}

$stmt = $conn->prepare("DELETE FROM equipamentos WHERE id=?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: equipamentos.php");
exit;
