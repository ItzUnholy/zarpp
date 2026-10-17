<?php
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $cpf = trim($_POST['cpf'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = trim($_POST['senha'] ?? '');
    $veiculo = trim($_POST['veiculo'] ?? '');
    $placa = trim($_POST['placa'] ?? '');
    $cnh = trim($_POST['cnh'] ?? '');

    if (empty($nome) || empty($email) || empty($senha) || empty($cnh)) {
        echo json_encode(['sucesso' => false, 'mensagem' => 'Preencha todos os campos obrigatórios!']);
        exit;
    }

    $arquivo = 'motoristas.json';
    $motoristas = [];

    if (file_exists($arquivo)) {
        $motoristas = json_decode(file_get_contents($arquivo), true) ?: [];
    }

    foreach ($motoristas as $m) {
        if ($m['email'] === $email) {
            echo json_encode(['sucesso' => false, 'mensagem' => 'Este e-mail já está cadastrado!']);
            exit;
        }
    }

    $novoMotorista = [
        'id' => uniqid(),
        'nome' => $nome,
        'cpf' => $cpf,
        'email' => $email,
        'senha' => $senha,
        'veiculo' => $veiculo,
        'placa' => strtoupper($placa),
        'cnh' => $cnh,
        'status' => 'Aprovado',
        'data_cadastro' => date('Y-m-d H:i:s')
    ];

    $motoristas[] = $novoMotorista;
    file_put_contents($arquivo, json_encode($motoristas, JSON_PRETTY_PRINT));

    echo json_encode(['sucesso' => true, 'mensagem' => 'Cadastro realizado com sucesso! Você já pode realizar o login.']);
    exit;
}

echo json_encode(['sucesso' => false, 'mensagem' => 'Requisição inválida.']);