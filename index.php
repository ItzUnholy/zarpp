<?php
session_start();
if (!isset($_GET['manter_sessao'])) {
    session_unset();
    session_destroy();
    session_start();
}
$abrirCadastroAuto = isset($_GET['abrirCadastro']) ? true : false;
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZARPP - Mobilidade Urbana</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>

    <header>
        <div class="logo">
            <a href="index.php">
                <img src="https://i.imgur.com/EiliGC7.png" alt="ZARPP Mobilidade">
            </a>
        </div>
        <nav>
            <?php if (isset($_SESSION['usuario'])): ?>
                <a href="painel_motorista.php" class="btn-secondary">Painel do Motorista</a>
                <a href="logout.php" class="btn-danger">Sair</a>
            <?php else: ?>
                <button onclick="abrirCadastroMotorista()" class="btn-secondary" style="border: 1px solid #ff3b30; color: #ff3b30 !important; cursor: pointer;">Seja um Motorista</button>
                <a href="login.php" class="btn-secondary">Login Parceiros</a>
            <?php endif; ?>
        </nav>
    </header>

    <div class="uber-main-container">
        <!-- Lado Esquerdo: Formulário Solicite uma viagem -->
        <div class="uber-left">
            <h1>Solicite uma viagem</h1>

            <div class="time-selector">
                🕒 Agora ▼
            </div>

            <form id="formViagem" onsubmit="abrirModalPrecos(event)">
                <div class="trip-form-box">
                    <div class="input-row">
                        <div class="dot-icon"></div>
                        <input type="text" id="origem" placeholder="Informe o local de partida (Ex: Av Brasil)" required autocomplete="off" oninput="buscarEnderecos(this.value, 'listaOrigem')">
                        <div id="listaOrigem" class="autocomplete-suggestions"></div>
                    </div>

                    <div class="input-row destination">
                        <div class="dot-icon"></div>
                        <input type="text" id="destino" placeholder="Informe o destino (Ex: Centro, São Carlos...)" required autocomplete="off" oninput="buscarEnderecos(this.value, 'listaDestino')">
                        <div id="listaDestino" class="autocomplete-suggestions"></div>
                    </div>
                </div>

                <button type="submit" class="btn-prices">Ver preços</button>
            </form>
        </div>

        <!-- Lado Direito: Por que escolher o ZARPP -->
        <div class="uber-right">
            <h3>Por que escolher o ZARPP?</h3>
            <div class="innovation-stack">
                <div class="innovation-card">
                    <div class="icon-box">🛡️</div>
                    <div>
                        <h4>Tarifas Justas Sem Dinâmicos</h4>
                        <p>Preço fixo por quilômetro transparente. Chega de pagar caro em dias de chuva ou horários de pico.</p>
                    </div>
                </div>

                <div class="innovation-card">
                    <div class="icon-box">🤝</div>
                    <div>
                        <h4>Comunidade e Ponto de Apoio</h4>
                        <p>O lucro da plataforma é reinvestido na frota, garantindo estrutura física e benefícios para os motoristas.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Popup Profissional de Preços -->
    <div id="modalPrecos" class="modal-overlay">
        <div class="modal-content-pro">
            <div class="modal-header-pro">
                <h3>Escolha sua viagem</h3>
                <span style="background: rgba(56, 189, 248, 0.1); color: #38bdf8; font-size: 12px; font-weight: 700; padding: 4px 10px; border-radius: 20px; border: 1px solid rgba(56, 189, 248, 0.3);" id="badgeDistancia">0 KM</span>
            </div>

            <div class="route-summary">
                <p>🟢 <strong id="lblOrigemSummary">Origem</strong></p>
                <p>🔴 <strong id="lblDestinoSummary">Destino</strong></p>
            </div>

            <div class="option-card-pro selected" id="optMoto" onclick="escolherCat('moto')">
                <div class="option-left-pro">
                    <div class="option-icon-pro">🛵</div>
                    <div>
                        <div class="option-title-pro">ZARPP Moto</div>
                        <div class="option-sub-pro">Rápido e econômico • R$ 1,50/km</div>
                    </div>
                </div>
                <div class="option-price-pro" id="valMoto">R$ 0,00</div>
            </div>

            <div class="option-card-pro" id="optCarro" onclick="escolherCat('carro')">
                <div class="option-left-pro">
                    <div class="option-icon-pro">🚗</div>
                    <div>
                        <div class="option-title-pro">ZARPP Carro</div>
                        <div class="option-sub-pro">Mais conforto • R$ 2,20/km</div>
                    </div>
                </div>
                <div class="option-price-pro" id="valCarro">R$ 0,00</div>
            </div>

            <button class="btn-confirm-pro" onclick="confirmarSolicitacao()">Solicitar ZARPP</button>
            <button class="btn-cancel-pro" onclick="fecharModal()">Voltar / Alterar Endereços</button>
        </div>
    </div>

    <!-- Modal de Cadastro de Motorista -->
    <div id="modalCadastroMotorista" class="modal-cad-motorista">
        <div class="cad-card-pro">
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
                <h3 style="color:#fff; font-size:22px; font-weight:800;">Cadastro de Motorista</h3>
                <button onclick="fecharCadastroMotorista()" style="background:transparent; border:none; color:#94a3b8; font-size:20px; cursor:pointer;">✕</button>
            </div>

            <form id="formCadDriver">
                <div class="form-group">
                    <label>Nome Completo</label>
                    <input type="text" id="cad_nome" placeholder="Digite seu nome completo" required>
                </div>

                <div class="cad-grid-2">
                    <div class="form-group">
                        <label>CPF</label>
                        <input type="text" id="cad_cpf" placeholder="000.000.000-00" required>
                    </div>
                    <div class="form-group">
                        <label>E-mail</label>
                        <input type="email" id="cad_email" placeholder="seu@email.com" required>
                    </div>
                </div>

                <div class="cad-grid-2">
                    <div class="form-group">
                        <label>Senha de Acesso</label>
                        <input type="password" id="cad_senha" placeholder="••••••••" required>
                    </div>
                    <div class="form-group">
                        <label>Nº Registro CNH</label>
                        <input type="text" id="cad_cnh" placeholder="00000000000" required>
                    </div>
                </div>

                <div class="cad-grid-2">
                    <div class="form-group">
                        <label>Marca / Modelo do Veículo</label>
                        <input type="text" id="cad_veiculo" placeholder="Ex: Honda Fan 160 / Onix" required>
                    </div>
                    <div class="form-group">
                        <label>Placa do Veículo</label>
                        <input type="text" id="cad_placa" placeholder="ABC-1D23" required>
                    </div>
                </div>

                <button type="submit" class="btn-prices" style="margin-top:15px;">Finalizar Cadastro</button>
            </form>
        </div>
    </div>

    <script>
        var categoriaEscolhida = 'moto';
        var valorCalculadoMoto = 0;
        var valorCalculadoCarro = 0;
        var distanciaKmReal = 0;

        var pontoOrigem = { lat: -21.7946, lng: -48.1766 };
        var pontoDestino = { lat: -21.7580, lng: -48.1020 };
        var timerBusca = null;

        var baseEnderecosLocais = [
            { nome: "Araraquara - Centro (Praça Redentora)", lat: -21.7946, lng: -48.1766 },
            { nome: "Avenida Espanha - Centro, Araraquara", lat: -21.7910, lng: -48.1750 },
            { nome: "Avenida Bento de Abreu - Araraquara", lat: -21.7850, lng: -48.1790 },
            { nome: "Avenida Maurício Galli - Araraquara", lat: -21.7650, lng: -48.1750 },
            { nome: "Via Expressa - Araraquara", lat: -21.7890, lng: -48.1850 },
            { nome: "Shopping Jaraguá - Araraquara", lat: -21.8020, lng: -48.2010 },
            { nome: "Américo Brasiliense - Centro", lat: -21.7250, lng: -48.1020 },
            { nome: "São Carlos - Centro", lat: -22.0175, lng: -47.8908 }
        ];

        function buscarEnderecos(termo, idLista) {
            var lista = document.getElementById(idLista);
            clearTimeout(timerBusca);

            if (termo.trim().length < 1) {
                lista.style.display = 'none';
                return;
            }

            var termoLower = termo.toLowerCase();
            var html = '';

            var matches = baseEnderecosLocais.filter(item => item.nome.toLowerCase().includes(termoLower));
            matches.forEach(item => {
                html += `<div class="suggestion-item" onclick="selecionarEndereco('${idLista}', '${item.nome}', ${item.lat}, ${item.lng})">📍 ${item.nome}</div>`;
            });

            if (html !== '') {
                lista.innerHTML = html;
                lista.style.display = 'block';
            }

            if (termo.trim().length >= 2) {
                timerBusca = setTimeout(function() {
                    var query = termo.trim();
                    if (!query.toLowerCase().includes('sp') && !query.toLowerCase().includes('brasil')) {
                        query += ', Brasil';
                    }

                    fetch('https://nominatim.openstreetmap.org/search?format=json&q=' + encodeURIComponent(query) + '&countrycodes=br&limit=6')
                    .then(res => res.json())
                    .then(data => {
                        if (data && data.length > 0) {
                            data.forEach(item => {
                                var nomeEnd = item.display_name;
                                var lat = parseFloat(item.lat);
                                var lng = parseFloat(item.lon);

                                if (!matches.some(m => m.nome === nomeEnd)) {
                                    html += `<div class="suggestion-item" onclick="selecionarEndereco('${idLista}', '${nomeEnd.replace(/'/g, "\\'")}', ${lat}, ${lng})">📍 ${nomeEnd}</div>`;
                                }
                            });
                            lista.innerHTML = html;
                            lista.style.display = 'block';
                        }
                    })
                    .catch(() => {});
                }, 300);
            }
        }

        function selecionarEndereco(idLista, endereco, lat, lng) {
            if (idLista === 'listaOrigem') {
                document.getElementById('origem').value = endereco;
                pontoOrigem = { lat: lat, lng: lng };
                document.getElementById('listaOrigem').style.display = 'none';
            } else {
                document.getElementById('destino').value = endereco;
                pontoDestino = { lat: lat, lng: lng };
                document.getElementById('listaDestino').style.display = 'none';
            }
        }

        document.addEventListener('click', function(e) {
            if (!e.target.closest('.input-row')) {
                document.getElementById('listaOrigem').style.display = 'none';
                document.getElementById('listaDestino').style.display = 'none';
            }
        });

        function calcularDistanciaRealKM(lat1, lon1, lat2, lon2) {
            var R = 6371;
            var dLat = (lat2 - lat1) * Math.PI / 180;
            var dLon = (lon2 - lon1) * Math.PI / 180;
            var a = Math.sin(dLat / 2) * Math.sin(dLat / 2) +
                    Math.cos(lat1 * Math.PI / 180) * Math.cos(lat2 * Math.PI / 180) *
                    Math.sin(dLon / 2) * Math.sin(dLon / 2);
            var c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1 - a));
            return Math.max(1.2, (R * c) * 1.28);
        }

        function abrirModalPrecos(e) {
            e.preventDefault();
            document.getElementById('lblOrigemSummary').innerText = document.getElementById('origem').value;
            document.getElementById('lblDestinoSummary').innerText = document.getElementById('destino').value;

            distanciaKmReal = calcularDistanciaRealKM(pontoOrigem.lat, pontoOrigem.lng, pontoDestino.lat, pontoDestino.lng).toFixed(1);

            valorCalculadoMoto = (distanciaKmReal * 1.50) + 2.00;
            if (valorCalculadoMoto < 5.00) valorCalculadoMoto = 5.00;

            valorCalculadoCarro = (distanciaKmReal * 2.20) + 2.00;

            document.getElementById('badgeDistancia').innerText = distanciaKmReal + ' KM';
            document.getElementById('valMoto').innerText = 'R$ ' + valorCalculadoMoto.toFixed(2).replace('.', ',');
            document.getElementById('valCarro').innerText = 'R$ ' + valorCalculadoCarro.toFixed(2).replace('.', ',');

            document.getElementById('modalPrecos').style.display = 'flex';
        }

        function escolherCat(cat) {
            categoriaEscolhida = cat;
            if (cat === 'moto') {
                document.getElementById('optMoto').classList.add('selected');
                document.getElementById('optCarro').classList.remove('selected');
            } else {
                document.getElementById('optCarro').classList.add('selected');
                document.getElementById('optMoto').classList.remove('selected');
            }
        }

        function fecharModal() {
            document.getElementById('modalPrecos').style.display = 'none';
        }

        function confirmarSolicitacao() {
            var origem = document.getElementById('origem').value;
            var destino = document.getElementById('destino').value;
            var valorFinal = categoriaEscolhida === 'moto' ? valorCalculadoMoto : valorCalculadoCarro;

            var dados = new FormData();
            dados.append('origem', origem);
            dados.append('destino', destino);
            dados.append('categoria', categoriaEscolhida);
            dados.append('distancia', distanciaKmReal);
            dados.append('valorTotal', valorFinal.toFixed(2));
            dados.append('latOrigem', pontoOrigem.lat);
            dados.append('lngOrigem', pontoOrigem.lng);
            dados.append('latDestino', pontoDestino.lat);
            dados.append('lngDestino', pontoDestino.lng);

            fetch('salvar_corrida.php', { method: 'POST', body: dados })
            .then(res => res.json())
            .then(res => {
                if (res.sucesso) {
                    alert('🚀 Viagem solicitada com sucesso! O pedido foi enviado para os motoristas no radar.');
                    fecharModal();
                    document.getElementById('formViagem').reset();
                }
            });
        }

        function abrirCadastroMotorista() {
            document.getElementById('modalCadastroMotorista').style.display = 'flex';
        }

        function fecharCadastroMotorista() {
            document.getElementById('modalCadastroMotorista').style.display = 'none';
        }

        document.getElementById('formCadDriver').addEventListener('submit', function(e) {
            e.preventDefault();

            var dados = new FormData();
            dados.append('nome', document.getElementById('cad_nome').value);
            dados.append('cpf', document.getElementById('cad_cpf').value);
            dados.append('email', document.getElementById('cad_email').value);
            dados.append('senha', document.getElementById('cad_senha').value);
            dados.append('cnh', document.getElementById('cad_cnh').value);
            dados.append('veiculo', document.getElementById('cad_veiculo').value);
            dados.append('placa', document.getElementById('cad_placa').value);

            fetch('cadastrar_motorista.php', { method: 'POST', body: dados })
            .then(res => res.json())
            .then(res => {
                alert(res.mensagem);
                if (res.sucesso) {
                    fecharCadastroMotorista();
                    window.location.href = 'login.php';
                }
            });
        });

        <?php if ($abrirCadastroAuto): ?>
            abrirCadastroMotorista();
        <?php endif; ?>
    </script>
</body>
</html>