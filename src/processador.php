<?php
// process.php

// Incluir configurações e controllers
require_once 'config/database.php';
require_once 'config/funcoes.php';
require_once 'controllers/PaisController.php';
require_once 'controllers/CidadeController.php';

// Iniciar sessão
session_start();

// Obter instância do banco
$database = Database::getInstance();
$pdo = $database->getPdo();

// Inicializar controllers
$paisController = new PaisController($pdo);
$cidadeController = new CidadeController($pdo);

// Processar requisição de clima (se houver)
if (isset($_GET['get_clima'])) {
    require_once 'api/api_clima.php';
    processarRequisicaoClima($cidadeController);
    exit;
}

// Limpar mensagem da sessão após exibir
$import_message = $_SESSION['import_message'] ?? '';
unset($_SESSION['import_message']);

// Manipulação de exclusões via GET
if (isset($_GET['delete_pais'])) {
    $id = validateId($_GET['delete_pais']);
    if ($id) {
        $paisController->excluir($id);
    }
    redirectWithMessage('País excluído com sucesso!', 'success');
}

if (isset($_GET['delete_cidade'])) {
    $id = validateId($_GET['delete_cidade']);
    if ($id) {
        $cidadeController->excluir($id);
    }
    redirectWithMessage('Cidade excluída com sucesso!', 'success');
}

// Processar busca de dados do país via API
if (isset($_GET['buscar_pais_api'])) {
    $nomePais = trim($_GET['nome_pais'] ?? '');
    
    if (!empty($nomePais)) {
        require_once 'api/api_paises.php';
        $resultado = buscarPaisPorNome($nomePais);
        
        header('Content-Type: application/json');
        echo json_encode($resultado);
        exit;
    }
    
    echo json_encode(['success' => false, 'message' => 'Nome do país não fornecido']);
    exit;
}

// Manipulação de POSTs
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';

    switch ($action) {
        case 'insert_pais':
            $dados = processPaisFormData($_POST);
            
            if (validatePaisData($dados)) {
                $paisController->criar($dados);
                redirectWithMessage('País adicionado com sucesso!', 'success');
            } else {
                redirectWithMessage('Dados do país inválidos!', 'error');
            }
            break;

        case 'update_pais':
            $id = validateId($_POST['id_pais']);
            $dados = processPaisFormData($_POST);
            
            if ($id && validatePaisData($dados)) {
                $paisController->atualizar($id, $dados);
                redirectWithMessage('País atualizado com sucesso!', 'success');
            } else {
                redirectWithMessage('Dados do país inválidos!', 'error');
            }
            break;

        case 'insert_cidade':
            $dados = processCidadeFormData($_POST);
            
            if (validateCidadeData($dados)) {
                $cidadeController->criar($dados);
                redirectWithMessage('Cidade adicionada com sucesso!', 'success');
            } else {
                redirectWithMessage('Dados da cidade inválidos!', 'error');
            }
            break;

        case 'update_cidade':
            $id = validateId($_POST['id_cidade']);
            $dados = processCidadeFormData($_POST);
            
            if ($id && validateCidadeData($dados)) {
                $cidadeController->atualizar($id, $dados);
                redirectWithMessage('Cidade atualizada com sucesso!', 'success');
            } else {
                redirectWithMessage('Dados da cidade inválidos!', 'error');
            }
            break;

        case 'import_paises_api':
            $resultado = $paisController->importardapi();
            $_SESSION['import_message'] = $resultado['message'];
        break;
    }

    // Redirecionamento para evitar reenvio do formulário
    header("Location: index.php");
    exit;
}

// Obter dados para exibição
$paises = $paisController->listar();
$cidades = $cidadeController->listar();
$totalPaises = $paisController->contarTotal();
$totalCidades = $cidadeController->contarTotal();

// Calcular estatísticas
$estatisticas = getCidadeStatistics($cidades);
$cidadeMaisPopulosa = $estatisticas['cidade_mais_populosa'];
$maiorPopulacao = $estatisticas['maior_populacao'];
?>