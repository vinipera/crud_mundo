<?php

/**
 * Função para filtrar e sanitizar dados de entrada
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

/**
 * Função para validar ID
 */
function validateId($id) {
    return filter_var($id, FILTER_VALIDATE_INT);
}

/**
 * Função para formatar população
 */
function formatPopulation($population) {
    return number_format($population);
}

/**
 * Função para redirecionar com mensagem
 */
function redirectWithMessage($message, $type = 'info') {
    $_SESSION['flash_message'] = [
        'text' => $message,
        'type' => $type
    ];
    header("Location: index.php");
    exit;
}

/**
 * Função para exibir mensagens flash
 */
function displayFlashMessage() {
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message']['text'];
        $type = $_SESSION['flash_message']['type'];
        unset($_SESSION['flash_message']);
        
        $class = $type === 'error' ? 'error-message' : 'success-message';
        return "<div class='flash-message {$class}'>" . htmlspecialchars($message) . "</div>";
    }
    return '';
}

/**
 * Função para processar dados do formulário de país
 */
function processPaisFormData($postData) {
    return [
        'nome_pais' => trim($postData['nome_pais'] ?? ''),
        'continente' => $postData['continente'] ?? '',
        'populacao_pais' => filter_var($postData['populacao_pais'], FILTER_VALIDATE_INT),
        'capital' => trim($postData['capital'] ?? ''),
        'moeda' => trim($postData['moeda'] ?? ''),
        'sigla' => trim($postData['sigla'] ?? ''),
        'idioma' => trim($postData['idioma'] ?? '')
    ];
}

/**
 * Função para processar dados do formulário de cidade
 */
function processCidadeFormData($postData) {
    return [
        'nome_cidade' => trim($postData['nome_cidade'] ?? ''),
        'populacao_cidade' => filter_var($postData['populacao_cidade'], FILTER_VALIDATE_INT),
        'id_pais' => filter_var($postData['id_pais'], FILTER_VALIDATE_INT)
    ];
}

/**
 * Função para validar dados do país
 */
function validatePaisData($data) {
    return !empty($data['nome_pais']) && 
           !empty($data['continente']) && 
           $data['populacao_pais'] !== false;
}

/**
 * Função para validar dados da cidade
 */
function validateCidadeData($data) {
    return !empty($data['nome_cidade']) && 
           $data['populacao_cidade'] !== false && 
           $data['id_pais'];
}

/**
 * Função para obter estatísticas das cidades
 */
function getCidadeStatistics($cidades) {
    $cidadeMaisPopulosa = null;
    $maiorPopulacao = 0;
    
    foreach ($cidades as $cidade) {
        if ($cidade['populacao_cidade'] > $maiorPopulacao) {
            $maiorPopulacao = $cidade['populacao_cidade'];
            $cidadeMaisPopulosa = $cidade;
        }
    }
    
    return [
        'cidade_mais_populosa' => $cidadeMaisPopulosa,
        'maior_populacao' => $maiorPopulacao
    ];
}
?>
