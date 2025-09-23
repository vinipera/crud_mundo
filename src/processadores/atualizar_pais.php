<?php
// header("Location: ../index.php"); // Esta linha já está correta para redirecionar para o diretório pai
require '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_pais'];
    $nome = $_POST['nome_pais'];
    $continente = $_POST['continente'];
    $populacao = $_POST['populacao_pais'];

    $stmt = $pdo->prepare("UPDATE paises SET nome_pais = ?, continente = ?, populacao_pais = ? WHERE id_pais = ?");
    $stmt->execute([$nome, $continente, $populacao, $id]);

    header("Location: ../index.php"); // Corrigido para redirecionar para o diretório pai
    exit;
}