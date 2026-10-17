<?php
session_start();

// Trava de segurança: apenas admin logado acessa
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$user = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZARPP - Painel Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="logo">ZARPP<span>.ADMIN</span></div>
        <nav>
            <span style="color:#94a3b8;">Olá, <?= $user['nome'] ?></span>
            <a href="logout.php" class="btn-login" style="background-color: #475569;">Sair</a>
        </nav>
    </header>

    <div class="dashboard">
        <h2>Painel de Controle do Sistema</h2>
        <p style="color: #94a3b8; margin-top: 5px;">Visão geral da operação em Araraquara e Américo Brasiliense.</p>

        <div class="stat-grid">
            <div class="stat-card">
                <h4>Motoristas Ativos</h4>
                <p>35 Motos / 12 Carros</p>
            </div>
            <div class="stat-card" style="border-left-color: #38bdf8;">
                <h4>Corridas Hoje</h4>
                <p>148 pedidos</p>
            </div>
            <div class="stat-card" style="border-left-color: #22c55e;">
                <h4>Faturamento Plataforma (Dia)</h4>
                <p>R$ 493,00</p>
            </div>
            <div class="stat-card" style="border-left-color: #eab308;">
                <h4>Taxa Média de Aceitação</h4>
                <p>92%</p>
            </div>
        </div>
    </div>

</body>
</html>