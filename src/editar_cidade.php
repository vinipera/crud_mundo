<?php

require 'config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM cidades WHERE id_cidade = ?");
$stmt->execute([$id]);
$cidade = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$cidade) {
    echo "Cidade não encontrada.";
    exit;
}
?>

<h2>Editar Cidade</h2>
<form method="POST" action="processadores/atualizar_cidade.php">
    <input type="hidden" name="id_cidade" value="<?php echo $cidade['id_cidade']; ?>">
    <label>Nome:</label>
    <input type="text" name="nome_cidade" value="<?php echo htmlspecialchars($cidade['nome_cidade']); ?>"><br>
    <label>População:</label>
    <input type="number" name="populacao_cidade" value="<?php echo $cidade['populacao_cidade']; ?>"><br>
    <label>País:</label>
    <select name="id_pais">
        <?php
        $query = $pdo->query("SELECT id_pais, nome_pais FROM paises");
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            $selected = $cidade['id_pais'] == $row['id_pais'] ? 'selected' : '';
            echo '<option value="' . $row['id_pais'] . "\" $selected>" . htmlspecialchars($row['nome_pais']) . '</option>';
        }
        ?>
    </select><br>
    <button type="submit">Salvar</button>
</form>