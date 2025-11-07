<?php
define('OPENWEATHER_API_KEY', '06cb432fb3eed4d1136c5929460bd323');

function obterClimaPorCidade($cidadeNome, $paisNome, $apiKey) {
    // Busca do clima por cidade + país
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

function processarRequisicaoClima($cidadeController) {
    // Processa requisição de clima via AJAX
    if (isset($_GET['get_clima'])) {
        
        $idCidade = isset($_GET['id_cidade']) ? filter_var($_GET['id_cidade'], FILTER_VALIDATE_INT) : null;
        
        if ($idCidade) {
            $cidade = $cidadeController->buscarPorId($idCidade);
            
            if ($cidade) {
                $clima = obterClimaPorCidade($cidade['nome_cidade'], $cidade['nome_pais'], OPENWEATHER_API_KEY);
                
                if ($clima && isset($clima['main'])) {
                    header('Content-Type: application/json');
                    echo json_encode([
                        'success' => true,
                        'cidade' => $cidade['nome_cidade'],
                        'pais' => $cidade['nome_pais'],
                        'clima' => [
                            'temperatura' => round($clima['main']['temp']),
                            'descricao' => ucfirst($clima['weather'][0]['description']),
                            'umidade' => $clima['main']['humidity'],
                            'vento' => round($clima['wind']['speed'] * 3.6), // Converte m/s para km/h
                            'icone' => $clima['weather'][0]['icon'],
                            'sensacao' => round($clima['main']['feels_like']),
                            'pressao' => $clima['main']['pressure'],
                            'temp_min' => round($clima['main']['temp_min']),
                            'temp_max' => round($clima['main']['temp_max'])
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
}
?>