<?php
$host = "localhost";
$user = "root";
$pass = "Angola@123";
$dbname = "inventario_ti_v2";

$conn = new mysqli($host, $user, $pass, $dbname);

if ($conn->connect_error) {
  die("Erro de conexão: " . $conn->connect_error);
}

$conn->set_charset("utf8mb4");
?>
