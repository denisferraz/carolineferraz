<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$token_emp = $_SESSION['token_emp'];
$pdo = $conexao;

// Buscar total por etapa
$etapas = ['Novo', 'Em Contato', 'Negociação', 'Fechado', 'Perdido'];
$totais_por_etapa = [];

foreach ($etapas as $etapa) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM contatos WHERE etapa = ? AND token_emp = ?");
    $stmt->execute([$etapa, $token_emp]);
    $totais_por_etapa[$etapa] = $stmt->fetchColumn();
}

// Buscar mensagens por dia (últimos 7 dias)
$mensagensPorDia = [];
for ($i = 6; $i >= 0; $i--) {
    $dia = date('Y-m-d', strtotime("-$i days"));
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM interacoes WHERE DATE(data) = ? AND token_emp = ?");
    $stmt->execute([$dia, $token_emp]);
    $mensagensPorDia[$dia] = $stmt->fetchColumn();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <title>📊 Dashboard Gerencial</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
  <style>
    body { background: #1e1e2f; color: #fff; }
    .card { background-color: #2b2b3d; color: #fff; border: none; }
  </style>
</head>
<body class="p-4">

<h3 class="mb-4">📊 Dashboard Gerencial</h3>

<div class="row mb-4">
  <?php foreach ($totais_por_etapa as $etapa => $total): ?>
    <div class="col-md-2">
      <div class="card text-center p-3 mb-3">
        <h6><?= $etapa ?></h6>
        <h4><?= $total ?></h4>
      </div>
    </div>
  <?php endforeach; ?>
</div>

<div class="card p-3 mb-4">
  <h5 class="mb-3">📈 Mensagens nos últimos 7 dias</h5>
  <canvas id="graficoMensagens" height="100"></canvas>
</div>

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
