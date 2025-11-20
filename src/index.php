<?php
// Incluir o arquivo de processamento
require_once 'processador.php';
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD de Países e Cidades</title>
    <link rel="stylesheet" href="assets/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Mono:wght@300;400;500;700&display=swap" rel="stylesheet">
</head>
<body>

    <video id="bgVideo" autoplay muted loop playsinline aria-hidden="true">
        <source src="assets/fundo_mundo.mp4" type="video/mp4">
        Seu navegador não suporta vídeo.
    </video>
    
    <div class="main-container">
        <div class="content-section">
            <header class="hero-section">
                <h1 class="hero-title">Explorando o <span class="gradient-text">Mundo Digital</span></h1>
                <p class="hero-subtitle">Uma jornada através dos dados geográficos, onde cada país e cidade é um ponto de luz no vasto universo da informação.</p>

                <section class="stats-section">
                    <div class="stats-grid">
                        <div class="stat-card">
                            <span class="stat-value"><?php echo $totalPaises ?? 0; ?></span>
                            <span class="stat-label">Total de Países</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-value"><?php echo $totalCidades ?? 0; ?></span>
                            <span class="stat-label">Total de Cidades</span>
                        </div>
                        <div class="stat-card">
                            <span class="stat-value"><?php echo number_format($populacaoMundial ?? 0, 0, ',', '.'); ?></span>
                            <span class="stat-label">População Mundial</span>
                        </div>
                        <div class="stat-card">
                            <?php 
                                $nomePaisPop = $paisMaisPopuloso['nome_pais'] ?? 'N/A';
                                $isLong = strlen($nomePaisPop) > 15;
                            ?>
                            <span class="stat-value" <?php echo $isLong ? 'data-long-text="true"' : ''; ?>>
                                <?php echo htmlspecialchars($nomePaisPop); ?>
                            </span>
                            <span class="stat-label">País Mais Populoso</span>
                        </div>
                    </div>
                </section>
                </header>
            
            <a href="#secao-paises" class="scroll-down" aria-label="Ir para a seção de países">
                <button type="submit" class="btn-primary btn-nav">Consultar Países</button>
            </a>

            <a href="#secao-cidades" class="scroll-down" aria-label="Ir para a seção de cidades">
                <button type="submit" class="btn-primary btn-nav">Consultar Cidades</button>
            </a>
            
            <br> <br> <br> <br> <br> <br> <br> <br>
            <section class="crud-section">

                <div class="forms-side-by-side">
                    <div class="form-column">
    <h2>Adicionar Novo País</h2>

    <form method="POST" class="form-card" id="formPais" onsubmit="return validarPais(event)">
        <input type="hidden" name="action" value="insert_pais">
        <input type="hidden" name="dados_api" id="dados_api" value="">
        
        <label for="nome_pais">Nome do País:</label>
        <input type="text" id="nome_pais" name="nome_pais" required>
        
        <label for="continente">Continente:</label>
        <select id="continente" name="continente" required>
            <option value="América do Norte">América do Norte</option>
            <option value="América do Sul">América do Sul</option>
            <option value="Europa">Europa</option>
            <option value="Ásia">Ásia</option>
            <option value="Oceania">Oceania</option>
            <option value="Antártica">Antártica</option>
            <option value="África">África</option>
            <option value="América">América</option>
            <option value="América Central">América Central</option>
            <option value="Caribe">Caribe</option>
            <option value="Desconhecido">Desconhecido</option>
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
        
        <label for="bandeira">URL da Bandeira:</label>
        <input type="text" id="bandeira" name="bandeira">
        
        <button type="submit" class="btn-primary" id="btnSubmitPais">Adicionar País</button>
    </form>

    <form method="POST" class="import-form">
        <input type="hidden" name="action" value="import_paises_api">
        <?php
        $disabled = $totalPaises > 0 ? 'disabled' : '';
        $title = $totalPaises > 0 ? 'title="Importação já realizada anteriormente"' : '';
        ?>
        <button type="submit" class="btn-primary btn-import" <?php echo $disabled; ?> <?php echo $title; ?>>
            <?php echo $totalPaises > 0 ? 'Países Já Importados' : 'Importar países da API'; ?>
        </button>
    </form>

    <?php if (!empty($import_message)): ?>
        <div class="import-message">
            <?php echo htmlspecialchars($import_message); ?>
        </div>
    <?php endif; ?>
</div>

                    <div class="form-column">
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
                                <?php foreach ($paises as $pais): ?>
                                    <option value="<?php echo htmlspecialchars($pais['id_pais']); ?>">
                                        <?php echo htmlspecialchars($pais['nome_pais']); ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            
                            <button type="submit" class="btn-primary">Adicionar Cidade</button>
                        </form>
                    </div>
                </div>

                <div id="secao-paises">
                    <h2>Países Cadastrados</h2>

                <div class="search-section">
                    <div class="search-container">
                        <input type="text" id="searchInput" placeholder="Pesquisar países ou cidades..." 
                               onkeyup="filtrarTabelas()" aria-label="Campo de pesquisa">
                        <div class="search-info" id="searchInfo">
                            Digite para pesquisar em países e cidades
                        </div>
                    </div>
                </div>
                
                    <div class="table-container">
                        <table id="tabelaPaises">
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
                                <?php foreach ($paises as $pais): ?>
                                    <?php
                                    $continenteEsc = htmlspecialchars($pais['continente'], ENT_QUOTES, 'UTF-8');
                                    $hasDetails = !empty($pais['capital']) || !empty($pais['moeda']) || !empty($pais['sigla']) || !empty($pais['idioma']);
                                    ?>
                                    <tr class="pais-row" data-nome="<?php echo htmlspecialchars(strtolower($pais['nome_pais'])); ?>" data-continente="<?php echo htmlspecialchars(strtolower($pais['continente'])); ?>">
                                        <td>
                                            <?php if (!empty($pais['bandeira'])): ?>
                                                <img src="<?php echo htmlspecialchars($pais['bandeira']); ?>" alt="Bandeira do <?php echo htmlspecialchars($pais['nome_pais']); ?>" class="flag-img">
                                            <?php else: ?>
                                                —
                                            <?php endif; ?>
                                        </td>
                                        <td class="pais-nome"><?php echo htmlspecialchars($pais['nome_pais']); ?></td>
                                        <td class="pais-continente"><?php echo htmlspecialchars($pais['continente']); ?></td>
                                        <td class="pais-populacao"><?php echo formatPopulation($pais['populacao_pais']); ?></td>
                                        <td>
                                            <?php if ($hasDetails): ?>
                                                <div class="country-details">
                                                    <?php if (!empty($pais['capital'])): ?>
                                                        <div class="detail-item"><span class="detail-label">Capital:</span><span class="detail-value"><?php echo htmlspecialchars($pais['capital']); ?></span></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($pais['moeda'])): ?>
                                                        <div class="detail-item"><span class="detail-label">Moeda:</span><span class="detail-value"><?php echo htmlspecialchars($pais['moeda']); ?></span></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($pais['sigla'])): ?>
                                                        <div class="detail-item"><span class="detail-label">Sigla:</span><span class="detail-value"><?php echo htmlspecialchars($pais['sigla']); ?></span></div>
                                                    <?php endif; ?>
                                                    <?php if (!empty($pais['idioma'])): ?>
                                                        <div class="detail-item"><span class="detail-label">Idioma:</span><span class="detail-value"><?php echo htmlspecialchars($pais['idioma']); ?></span></div>
                                                    <?php endif; ?>
                                                </div>
                                            <?php else: ?>
                                                —
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <span class="edit-link" onclick="openEditPaisModal(
                                                <?php echo $pais['id_pais']; ?>,
                                                '<?php echo htmlspecialchars($pais['nome_pais'], ENT_QUOTES, 'UTF-8'); ?>',
                                                '<?php echo $continenteEsc; ?>',
                                                <?php echo $pais['populacao_pais']; ?>,
                                                '<?php echo htmlspecialchars($pais['capital'] ?? '', ENT_QUOTES, 'UTF-8'); ?>',
                                                '<?php echo htmlspecialchars($pais['moeda'] ?? '', ENT_QUOTES, 'UTF-8'); ?>',
                                                '<?php echo htmlspecialchars($pais['sigla'] ?? '', ENT_QUOTES, 'UTF-8'); ?>',
                                                '<?php echo htmlspecialchars($pais['idioma'] ?? '', ENT_QUOTES, 'UTF-8'); ?>',
                                                '<?php echo htmlspecialchars($pais['bandeira'] ?? '', ENT_QUOTES, 'UTF-8'); ?>'
                                            )">Editar</span> | 
                                            <a href="?delete_pais=<?php echo $pais['id_pais']; ?>" class="delete-link" onclick="return confirm('Confirmar exclusão?')">Excluir</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="no-results" id="noResultsPaises" style="display: none;">
                            <p>Nenhum país encontrado com o termo pesquisado.</p>
                        </div>
                    </div>
                </div>
                
                <div id="secao-cidades">
                    <h2>Cidades Cadastradas</h2>
                    <div class="table-container">
                        <table id="tabelaCidades">
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
                                <?php foreach ($cidades as $cidade): ?>
                                    <?php $nomePaisEsc = htmlspecialchars($cidade['nome_pais'], ENT_QUOTES, 'UTF-8'); ?>
                                    <tr class="cidade-row" data-nome="<?php echo htmlspecialchars(strtolower($cidade['nome_cidade'])); ?>" data-pais="<?php echo htmlspecialchars(strtolower($cidade['nome_pais'])); ?>">
                                        <td class="cidade-nome"><?php echo htmlspecialchars($cidade['nome_cidade']); ?></td>
                                        <td class="cidade-populacao"><?php echo formatPopulation($cidade['populacao_cidade']); ?></td>
                                        <td class="cidade-pais"><?php echo htmlspecialchars($cidade['nome_pais']); ?></td>
                                        <td>
                                            <button class="btn-clima" onclick="obterClimaModal(<?php echo $cidade['id_cidade']; ?>)">
                                                Ver Clima
                                            </button>
                                        </td>
                                        <td>
                                            <span class="edit-link" onclick="openEditCidadeModal(
                                                <?php echo $cidade['id_cidade']; ?>,
                                                '<?php echo htmlspecialchars($cidade['nome_cidade'], ENT_QUOTES, 'UTF-8'); ?>',
                                                <?php echo $cidade['populacao_cidade']; ?>,
                                                <?php echo $cidade['pais_id']; ?>
                                            )">Editar</span> |
                                            <a href="?delete_cidade=<?php echo $cidade['id_cidade']; ?>" class="delete-link" onclick="return confirm('Confirmar exclusão?')">Excluir</a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <div class="no-results" id="noResultsCidades" style="display: none;">
                            <p>Nenhuma cidade encontrada com o termo pesquisado.</p>
                        </div>
                    </div>
                </div>
            </section>

            <footer class="futuristic-footer">
                <p>Crud Mundo © 2025 - Vines</p>
            </footer>
        </div>

        <div class="space-visual" aria-hidden="true"></div>
    </div>

    <button id="btnTopo" class="btn-topo" title="Voltar ao topo">
        ↑
    </button>

    <?php include_once 'modals/modal_pais.php'; ?>
    <?php include_once 'modals/modal_cidade.php'; ?>
    <?php include_once 'modals/modal_clima.php'; ?>
    <?php include_once 'modals/modal_api_pais.php'; ?>

    <script src="assets/js/scripts.js"></script>
</body>
</html>