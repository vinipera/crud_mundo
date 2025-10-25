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

  // Definir o conjunto de caracteres para UTF-8
  $conn->set_charset("utf8");

  // Conexão PDO
  try {
    $pdo = new PDO("mysql:host=$servidor;dbname=$banco;charset=utf8", $usuario, $senha);
    // Definir o modo de erro do PDO para exceção
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
  } catch (PDOException $e) {
    die("Falha na conexão PDO: " . $e->getMessage());
  }
?>
