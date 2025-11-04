<?php

function sanitizeInput($data) {
    // limpa e protege dados de entrada contra ataques
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(trim($data), ENT_QUOTES, 'UTF-8');
}

function validateId($id) {
    // valida se id é um número inteiro válido
    return filter_var($id, FILTER_VALIDATE_INT);
}

function formatPopulation($population) {
    // formata número de população com separadores
    return number_format($population);
}

function redirectWithMessage($message, $type = 'info') {
    // redireciona página com mensagem temporária
    $_SESSION['flash_message'] = [
        'text' => $message,
        'type' => $type
    ];
    header("Location: index.php");
    exit;
}

function displayFlashMessage() {
    // exibe mensagem flash e remove da sessão
    if (isset($_SESSION['flash_message'])) {
        $message = $_SESSION['flash_message']['text'];
        $type = $_SESSION['flash_message']['type'];
        unset($_SESSION['flash_message']);
        
        $class = $type === 'error' ? 'error-message' : 'success-message';
        return "<div class='flash-message {$class}'>" . htmlspecialchars($message) . "</div>";
    }
    return '';
}

function processPaisFormData($postData) {
    // processa e formata dados do formulário de país
    return [
        'nome_pais' => trim($postData['nome_pais'] ?? ''),
        'continente' => $postData['continente'] ?? '',
        'populacao_pais' => filter_var($postData['populacao_pais'], FILTER_VALIDATE_INT),
        'capital' => trim($postData['capital'] ?? ''),
        'moeda' => trim($postData['moeda'] ?? ''),
        'sigla' => trim($postData['sigla'] ?? ''),
        'idioma' => trim($postData['idioma'] ?? ''),
        'bandeira' => trim($postData['bandeira'] ?? '')
    ];
}

function processCidadeFormData($postData) {
    // processa e formata dados do formulário de cidade
    return [
        'nome_cidade' => trim($postData['nome_cidade'] ?? ''),
        'populacao_cidade' => filter_var($postData['populacao_cidade'], FILTER_VALIDATE_INT),
        'id_pais' => filter_var($postData['id_pais'], FILTER_VALIDATE_INT)
    ];
}

function validatePaisData($data) {
    // valida se dados do país estão completos
    return !empty($data['nome_pais']) && 
           !empty($data['continente']) && 
           $data['populacao_pais'] !== false;
}

function validateCidadeData($data) {
    // valida se dados da cidade estão completos
    return !empty($data['nome_cidade']) && 
           $data['populacao_cidade'] !== false && 
           $data['id_pais'];
}

function getCidadeStatistics($cidades) {
    // calcula estatísticas das cidades (mais populosa)
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