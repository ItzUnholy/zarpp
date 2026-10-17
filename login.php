<?php
session_start();

$erro = '';

if (isset($_SESSION['usuario'])) {
    header("Location: painel_motorista.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $senha = trim($_POST['senha']);

    if ($email == 'motorista@zarpp.com' && $senha == '123456') {
        $_SESSION['usuario'] = [
            'nome' => 'Carlos Silva (Motorista)',
            'email' => $email,
            'veiculo' => 'Honda CG 160 / Onix',
            'status' => 'Aprovado'
        ];
        header("Location: painel_motorista.php");
        exit;
    }

    $arquivo = 'motoristas.json';
    if (file_exists($arquivo)) {
        $motoristas = json_decode(file_get_contents($arquivo), true) ?: [];
        foreach ($motoristas as $m) {
            if ($m['email'] === $email && $m['senha'] === $senha) {
                $_SESSION['usuario'] = [
                    'nome' => $m['nome'],
                    'email' => $m['email'],
                    'veiculo' => $m['veiculo'] . ' (' . $m['placa'] . ')',
                    'status' => $m['status']
                ];
                header("Location: painel_motorista.php");
                exit;
            }
        }
    }

    $erro = "E-mail ou senha incorretos!";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZARPP - Portal do Motorista</title>
    <link rel="stylesheet" href="style.css">
    <style>
        .login-hero {
            min-height: calc(100vh - 80px);
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 40px 20px;
            background: radial-gradient(circle at center, #1e293b 0%, #0f172a 100%);
        }

        .login-card-innovative {
            background: rgba(30, 41, 59, 0.7);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            padding: 40px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.7);
        }

        .badge-driver {
            display: inline-block;
            background: rgba(255, 59, 48, 0.15);
            color: #ff3b30;
            border: 1px solid rgba(255, 59, 48, 0.3);
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
            margin-bottom: 15px;
        }

        .login-card-innovative h2 {
            font-size: 28px;
            font-weight: 800;
            color: #ffffff;
            margin-bottom: 8px;
        }

        .login-card-innovative p {
            color: #94a3b8;
            font-size: 14px;
            margin-bottom: 25px;
        }

        .input-pro {
            width: 100%;
            padding: 14px 16px;
            border-radius: 10px;
            border: 1px solid #334155;
            background-color: #0f172a;
            color: #fff;
            font-size: 15px;
            outline: none;
            transition: 0.3s;
        }

        .input-pro:focus {
            border-color: #ff3b30;
            box-shadow: 0 0 0 2px rgba(255, 59, 48, 0.2);
        }

        .btn-innovative {
            width: 100%;
            padding: 16px;
            background: #ff3b30;
            color: #ffffff;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 800;
            cursor: pointer;
            transition: 0.3s;
            margin-top: 10px;
        }

        .btn-innovative:hover {
            background: #e02d22;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>

    <header>
        <div class="logo">
            <a href="index.php">
                <img src="https://i.imgur.com/EiliGC7.png" alt="ZARPP Mobilidade">
            </a>
        </div>
        <nav>
            <a href="index.php" class="btn-secondary">Voltar para Home</a>
        </nav>
    </header>

    <div class="login-hero">
        <div class="login-card-innovative">
            <span class="badge-driver">⚡ Portal do Condutor</span>
            <h2>Seja bem-vindo</h2>
            <p>Acesse o radar de corridas em tempo real e gerencie seus ganhos diários.</p>

            <?php if (!empty($erro)): ?>
                <div style="background-color: rgba(127, 29, 29, 0.8); color: #fca5a5; padding: 12px; border-radius: 8px; margin-bottom: 20px; font-size: 14px; text-align: center; border: 1px solid #ef4444;"><?= $erro ?></div>
            <?php endif; ?>

            <form method="POST" action="login.php">
                <div class="form-group">
                    <label for="email">E-mail do Condutor</label>
                    <input type="email" name="email" id="email" class="input-pro" placeholder="seu@email.com" required value="motorista@zarpp.com">
                </div>

                <div class="form-group">
                    <label for="senha">Senha de Acesso</label>
                    <input type="password" name="senha" id="senha" class="input-pro" placeholder="••••••••" required value="123456">
                </div>

                <button type="submit" class="btn-innovative">Acessar Radar ZARPP</button>
            </form>

            <div style="text-align: center; margin-top: 25px; border-top: 1px solid #334155; padding-top: 20px;">
                <p style="font-size: 13px; color: #94a3b8; margin-bottom: 0;">Ainda não é parceiro? <a href="index.php?abrirCadastro=1" style="color: #38bdf8; text-decoration: none; font-weight: 700;">Cadastre-se na Home</a></p>
            </div>
        </div>
    </div>

</body>
</html>