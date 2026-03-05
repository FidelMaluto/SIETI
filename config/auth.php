<?php
session_start();

function proteger() {
  if (!isset($_SESSION["user"])) {
    header("Location: login.php");
    exit;
  }
}

function isAdmin() {
  return isset($_SESSION["user"]) && $_SESSION["user"]["role"] === "admin";
}

function protegerAdmin() {
  proteger();
  if (!isAdmin()) {
    die("Acesso negado. Apenas ADMIN.");
  }
}
?>
  