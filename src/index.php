<?php
require 'config.php';

// Funções para operações CRUD (atualizadas com novos campos)
function inserirPais($pdo, $nome, $continente, $populacao, $capital = null, $moeda = null, $bandeira = null, $sigla = null, $idioma = null) {
    $stmt = $pdo->prepare("INSERT INTO paises (nome_pais, continente, populacao_pais, capital, moeda, bandeira, sigla, idioma) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    return $stmt->execute([$nome, $continente, $populacao, $capital, $moeda, $bandeira, $sigla, $idioma]);
}

function atualizarPais($pdo, $id, $nome, $continente, $populacao, $capital = null, $moeda = null, $bandeira = null, $sigla = null, $idioma = null) {
    $stmt = $pdo->prepare("UPDATE paises SET nome_pais = ?, continente = ?, populacao_pais = ?, capital = ?, moeda = ?, bandeira = ?, sigla = ?, idioma = ? WHERE id_pais = ?");
    return $stmt->execute([$nome, $continente, $populacao, $capital, $moeda, $bandeira, $sigla, $idioma, $id]);
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
            $capital = trim($_POST['capital'] ?? '');
            $moeda = trim($_POST['moeda'] ?? '');
            $sigla = trim($_POST['sigla'] ?? '');
            $idioma = trim($_POST['idioma'] ?? '');
            
            if ($nome && $continente && $populacao !== false) {
                inserirPais($pdo, $nome, $continente, $populacao, $capital, $moeda, null, $sigla, $idioma);
            }
            break;

        case 'update_pais':
            $id = filter_input(INPUT_POST, 'id_pais', FILTER_VALIDATE_INT);
            $nome = trim($_POST['nome_pais'] ?? '');
            $continente = $_POST['continente'] ?? '';
            $populacao = filter_input(INPUT_POST, 'populacao_pais', FILTER_VALIDATE_INT);
            $capital = trim($_POST['capital'] ?? '');
            $moeda = trim($_POST['moeda'] ?? '');
            $sigla = trim($_POST['sigla'] ?? '');
            $idioma = trim($_POST['idioma'] ?? '');
            
            if ($id && $nome && $continente && $populacao !== false) {
                atualizarPais($pdo, $id, $nome, $continente, $populacao, $capital, $moeda, null, $sigla, $idioma);
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

        case 'import_paises_api':
            // Integração com API pública de países (usaremos restcountries.com)
            $apiUrl = 'https://restcountries.com/v3.1/all?fields=name,region,population,languages,capital,currencies,flags,cca2';

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $apiUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 15);
            // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // descomente apenas em ambiente específico
            $resp = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            if ($resp && $httpCode >= 200 && $httpCode < 300) {
                $countries = json_decode($resp, true);
                if (is_array($countries)) {
                    $selectStmt = $pdo->prepare("SELECT id_pais FROM paises WHERE nome_pais = ? LIMIT 1");
                    $updateStmt = $pdo->prepare("UPDATE paises SET continente = ?, populacao_pais = ?, idioma = ?, capital = ?, moeda = ?, bandeira = ?, sigla = ? WHERE id_pais = ?");
                    $insertStmt = $pdo->prepare("INSERT INTO paises (nome_pais, continente, populacao_pais, idioma, capital, moeda, bandeira, sigla) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");

                    foreach ($countries as $c) {
                        $nome = $c['name']['common'] ?? null;
                        $continente = $c['region'] ?? 'Desconhecido';
                        $populacao = isset($c['population']) ? intval($c['population']) : 0;
                        
                        // Novos campos
                        $capital = isset($c['capital'][0]) ? $c['capital'][0] : 'Desconhecida';
                        $moeda = 'Desconhecida';
                        if (!empty($c['currencies']) && is_array($c['currencies'])) {
                            $moedas = [];
                            foreach ($c['currencies'] as $currency) {
                                $moedas[] = $currency['name'] ?? '';
                            }
                            $moeda = implode(', ', array_filter($moedas));
                        }
                        $bandeira = $c['flags']['png'] ?? $c['flags']['svg'] ?? null;
                        $sigla = $c['cca2'] ?? '';
                        
                        $idiomas = 'Desconhecido';
                        if (!empty($c['languages']) && is_array($c['languages'])) {
                            $idiomas = implode(', ', array_values($c['languages']));
                        }

                        if (!$nome) continue;
                        $nome = mb_substr(trim($nome), 0, 120);
                        $continente = mb_substr(trim($continente), 0, 120);
                        $idiomas = mb_substr(trim($idiomas), 0, 120);
                        $capital = mb_substr(trim($capital), 0, 100);
                        $moeda = mb_substr(trim($moeda), 0, 50);
                        $sigla = mb_substr(trim($sigla), 0, 5);

                        $selectStmt->execute([$nome]);
                        $row = $selectStmt->fetch(PDO::FETCH_ASSOC);
                        if ($row && isset($row['id_pais'])) {
                            $updateStmt->execute([$continente, $populacao, $idiomas, $capital, $moeda, $bandeira, $sigla, $row['id_pais']]);
                        } else {
                            $insertStmt->execute([$nome, $continente, $populacao, $idiomas, $capital, $moeda, $bandeira, $sigla]);
                        }
                    }
                }
            }
            break;
    }

    // Redirecionamento para evitar reenvio do formulário (POST/Redirect/GET)
    header("Location: index.php");
    exit;
}

// Sua API Key - SUBSTITUA pela sua chave
define('OPENWEATHER_API_KEY', '06cb432fb3eed4d1136c5929460bd323');

// Função para obter clima diretamente pelo nome da cidade
function obterClimaPorCidade($cidadeNome, $paisNome, $apiKey) {
    // Primeiro tenta buscar pelo nome da cidade + país
    $url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($cidadeNome) . "," . urlencode($paisNome) . "&appid=" . $apiKey . "&units=metric&lang=pt_br";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $resp = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($resp && $httpCode === 200) {
        return json_decode($resp, true);
    }
    
    // Se não encontrou, tenta apenas pelo nome da cidade
    $url = "https://api.openweathermap.org/data/2.5/weather?q=" . urlencode($cidadeNome) . "&appid=" . $apiKey . "&units=metric&lang=pt_br";
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 10);
    $resp = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($resp && $httpCode === 200) {
        return json_decode($resp, true);
    }
    
    return null;
}

// Manipulação de requisições de clima
if (isset($_GET['get_clima'])) {
    $idCidade = filter_input(INPUT_GET, 'get_clima', FILTER_VALIDATE_INT);
    
    if ($idCidade) {
        // Buscar cidade no banco
        $stmt = $pdo->prepare("SELECT c.*, p.nome_pais FROM cidades c JOIN paises p ON c.id_pais = p.id_pais WHERE c.id_cidade = ?");
        $stmt->execute([$idCidade]);
        $cidade = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($cidade) {
            // Buscar clima diretamente pelo nome da cidade
            $clima = obterClimaPorCidade($cidade['nome_cidade'], $cidade['nome_pais'], OPENWEATHER_API_KEY);
            
            if ($clima) {
                header('Content-Type: application/json');
                echo json_encode([
                    'success' => true,
                    'cidade' => $cidade['nome_cidade'],
                    'clima' => [
                        'temperatura' => round($clima['main']['temp']),
                        'descricao' => ucfirst($clima['weather'][0]['description']),
                        'umidade' => $clima['main']['humidity'],
                        'vento' => round($clima['wind']['speed'] * 3.6), // converter para km/h
                        'icone' => $clima['weather'][0]['icon']
                    ]
                ]);
                exit;
            }
        }
    }
    
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Não foi possível obter dados do clima']);
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Países e Cidades - Visão Futurista</title>
    <link rel="stylesheet" href="style.css">

    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;700&display=swap" rel="stylesheet">
    <style>
    @font-face {
        font-family: 'DROID';
        src: url('fonts/DROID.ttf') format('truetype');
        font-weight: normal;
        font-style: normal;
    }
    
    /* Bandeiras */
    .flag-img {
        width: 50px;
        height: 35px;
        border-radius: 3px;
        border: 1px solid rgba(222, 133, 0, 0.3);
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.3);
        transition: transform 0.2s ease;
    }
    
    .flag-img:hover {
        transform: scale(1.1);
    }
    
    .country-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .country-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 15px;
        margin-top: 10px;
        padding: 15px;
        background: rgba(222, 133, 0, 0.05);
        border-radius: 8px;
        border-left: 3px solid #de8500;
    }
    
    .detail-item {
        display: flex;
        flex-direction: column;
    }
    
    .detail-label {
        font-size: 0.8em;
        color: #de8500;
        font-weight: 500;
        margin-bottom: 2px;
    }
    
    .detail-value {
        font-size: 0.9em;
        color: #e0e0e0;
    }

    /* Estilos para clima */
    .btn-clima {
        background: linear-gradient(45deg, #de8500, #cd6700);
        color: white;
        border: none;
        padding: 8px 12px;
        border-radius: 5px;
        cursor: pointer;
        font-size: 0.8em;
        font-family: 'Roboto Mono', monospace;
        transition: all 0.3s ease;
    }

    .btn-clima:hover {
        transform: translateY(-1px);
        box-shadow: 0 4px 8px rgba(222, 133, 0, 0.3);
    }

    .btn-clima:disabled {
        opacity: 0.6;
        cursor: not-allowed;
    }

    .clima-info {
        margin-top: 8px;
        padding: 10px;
        background: rgba(222, 133, 0, 0.1);
        border-radius: 5px;
        border-left: 3px solid #de8500;
        font-size: 0.85em;
    }

    .weather-icon {
        width: 40px;
        height: 40px;
        vertical-align: middle;
    }

    .weather-details {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 8px;
        margin-top: 5px;
    }

    .weather-item {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .weather-label {
        font-size: 0.7em;
        color: #de8500;
        margin-bottom: 2px;
    }

    .weather-value {
        font-size: 0.9em;
        font-weight: 500;
    }
    </style>

</head>
<body>

    <div class="background-stars"></div>

    <!-- Vídeo de fundo fixo (50% opacidade) -->
    <video id="bgVideo" autoplay muted loop playsinline aria-hidden="true">
        <source src="assets/fundo_mundo.mp4" type="video/mp4">
        Seu navegador não suporta vídeo HTML5.
    </video>

    <div class="main-container">
        <div class="content-section">
            <header class="hero-section">
                <h1 class="hero-title">Explorando o <span class="gradient-text">Mundo Digital</span></h1>
                <p class="hero-subtitle">Uma jornada através dos dados geográficos, onde cada país e cidade é um ponto de luz no vasto universo da informação.</p>
            </header>

            <section class="crud-section">
                <!-- Botão para importar países de uma API RESTful pública -->
                <form method="POST" style="display:inline-block;margin-bottom:20px;">
                    <input type="hidden" name="action" value="import_paises_api">
                    <button type="submit" class="btn-primary">Importar países da API</button>
                </form>

                <h2>Adicionar Novo País</h2>
                <form method="POST" class="form-card">
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
                    
                    <label for="capital">Capital:</label>
                    <input type="text" id="capital" name="capital">
                    
                    <label for="moeda">Moeda:</label>
                    <input type="text" id="moeda" name="moeda">
                    
                    <label for="sigla">Sigla:</label>
                    <input type="text" id="sigla" name="sigla" maxlength="5">
                    
                    <label for="idioma">Idioma:</label>
                    <input type="text" id="idioma" name="idioma">
                    
                    <button type="submit" class="btn-primary">Adicionar País</button>
                </form>

                <h2>Adicionar Nova Cidade</h2>
                <form method="POST" class="form-card">
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
                    
                    <button type="submit" class="btn-primary">Adicionar Cidade</button>
                </form>

                <h2>Países Cadastrados</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Bandeira</th>
                                <th>Nome</th>
                                <th>Continente</th>
                                <th>População</th>
                                <th>Detalhes</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = $pdo->query("SELECT * FROM paises ORDER BY nome_pais");
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                $continenteEsc = htmlspecialchars($row['continente'], ENT_QUOTES, 'UTF-8');
                                $hasDetails = !empty($row['capital']) || !empty($row['moeda']) || !empty($row['sigla']) || !empty($row['idioma']);
                                
                                echo '<tr>';
                                echo '<td>';
                                if (!empty($row['bandeira'])) {
                                    echo '<img src="' . htmlspecialchars($row['bandeira']) . '" alt="Bandeira do ' . htmlspecialchars($row['nome_pais']) . '" class="flag-img">';
                                } else {
                                    echo '—';
                                }
                                echo '</td>';
                                echo '<td>' . htmlspecialchars($row['nome_pais']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['continente']) . '</td>';
                                echo '<td>' . number_format($row['populacao_pais']) . '</td>';
                                echo '<td>';
                                if ($hasDetails) {
                                    echo '<div class="country-details">';
                                    if (!empty($row['capital'])) {
                                        echo '<div class="detail-item"><span class="detail-label">Capital:</span><span class="detail-value">' . htmlspecialchars($row['capital']) . '</span></div>';
                                    }
                                    if (!empty($row['moeda'])) {
                                        echo '<div class="detail-item"><span class="detail-label">Moeda:</span><span class="detail-value">' . htmlspecialchars($row['moeda']) . '</span></div>';
                                    }
                                    if (!empty($row['sigla'])) {
                                        echo '<div class="detail-item"><span class="detail-label">Sigla:</span><span class="detail-value">' . htmlspecialchars($row['sigla']) . '</span></div>';
                                    }
                                    if (!empty($row['idioma'])) {
                                        echo '<div class="detail-item"><span class="detail-label">Idioma:</span><span class="detail-value">' . htmlspecialchars($row['idioma']) . '</span></div>';
                                    }
                                    echo '</div>';
                                } else {
                                    echo '—';
                                }
                                echo '</td>';
                                echo '<td>
                                        <span class="edit-link" onclick="openEditPaisModal(' . $row['id_pais'] . ', \'' . htmlspecialchars($row['nome_pais'], ENT_QUOTES, 'UTF-8') . '\', \'' . $continenteEsc . '\', ' . $row['populacao_pais'] . ', \'' . htmlspecialchars($row['capital'] ?? '', ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($row['moeda'] ?? '', ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($row['sigla'] ?? '', ENT_QUOTES, 'UTF-8') . '\', \'' . htmlspecialchars($row['idioma'] ?? '', ENT_QUOTES, 'UTF-8') . '\')">Editar</span> | 
                                        <a href="?delete_pais=' . $row['id_pais'] . '" class="delete-link" onclick="return confirm(\'Confirmar exclusão?\')">Excluir</a>
                                    </td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

                <h2>Cidades Cadastradas</h2>
                <div class="table-container">
                    <table>
                        <thead>
                            <tr>
                                <th>Nome</th>
                                <th>População</th>
                                <th>País</th>
                                <th>Clima</th>
                                <th>Ações</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = $pdo->query("SELECT c.*, p.id_pais as pais_id, p.nome_pais FROM cidades c JOIN paises p ON c.id_pais = p.id_pais ORDER BY c.nome_cidade");
                            while ($row = $query->fetch(PDO::FETCH_ASSOC)) {
                                $nomePaisEsc = htmlspecialchars($row['nome_pais'], ENT_QUOTES, 'UTF-8');
                                echo '<tr>';
                                echo '<td>' . htmlspecialchars($row['nome_cidade']) . '</td>';
                                echo '<td>' . number_format($row['populacao_cidade']) . '</td>';
                                echo '<td>' . htmlspecialchars($row['nome_pais']) . '</td>';
                                echo '<td>
                                        <button class="btn-clima" onclick="obterClima(' . $row['id_cidade'] . ', this)" data-loading-text="Carregando...">
                                            🌤️ Ver Clima
                                        </button>
                                        <div id="clima-' . $row['id_cidade'] . '" class="clima-info"></div>
                                    </td>';
                                echo '<td>
                                        <span class="edit-link" onclick="openEditCidadeModal(' . $row['id_cidade'] . ', \'' . htmlspecialchars($row['nome_cidade'], ENT_QUOTES, 'UTF-8') . '\', ' . $row['populacao_cidade'] . ', ' . $row['pais_id'] . ')">Editar</span> |
                                        <a href="?delete_cidade=' . $row['id_cidade'] . '" class="delete-link" onclick="return confirm(\'Confirmar exclusão?\')">Excluir</a>
                                    </td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </section>

            <footer class="futuristic-footer">
                <p>Sistema de Gestão Geográfica © 2024 - Explorando novos horizontes digitais</p>
            </footer>
        </div>

        <!-- Substituído: globo 3D removido por um visual leve e centrado -->
        <div class="space-visual" aria-hidden="true"></div>
    </div>

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
                
                <label for="edit_capital">Capital:</label>
                <input type="text" id="edit_capital" name="capital">
                
                <label for="edit_moeda">Moeda:</label>
                <input type="text" id="edit_moeda" name="moeda">
                
                <label for="edit_sigla">Sigla:</label>
                <input type="text" id="edit_sigla" name="sigla" maxlength="5">
                
                <label for="edit_idioma">Idioma:</label>
                <input type="text" id="edit_idioma" name="idioma">
                
                <button type="submit" class="btn-primary">Atualizar País</button>
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
                
                <button type="submit" class="btn-primary">Atualizar Cidade</button>
                <button type="button" class="btn-cancel" onclick="closeModal('modalCidade')">Cancelar</button>
            </form>
        </div>
    </div>

<script>
    function openEditPaisModal(id, nome, continente, populacao, capital, moeda, sigla, idioma) {
        document.getElementById('edit_id_pais').value = id;
        document.getElementById('edit_nome_pais').value = nome;
        document.getElementById('edit_continente').value = continente;
        document.getElementById('edit_populacao_pais').value = populacao;
        document.getElementById('edit_capital').value = capital || '';
        document.getElementById('edit_moeda').value = moeda || '';
        document.getElementById('edit_sigla').value = sigla || '';
        document.getElementById('edit_idioma').value = idioma || '';
        document.getElementById('modalPais').style.display = 'block';
    }

    function openEditCidadeModal(id, nome, populacao, id_pais) {
        document.getElementById('edit_id_cidade').value = id;
        document.getElementById('edit_nome_cidade').value = nome;
        document.getElementById('edit_populacao_cidade').value = populacao;
        document.getElementById('edit_id_pais').value = id_pais;
        document.getElementById('modalCidade').style.display = 'block';
    }

    function closeModal(modalId) {
        document.getElementById(modalId).style.display = 'none';
    }

    window.onclick = function(event) {
        const modais = document.getElementsByClassName('modal');
        for (let i = 0; i < modais.length; i++) {
            if (event.target === modais[i]) {
                modais[i].style.display = 'none';
            }
        }
    }

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

    async function obterClima(idCidade, botao) {
        const climaDiv = document.getElementById('clima-' + idCidade);
        const btnTextoOriginal = botao.innerHTML;
        
        // Mostrar loading
        botao.innerHTML = '⏳ Carregando...';
        botao.disabled = true;
        climaDiv.innerHTML = '';
        
        try {
            const response = await fetch('?get_clima=' + idCidade);
            const data = await response.json();
            
            if (data.success) {
                climaDiv.innerHTML = `
                    <div style="text-align: center;">
                        <strong>${data.cidade}</strong>
                        <div style="margin: 8px 0;">
                            <img src="https://openweathermap.org/img/wn/${data.clima.icone}.png" 
                                 alt="${data.clima.descricao}" 
                                 class="weather-icon">
                            <div style="font-size: 1.2em; font-weight: bold;">
                                ${data.clima.temperatura}°C
                            </div>
                        </div>
                        <div style="font-style: italic; margin-bottom: 8px;">
                            ${data.clima.descricao}
                        </div>
                        <div class="weather-details">
                            <div class="weather-item">
                                <span class="weather-label">💧 Umidade</span>
                                <span class="weather-value">${data.clima.umidade}%</span>
                            </div>
                            <div class="weather-item">
                                <span class="weather-label">💨 Vento</span>
                                <span class="weather-value">${data.clima.vento} km/h</span>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                climaDiv.innerHTML = '<span style="color: #ff6347;">❌ ' + (data.message || 'Erro ao carregar clima') + '</span>';
            }
        } catch (error) {
            climaDiv.innerHTML = '<span style="color: #ff6347;">❌ Erro de conexão</span>';
            console.error('Erro:', error);
        } finally {
            // Restaurar botão
            botao.innerHTML = btnTextoOriginal;
            botao.disabled = false;
        }
    }
</script>
</body>
</html>
