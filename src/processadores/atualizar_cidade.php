<?php
// header("Location: ../index.php"); // Esta linha já está correta para redirecionar para o diretório pai
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_cidade'];
    $nome = $_POST['nome_cidade'];
    $populacao = $_POST['populacao_cidade'];
    $id_pais = $_POST['id_pais'];

    $stmt = $pdo->prepare("UPDATE cidades SET nome_cidade = ?, populacao_cidade = ?, id_pais = ? WHERE id_cidade = ?");
    $stmt->execute([$nome, $populacao, $id_pais, $id]);

    header("Location: ../index.php"); // Corrigido para redirecionar para o diretório pai
    exit;
}
