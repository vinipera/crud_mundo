<?php
// header("Location: ../index.php"); // Esta linha já está correta para redirecionar para o diretório pai

require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome_pais = $_POST['nome_pais'];
    $populacao_pais = $_POST['populacao_pais'];
    $continente = $_POST['continente']; // <-- Captura o continente

    // Inserir país no banco de dados (incluindo o continente)
    $stmt = $pdo->prepare("INSERT INTO paises (nome_pais, populacao_pais, continente) VALUES (?, ?, ?)");
    $stmt->execute([$nome_pais, $populacao_pais, $continente]);

    // Redirecionar de volta para o formulário
    header("Location: ../index.php"); // Corrigido para redirecionar para o diretório pai
    exit;
}