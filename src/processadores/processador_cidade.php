<?php
// header("Location: ../index.php"); // Esta linha já está correta para redirecionar para o diretório pai
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_cidade = $_POST['nome_cidade'];
    $populacao_cidade = $_POST['populacao_cidade'];
    $pais_id = $_POST['id_pais'];
    
    $stmt = $pdo->prepare("INSERT INTO cidades (nome_cidade, populacao_cidade, id_pais) VALUES (?, ?, ?)");
    $stmt->execute([$nome_cidade, $populacao_cidade, $pais_id]);

    // Redirecionar de volta para o formulário
    header("Location: ../index.php"); // Corrigido para redirecionar para o diretório pai
    exit;
}