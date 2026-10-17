<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $origem = $_POST['origem'] ?? 'Partida não informada';
    $destino = $_POST['destino'] ?? 'Destino não informado';
    $categoria = $_POST['categoria'] ?? 'moto';
    $distancia = floatval($_POST['distancia'] ?? 0);
    $valorTotal = floatval($_POST['valorTotal'] ?? 0);
    
    $latOrigem = floatval($_POST['latOrigem'] ?? -21.7946);
    $lngOrigem = floatval($_POST['lngOrigem'] ?? -48.1766);
    $latDestino = floatval($_POST['latDestino'] ?? -21.7580);
    $lngDestino = floatval($_POST['lngDestino'] ?? -48.1020);

    $novaCorrida = [
        'id' => uniqid(),
        'origem' => $origem,
        'destino' => $destino,
        'categoria' => $categoria,
        'distancia' => $distancia,
        'valorTotal' => $valorTotal,
        'latOrigem' => $latOrigem,
        'lngOrigem' => $lngOrigem,
        'latDestino' => $latDestino,
        'lngDestino' => $lngDestino,
        'status' => 'PENDENTE',
        'data' => date('H:i:s')
    ];

    $arquivo = 'corridas.json';
    $corridas = [];

    if (file_exists($arquivo)) {
        $corridas = json_decode(file_get_contents($arquivo), true) ?: [];
    }

    array_unshift($corridas, $novaCorrida);
    file_put_contents($arquivo, json_encode($corridas, JSON_PRETTY_PRINT));

    echo json_encode(['sucesso' => true, 'mensagem' => 'Corrida solicitada com sucesso!']);
    exit;
}

echo json_encode(['sucesso' => false, 'mensagem' => 'Requisição inválida']);