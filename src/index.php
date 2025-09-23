<?php require 'config.php' ?>

<h1> Crud de países </h1>

<form method="POST" action="processadores/processador_pais.php">

    <label for="nome_pais">Nome:</label>
    <input type="text" id="nome_pais" name="nome_pais">

    <br>

    <label for="continente">Escolha um continente:</label>
    <select id="continente" name="continente">
        <option value="América do Norte">América do Norte</option>
        <option value="América do Sul">América do Sul</option>
        <option value="Europa">Europa</option>
        <option value="Ásia">Ásia</option>
        <option value="Oceania">Oceania</option>
        <option value="Antártica">Antártica</option>
    </select>

    <br>
     
    <label for="populacao_pais">População:</label>
    <input type="number" id="populacao_pais" name="populacao_pais">

    <button type="submit">Enviar</button>

</form>

<form method="POST" action="processadores/processador_cidade.php">

    <label for="nome_cidade">Nome:</label>
    <input type="text" id="nome_cidade" name="nome_cidade">

    <br>

    <label for="populacao_cidade">População:</label>
    <input type="number" id="populacao_cidade" name="populacao_cidade">

    <br>

    <label for="id_pais">País:</label>
    <select id="id_pais" name="id_pais">
        <?php
        // Buscar países cadastrados no banco de dados
        $query = $pdo->query("SELECT id_pais, nome_pais FROM paises");
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . htmlspecialchars($row['id_pais']) . '">' . htmlspecialchars($row['nome_pais']) . '</option>';
        }
        ?>
    </select>

    <br> <br>
    <button type="submit">Enviar</button>

</form>

<h2>Países cadastrados</h2>
<table border="1">
    <tr>
        <th>Nome</th>
        <th>Continente</th>
        <th>População</th>
        <th>Ações</th>
    </tr>
    <?php
    $query = $pdo->query("SELECT * FROM paises");
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['nome_pais']) . '</td>';
        echo '<td>' . htmlspecialchars($row['continente']) . '</td>';
        echo '<td>' . htmlspecialchars($row['populacao_pais']) . '</td>';
        echo '<td><a href="editar_pais.php?id=' . $row['id_pais'] . '">Editar</a></td>';
        echo '</tr>';
    }
    ?>
</table>

<h2>Cidades cadastradas</h2>
<table border="1">
    <tr>
        <th>Nome</th>
        <th>População</th>
        <th>País</th>
        <th>Ações</th>
    </tr>
    <?php
    $query = $pdo->query("SELECT c.*, p.nome_pais FROM cidades c JOIN paises p ON c.id_pais = p.id_pais");
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['nome_cidade']) . '</td>';
        echo '<td>' . htmlspecialchars($row['populacao_cidade']) . '</td>';
        echo '<td>' . htmlspecialchars($row['nome_pais']) . '</td>';
        echo '<td><a href="editar_cidade.php?id=' . $row['id_cidade'] . '">Editar</a></td>';
        echo '</tr>';
    }
    ?>
</table>