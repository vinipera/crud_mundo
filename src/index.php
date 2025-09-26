<?php
require 'config.php';

// Funções para operações CRUD (mantidas iguais)
function inserirPais($pdo, $nome, $continente, $populacao) {
    $stmt = $pdo->prepare("INSERT INTO paises (nome_pais, continente, populacao_pais) VALUES (?, ?, ?)");
    return $stmt->execute([$nome, $continente, $populacao]);
}

function atualizarPais($pdo, $id, $nome, $continente, $populacao) {
    $stmt = $pdo->prepare("UPDATE paises SET nome_pais = ?, continente = ?, populacao_pais = ? WHERE id_pais = ?");
    return $stmt->execute([$nome, $continente, $populacao, $id]);
}

function excluirPais($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM paises WHERE id_pais = ?");
    return $stmt->execute([$id]);
}

function inserirCidade($pdo, $nome, $populacao, $id_pais) {
    $stmt = $pdo->prepare("INSERT INTO cidades (nome_cidade, populacao_cidade, id_pais) VALUES (?, ?, ?)");
    return $stmt->execute([$nome, $populacao, $id_pais]);
}

function atualizarCidade($pdo, $id, $nome, $populacao, $id_pais) {
    $stmt = $pdo->prepare("UPDATE cidades SET nome_cidade = ?, populacao_cidade = ?, id_pais = ? WHERE id_cidade = ?");
    return $stmt->execute([$nome, $populacao, $id_pais, $id]);
}

function excluirCidade($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM cidades WHERE id_cidade = ?");
    return $stmt->execute([$id]);
}

// Manipulação de exclusões via GET (com redirecionamento para si mesmo)
if (isset($_GET['delete_pais'])) {
    $id = filter_input(INPUT_GET, 'delete_pais', FILTER_VALIDATE_INT);
    if ($id) {
        excluirPais($pdo, $id);
    }
    header("Location: index.php");
    exit;
}

if (isset($_GET['delete_cidade'])) {
    $id = filter_input(INPUT_GET, 'delete_cidade', FILTER_VALIDATE_INT);
    if ($id) {
        excluirCidade($pdo, $id);
    }
    header("Location: index.php");
    exit;
}

// Manipulação de POSTs (inserir/atualizar) - tudo no index.php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'insert_pais':
            $nome = trim($_POST['nome_pais'] ?? '');
            $continente = $_POST['continente'] ?? '';
            $populacao = filter_input(INPUT_POST, 'populacao_pais', FILTER_VALIDATE_INT);
            if ($nome && $continente && $populacao !== false) {
                inserirPais($pdo, $nome, $continente, $populacao);
            }
            break;

        case 'update_pais':
            $id = filter_input(INPUT_POST, 'id_pais', FILTER_VALIDATE_INT);
            $nome = trim($_POST['nome_pais'] ?? '');
            $continente = $_POST['continente'] ?? '';
            $populacao = filter_input(INPUT_POST, 'populacao_pais', FILTER_VALIDATE_INT);
            if ($id && $nome && $continente && $populacao !== false) {
                atualizarPais($pdo, $id, $nome, $continente, $populacao);
            }
            break;

        case 'insert_cidade':
            $nome = trim($_POST['nome_cidade'] ?? '');
            $populacao = filter_input(INPUT_POST, 'populacao_cidade', FILTER_VALIDATE_INT);
            $id_pais = filter_input(INPUT_POST, 'id_pais', FILTER_VALIDATE_INT);
            if ($nome && $populacao !== false && $id_pais) {
                inserirCidade($pdo, $nome, $populacao, $id_pais);
            }
            break;

        case 'update_cidade':
            $id = filter_input(INPUT_POST, 'id_cidade', FILTER_VALIDATE_INT);
            $nome = trim($_POST['nome_cidade'] ?? '');
            $populacao = filter_input(INPUT_POST, 'populacao_cidade', FILTER_VALIDATE_INT);
            $id_pais = filter_input(INPUT_POST, 'id_pais', FILTER_VALIDATE_INT);
            if ($id && $nome && $populacao !== false && $id_pais) {
                atualizarCidade($pdo, $id, $nome, $populacao, $id_pais);
            }
            break;
    }

    // Redirecionamento para evitar reenvio do formulário (POST/Redirect/GET)
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>CRUD de Países e Cidades</title>
    <link rel="stylesheet" href="style.css"> 
</head>
<body>

<h1>CRUD de Países e Cidades</h1>

<h2>Adicionar Novo País</h2>
<form method="POST">
    <input type="hidden" name="action" value="insert_pais">
    
    <label for="nome_pais">Nome:</label>
    <input type="text" id="nome_pais" name="nome_pais" required>
    
    <label for="continente">Continente:</label>
    <select id="continente" name="continente" required>
        <option value="América do Norte">América do Norte</option>
        <option value="América do Sul">América do Sul</option>
        <option value="Europa">Europa</option>
        <option value="Ásia">Ásia</option>
        <option value="Oceania">Oceania</option>
        <option value="Antártica">Antártica</option>
    </select>
    
    <label for="populacao_pais">População:</label>
    <input type="number" id="populacao_pais" name="populacao_pais" required>
    
    <button type="submit">Adicionar País</button>
</form>

<h2>Adicionar Nova Cidade</h2>
<form method="POST">
    <input type="hidden" name="action" value="insert_cidade">
    
    <label for="nome_cidade">Nome:</label>
    <input type="text" id="nome_cidade" name="nome_cidade" required>
    
    <label for="populacao_cidade">População:</label>
    <input type="number" id="populacao_cidade" name="populacao_cidade" required>
    
    <label for="id_pais">País:</label>
    <select id="id_pais" name="id_pais" required>
        <option value="">Selecione um país</option>
        <?php
        $query = $pdo->query("SELECT id_pais, nome_pais FROM paises ORDER BY nome_pais");
        while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
            echo '<option value="' . htmlspecialchars($row['id_pais']) . '">' . htmlspecialchars($row['nome_pais']) . '</option>';
        }
        ?>
    </select>
    
    <button type="submit">Adicionar Cidade</button>
</form>

<h2>Países Cadastrados</h2>
<table>
    <tr>
        <th>Nome</th>
        <th>Continente</th>
        <th>População</th>
        <th>Ações</th>
    </tr>
    <?php
    $query = $pdo->query("SELECT * FROM paises ORDER BY nome_pais");
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $continenteEsc = htmlspecialchars($row['continente'], ENT_QUOTES, 'UTF-8'); // Para JS
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['nome_pais']) . '</td>';
        echo '<td>' . htmlspecialchars($row['continente']) . '</td>';
        echo '<td>' . number_format($row['populacao_pais']) . '</td>';
        echo '<td>
                <span class="edit-link" onclick="openEditPaisModal(' . $row['id_pais'] . ', \'' . htmlspecialchars($row['nome_pais'], ENT_QUOTES, 'UTF-8') . '\', \'' . $continenteEsc . '\', ' . $row['populacao_pais'] . ')">Editar</span> |
                <a href="?delete_pais=' . $row['id_pais'] . '" class="delete-link" onclick="return confirm(\'Confirmar exclusão?\')">Excluir</a>
              </td>';
        echo '</tr>';
    }
    ?>
</table>

<h2>Cidades Cadastradas</h2>
<table>
    <tr>
        <th>Nome</th>
        <th>População</th>
        <th>País</th>
        <th>Ações</th>
    </tr>
    <?php
    $query = $pdo->query("SELECT c.*, p.id_pais as pais_id, p.nome_pais FROM cidades c JOIN paises p ON c.id_pais = p.id_pais ORDER BY c.nome_cidade");
    while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
        $nomePaisEsc = htmlspecialchars($row['nome_pais'], ENT_QUOTES, 'UTF-8');
        echo '<tr>';
        echo '<td>' . htmlspecialchars($row['nome_cidade']) . '</td>';
        echo '<td>' . number_format($row['populacao_cidade']) . '</td>';
        echo '<td>' . htmlspecialchars($row['nome_pais']) . '</td>';
        echo '<td>
                <span class="edit-link" onclick="openEditCidadeModal(' . $row['id_cidade'] . ', \'' . htmlspecialchars($row['nome_cidade'], ENT_QUOTES, 'UTF-8') . '\', ' . $row['populacao_cidade'] . ', ' . $row['pais_id'] . ')">Editar</span> |
                <a href="?delete_cidade=' . $row['id_cidade'] . '" class="delete-link" onclick="return confirm(\'Confirmar exclusão?\')">Excluir</a>
              </td>';
        echo '</tr>';
    }
    ?>
</table>

<!-- Modal para Editar País -->
<div id="modalPais" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modalPais')">&times;</span>
        <h3>Editar País</h3>
        <form method="POST" id="formEditPais">
            <input type="hidden" name="action" value="update_pais">
            <input type="hidden" name="id_pais" id="edit_id_pais">
            
            <label for="edit_nome_pais">Nome:</label>
            <input type="text" id="edit_nome_pais" name="nome_pais" required>
            
            <label for="edit_continente">Continente:</label>
            <select id="edit_continente" name="continente" required>
                <option value="América do Norte">América do Norte</option>
                <option value="América do Sul">América do Sul</option>
                <option value="Europa">Europa</option>
                <option value="Ásia">Ásia</option>
                <option value="Oceania">Oceania</option>
                <option value="Antártica">Antártica</option>
            </select>
            
            <label for="edit_populacao_pais">População:</label>
            <input type="number" id="edit_populacao_pais" name="populacao_pais" required>
            
            <button type="submit">Atualizar País</button>
            <button type="button" class="btn-cancel" onclick="closeModal('modalPais')">Cancelar</button>
        </form>
    </div>
</div>

<!-- Modal para Editar Cidade -->
<div id="modalCidade" class="modal">
    <div class="modal-content">
        <span class="close" onclick="closeModal('modalCidade')">&times;</span>
        <h3>Editar Cidade</h3>
        <form method="POST" id="formEditCidade">
            <input type="hidden" name="action" value="update_cidade">
            <input type="hidden" name="id_cidade" id="edit_id_cidade">
            
            <label for="edit_nome_cidade">Nome:</label>
            <input type="text" id="edit_nome_cidade" name="nome_cidade" required>
            
            <label for="edit_populacao_cidade">População:</label>
            <input type="number" id="edit_populacao_cidade" name="populacao_cidade" required>
            
            <label for="edit_id_pais">País:</label>
            <select id="edit_id_pais" name="id_pais" required>
                <option value="">Selecione um país</option>
                <?php
                $query = $pdo->query("SELECT id_pais, nome_pais FROM paises ORDER BY nome_pais");
                while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                    echo '<option value="' . htmlspecialchars($row['id_pais']) . '">' . htmlspecialchars($row['nome_pais']) . '</option>';
                }
                ?>
            </select>
            
            <button type="submit">Atualizar Cidade</button>
            <button type="button" class="btn-cancel" onclick="closeModal('modalCidade')">Cancelar</button>
        </form>
    </div>
</div>

<script>
    // Funções para abrir modais de edição
    function openEditPaisModal(id, nome, continente, populacao) {
        document.getElementById('edit_id_pais').value = id;
        document.getElementById('edit_nome_pais').value = nome;
        document.getElementById('edit_continente').value = continente;
        document.getElementById('edit_populacao_pais').value = populacao;
        document.getElementById('modalPais').style.display = 'block';
    }

    function openEditCidadeModal(id, nome, populacao, id_pais) {
        document.getElementById('edit_id_cidade').value = id;
        document.getElementById('edit_nome_cidade').value = nome;
        document.getElementById('edit_populacao_cidade').value = populacao;
        document.getElementById('edit_id_pais').value = id_pais;
        document.getElementById('modalCidade').style.display = 'block';
    }

    // Função para fechar modal
    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    // Fechar modal ao clicar fora dele
    window.onclick = function(event) {
        const modais = document.getElementsByClassName('modal');
        for (let i = 0; i < modais.length; i++) {
            if (event.target === modais[i]) {
                modais[i].style.display = 'none';
            }
        }
    }

    // Fechar com ESC
    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') {
            const modais = document.getElementsByClassName('modal');
            for (let i = 0; i < modais.length; i++) {
                if (modais[i].style.display === 'block') {
                    modais[i].style.display = 'none';
                }
            }
        }
    });
</script>

</body>
</html>
