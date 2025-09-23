<?php

require 'config.php';

$id = $_GET['id'];
$stmt = $pdo->prepare("SELECT * FROM paises WHERE id_pais = ?");
$stmt->execute([$id]);
$pais = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$pais) {
    echo "País não encontrado.";
    exit;
}
?>

<h2>Editar País</h2>
<form method="POST" action="processadores/atualizar_pais.php">
    <input type="hidden" name="id_pais" value="<?php echo $pais['id_pais']; ?>">
    <label>Nome:</label>
    <input type="text" name="nome_pais" value="<?php echo htmlspecialchars($pais['nome_pais']); ?>"><br>
    <label>Continente:</label>
    <select name="continente">
        <?php
        $continentes = ["América do Norte", "América do Sul", "Europa", "Ásia", "Oceania", "Antártica"];
        foreach ($continentes as $cont) {
            $selected = $pais['continente'] == $cont ? 'selected' : '';
            echo "<option value=\"$cont\" $selected>$cont</option>";
        }
        ?>
    </select><br>
    <label>População:</label>
    <input type="number" name="populacao_pais" value="<?php echo $pais['populacao_pais']; ?>"><br>
    <button type="submit">Salvar</button>
</form>