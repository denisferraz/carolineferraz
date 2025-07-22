<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$etapas = ['Novo', 'Em Contato', 'Negociação', 'Fechado', 'Perdido'];

$token_emp = $_SESSION['token_emp'];

$totais = [];
foreach ($etapas as $etapa_nome) {
    $stmt = $conexao->prepare("SELECT COUNT(*) FROM contatos WHERE token_emp = :token AND etapa = :etapa");
    $stmt->execute(['token' => $token_emp, 'etapa' => $etapa_nome]);
    $totais[$etapa_nome] = $stmt->fetchColumn();
}

// Total geral
$stmt = $conexao->prepare("SELECT COUNT(*) FROM contatos WHERE token_emp = :token");
$stmt->execute(['token' => $token_emp]);
$total_contatos = $stmt->fetchColumn();

$fechados = $totais['Fechado'] ?? 0;
$ativos = $total_contatos - ($totais['Perdido'] ?? 0);
$taxa_conversao = $ativos > 0 ? round(($fechados / $ativos) * 100, 1) : 0;

$labels = json_encode(array_keys($totais));
$valores = json_encode(array_values($totais));

$stmt = $conexao->prepare("
    SELECT DATE(ultimo_contato) as dia, COUNT(*) as total 
    FROM contatos 
    WHERE token_emp = :token 
      AND ultimo_contato >= DATE_SUB(CURDATE(), INTERVAL 6 DAY)
    GROUP BY dia ORDER BY dia ASC
");
$stmt->execute(['token' => $token_emp]);
$dados_dia = $stmt->fetchAll();

$labels_dias = [];
$valores_dias = [];

foreach ($dados_dia as $linha) {
    $labels_dias[] = date('d/m', strtotime($linha['dia']));
    $valores_dias[] = (int)$linha['total'];
}

$labels_dias = json_encode($labels_dias);
$valores_dias = json_encode($valores_dias);

// Buscar mensagens por dia (últimos 7 dias)
$mensagensPorDia = [];
for ($i = 6; $i >= 0; $i--) {
    $dia = date('Y-m-d', strtotime("-$i days"));
    $stmt = $conexao->prepare("SELECT COUNT(*) FROM interacoes WHERE DATE(data) = ? AND token_emp = ?");
    $stmt->execute([$dia, $token_emp]);
    $mensagensPorDia[$dia] = $stmt->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>CRM - Painel de Contatos WhatsApp</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <!-- Bootstrap 5 (CDN) -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <!-- Estilo personalizado -->
  <style>
    body {
      background-color: #1e1e2f;
      color: #fff;
    }
    .kanban-col {
      background-color: #2a2a3b;
      border-radius: 10px;
      padding: 15px;
      min-height: 500px;
      max-height: 80vh;
      overflow-y: auto;
    }
    .contato-card {
      background-color: #343a40;
      padding: 10px;
      border-radius: 8px;
      margin-bottom: 10px;
      box-shadow: 0 0 5px rgba(255,255,255,0.05);
    }
    .contato-card strong {
      font-size: 16px;
    }
    .contato-card small {
      font-size: 13px;
      color: #aaa;
    }
    .etapa-title {
      font-weight: bold;
      font-size: 18px;
      margin-bottom: 10px;
      text-align: center;
    }
    .btn-ver {
      width: 100%;
      margin-top: 5px;
    }
    .scroll-horizontal {
      overflow-x: auto;
      white-space: nowrap;
    }
    .col-kanban {
      display: inline-block;
      vertical-align: top;
      width: 250px;
      margin-right: 10px;
    }
  </style>
</head>
<body>

<div class="container-fluid p-4">
  <h2 class="mb-4">📋 CRM - Estatisticas</h2>

<div class="row g-3 mb-4">
  <div class="col-md-3">
    <div class="card text-bg-dark shadow-sm">
      <div class="card-body">
        <h6 class="card-title">👥 Total de Contatos</h6>
        <h3><?= $total_contatos ?></h3>
      </div>
    </div>
  </div>

  <?php foreach ($etapas as $et): ?>
    <div class="col-md-3">
      <div class="card text-bg-secondary shadow-sm">
        <div class="card-body">
          <h6 class="card-title"><?= $et ?></h6>
          <h4><?= $totais[$et] ?? 0 ?></h4>
        </div>
      </div>
    </div>
  <?php endforeach; ?>

  <div class="col-md-3">
  <div class="card text-bg-success shadow-sm">
    <div class="card-body">
      <h6 class="card-title">🎯 Conversão</h6>
      <h4><?= $taxa_conversao ?>%</h4>
    </div>
  </div>
</div>
</div>

<div class="card p-3 mb-4">
  <h5 class="mb-3">📈 Mensagens nos últimos 7 dias</h5>
  <canvas id="graficoMensagens" height="100"></canvas>
</div>


<div class="row mb-4">
  <div class="col-md-6">
    <canvas id="graficoEtapas"></canvas>
  </div>
  <div class="col-md-6">
    <canvas id="graficoPorDia"></canvas>
  </div>
</div>

</div>

<script>
const ctx1 = document.getElementById('graficoEtapas').getContext('2d');
const graficoEtapas = new Chart(ctx1, {
    type: 'doughnut',
    data: {
        labels: <?= $labels ?>,
        datasets: [{
            data: <?= $valores ?>,
            backgroundColor: ['#0d6efd', '#ffc107', '#20c997', '#198754', '#dc3545'],
        }]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Distribuição por Etapa'
            }
        }
    }
});

const ctx2 = document.getElementById('graficoPorDia').getContext('2d');
const graficoDias = new Chart(ctx2, {
    type: 'bar',
    data: {
        labels: <?= $labels_dias ?>,
        datasets: [{
            label: 'Contatos por Dia',
            data: <?= $valores_dias ?>,
            backgroundColor: '#0dcaf0'
        }]
    },
    options: {
        plugins: {
            title: {
                display: true,
                text: 'Novos Contatos - Últimos 7 Dias'
            }
        },
        scales: {
            y: { beginAtZero: true }
        }
    }
});
</script>

<script>
const ctx = document.getElementById('graficoMensagens').getContext('2d');
const graficoMensagens = new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?= json_encode(array_keys($mensagensPorDia)) ?>,
        datasets: [{
            label: 'Mensagens',
            data: <?= json_encode(array_values($mensagensPorDia)) ?>,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            tension: 0.3,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            y: { beginAtZero: true },
            x: { ticks: { color: '#ccc' } }
        },
        plugins: {
            legend: { labels: { color: '#fff' } }
        }
    }
});
</script>

</body>
</html>
