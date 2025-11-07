<?php
  $servidor = "localhost";
  $usuario = "root";
  $senha = "";
  $banco = "crud_mundo";

  // Conexão mysqli
  $conn = new mysqli($servidor, $usuario, $senha, $banco);

  if ($conn->connect_error) {
    die("Falha na conexão: " . $conn->connect_error);
  }

  $conn->set_charset("utf8");

  try {
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco;charset=utf8", $usuario, $senha);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
  } catch (PDOException $e) {
    die("Falha na conexão PDO: " . $e->getMessage());
  }
?>