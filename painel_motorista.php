<?php
session_start();

if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit;
}

$user =$_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ZARPP - Painel do Motorista</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
</head>
<body>

    <header>
        <div class="logo">
            <a href="index.php?manter_sessao=1">
                <img src="https://i.imgur.com/EiliGC7.png" alt="ZARPP Mobilidade">
            </a>
        </div>
        <nav>
            <a href="index.php?manter_sessao=1" class="btn-secondary">Ir para Home</a>
            <span style="color:#94a3b8; font-size:14px;">Olá, <?= $user['nome'] ?></span>
            <a href="logout.php" class="btn-danger">Sair</a>
        </nav>
    </header>

    <div class="dashboard">
        <h2>Radar de Chamadas ZARPP</h2>
        <p style="color: #94a3b8; margin-bottom:20px;">Buscando corridas disponíveis na sua região...</p>

        <div class="driver-grid">
            <div class="calls-list">
                <h3 style="color:#fff; margin-bottom:15px;">Chamadas Disponíveis</h3>
                <div id="listaCorridas">
                    <p style="color: #64748b;">Nenhuma corrida pendente no momento...</p>
                </div>
            </div>

            <div class="map-container" style="height: 520px; border-radius: 16px; overflow: hidden; border:1px solid #334155;">
                <div id="mapDriver" style="width: 100%; height: 100%;"></div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var mapDriver = L.map('mapDriver').setView([-21.7946, -48.1766], 13);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png').addTo(mapDriver);

        var layerGroup = L.layerGroup().addTo(mapDriver);

        function carregarCorridas() {
            fetch('corridas.json?v=' + new Date().getTime())
            .then(res => res.json())
            .then(corridas => {
                var html = '';
                if (!corridas || corridas.length === 0) {
                    html = '<p style="color: #64748b;">Nenhuma corrida pendente no momento...</p>';
                } else {
                    corridas.forEach(c => {
                        var ganhoCondutor = c.categoria === 'moto' ? (c.distancia * 1.70) + 1.00 : (c.distancia * 2.40) + 1.00;
                        var ehAceita = c.status === 'ACEITA';

                        html += `
                            <div class="call-card" style="${ehAceita ? 'border-left-color: #22c55e;' : ''}">
                                <div style="display:flex; justify-content:space-between; align-items:center;">
                                    <h4 style="color:#38bdf8; margin-bottom:8px;">${c.categoria.toUpperCase()} - ${c.distancia} KM</h4>
                                    <span style="font-size:11px; padding:3px 8px; border-radius:12px; font-weight:bold; background:${ehAceita ? '#166534; color:#4ade80;' : '#854d0e; color:#fde047;'}">${c.status}</span>
                                </div>
                                <p style="font-size:13px; color:#cbd5e1; margin-bottom:4px;"><strong>🟢 Partida:</strong> ${c.origem}</p>
                                <p style="font-size:13px; color:#cbd5e1; margin-bottom:4px;"><strong>🔴 Destino:</strong> ${c.destino}</p>
                                <p style="font-size:13px; color:#cbd5e1; margin-bottom:4px;"><strong>💰 Valor Passageiro:</strong> R$ ${parseFloat(c.valorTotal).toFixed(2).replace('.', ',')}</p>
                                <p style="color:#22c55e; font-weight:bold; font-size:14px; margin-top:6px;">👉 Seu Ganho Estimado: R$ ${ganhoCondutor.toFixed(2).replace('.', ',')}</p>

                                <div style="display:flex; gap:10px; margin-top:10px;">
                                    ${!ehAceita ? `
                                        <button class="btn-submit" style="background-color:#22c55e; flex:1;" onclick="aceitarCorrida('${c.id}', ${c.latOrigem},${c.lngOrigem}, ${c.latDestino},${c.lngDestino})">
                                            ✅ Aceitar Corrida
                                        </button>
                                    ` : `
                                        <button class="btn-submit" style="background-color:#0284c7; flex:1;" onclick="desenharNoMapa(${c.latOrigem},${c.lngOrigem}, ${c.latDestino},${c.lngDestino})">
                                            📍 Ver Rota no Mapa
                                        </button>
                                    `}
                                </div>
                            </div>
                        `;
                    });
                }
                document.getElementById('listaCorridas').innerHTML = html;
            });
        }

        function aceitarCorrida(id, latO, lngO, latD, lngD) {
            var dados = new FormData();
            dados.append('id', id);
            dados.append('status', 'ACEITA');

            fetch('atualizar_status.php', { method: 'POST', body: dados })
            .then(res => res.json())
            .then(res => {
                if (res.sucesso) {
                    alert('🎉 Corrida aceita com sucesso!');
                    carregarCorridas();
                    desenharNoMapa(latO, lngO, latD, lngD);
                }
            });
        }

        function desenharNoMapa(latO, lngO, latD, lngD) {
            layerGroup.clearLayers();

            L.marker([latO, lngO]).addTo(layerGroup).bindPopup('🟢 Partida (Embarque)').openPopup();
            L.marker([latD, lngD]).addTo(layerGroup).bindPopup('🔴 Destino (Desembarque)');

            var poly = L.polyline([[latO, lngO], [latD, lngD]], {color: '#ff3b30', weight: 5}).addTo(layerGroup);
            mapDriver.fitBounds(poly.getBounds(), {padding: [50, 50]});
        }

        setInterval(carregarCorridas, 3000);
        carregarCorridas();
    </script>
</body>
</html>