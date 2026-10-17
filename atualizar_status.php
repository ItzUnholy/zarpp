<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id'] ?? '';
    $novoStatus = $_POST['status'] ?? 'ACEITA';

    $arquivo = 'corridas.json';
    if (file_exists($arquivo)) {
        $corridas = json_decode(file_get_contents($arquivo), true) ?: [];
        foreach ($corridas as &$c) {
            if ($c['id'] === $id) {
                $c['status'] = $novoStatus;
                break;
            }
        }
        file_put_contents($arquivo, json_encode($corridas, JSON_PRETTY_PRINT));
        echo json_encode(['sucesso' => true]);
        exit;
    }
}

echo json_encode(['sucesso' => false]);