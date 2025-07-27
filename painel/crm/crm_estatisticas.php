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
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM - Estatísticas e Relatórios</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="../css/health_theme.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        /* Reset e Base */
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: var(--health-bg-primary);
            color: var(--health-text-primary);
            line-height: 1.6;
        }

        /* Container Principal */
        .health-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: var(--space-6);
        }

        /* Header */
        .health-header {
            background: linear-gradient(135deg, var(--health-primary), var(--health-primary-dark));
            color: white;
            padding: var(--space-6);
            border-radius: var(--health-radius);
            margin-bottom: var(--space-6);
            box-shadow: var(--health-shadow-lg);
        }

        .health-header h1 {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: var(--space-2);
            display: flex;
            align-items: center;
            gap: var(--space-3);
        }

        .health-header p {
            opacity: 0.9;
            font-size: 1.1rem;
        }

        /* Cards */
        .health-card {
            background: var(--health-bg-card);
            border-radius: var(--health-radius);
            box-shadow: var(--health-shadow);
            padding: var(--space-6);
            margin-bottom: var(--space-6);
            border: 1px solid #e2e8f0;
        }

        .health-card-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--health-text-primary);
            margin-bottom: var(--space-4);
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }

        /* Estatísticas */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: var(--space-4);
            margin-bottom: var(--space-6);
        }

        .stat-card {
            background: var(--health-bg-card);
            padding: var(--space-5);
            border-radius: var(--health-radius);
            box-shadow: var(--health-shadow);
            text-align: center;
            border-left: 4px solid var(--health-primary);
            transition: transform 0.2s ease;
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--health-shadow-lg);
        }

        .stat-card.total {
            border-left-color: var(--health-primary);
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
        }

        .stat-card.novo {
            border-left-color: var(--stage-novo);
            background: linear-gradient(135deg, #f9fafb, #f3f4f6);
        }

        .stat-card.contato {
            border-left-color: var(--stage-contato);
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
        }

        .stat-card.negociacao {
            border-left-color: var(--stage-negociacao);
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
        }

        .stat-card.fechado {
            border-left-color: var(--stage-fechado);
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        }

        .stat-card.perdido {
            border-left-color: var(--stage-perdido);
            background: linear-gradient(135deg, #fef2f2, #fecaca);
        }

        .stat-card.conversao {
            border-left-color: var(--health-success);
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        }

        .stat-icon {
            font-size: 2rem;
            margin-bottom: var(--space-3);
            opacity: 0.8;
        }

        .stat-number {
            font-size: 2.5rem;
            font-weight: 700;
            margin-bottom: var(--space-2);
        }

        .stat-card.total .stat-number { color: var(--health-primary); }
        .stat-card.novo .stat-number { color: var(--stage-novo); }
        .stat-card.contato .stat-number { color: var(--stage-contato); }
        .stat-card.negociacao .stat-number { color: var(--stage-negociacao); }
        .stat-card.fechado .stat-number { color: var(--stage-fechado); }
        .stat-card.perdido .stat-number { color: var(--stage-perdido); }
        .stat-card.conversao .stat-number { color: var(--health-success); }

        .stat-label {
            color: var(--health-text-secondary);
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Gráficos */
        .charts-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--space-6);
            margin-bottom: var(--space-6);
        }

        .chart-container {
            background: var(--health-bg-card);
            border-radius: var(--health-radius);
            box-shadow: var(--health-shadow);
            padding: var(--space-6);
            border: 1px solid #e2e8f0;
        }

        .chart-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--health-text-primary);
            margin-bottom: var(--space-4);
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .health-container {
                padding: var(--space-4);
            }
            
            .charts-grid {
                grid-template-columns: 1fr;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .health-header h1 {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
        }

        /* Animações */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .health-fade-in {
            animation: fadeIn 0.6s ease-out;
        }

        /* Customização dos gráficos */
        .chart-canvas {
            max-height: 400px;
        }
    </style>
</head>
<body>
    <div class="health-container">
        <!-- Header -->
        <div class="health-header health-fade-in">
            <h1>
                <i class="fas fa-chart-line"></i>
                CRM - Estatísticas e Relatórios
            </h1>
            <p>Acompanhe o desempenho do seu pipeline de vendas e métricas importantes</p>
        </div>

        <!-- Estatísticas Principais -->
        <div class="stats-grid health-fade-in">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-users"></i>
                </div>
                <div class="stat-number"><?= $total_contatos ?></div>
                <div class="stat-label">Total de Contatos</div>
            </div>

            <?php 
            $etapa_classes = [
                'Novo' => 'novo',
                'Em Contato' => 'contato', 
                'Negociação' => 'negociacao',
                'Fechado' => 'fechado',
                'Perdido' => 'perdido'
            ];
            
            $etapa_icons = [
                'Novo' => 'fas fa-user-plus',
                'Em Contato' => 'fas fa-phone',
                'Negociação' => 'fas fa-handshake',
                'Fechado' => 'fas fa-check-circle',
                'Perdido' => 'fas fa-times-circle'
            ];
            
            foreach ($etapas as $et): 
            ?>
                <div class="stat-card <?= $etapa_classes[$et] ?>">
                    <div class="stat-icon">
                        <i class="<?= $etapa_icons[$et] ?>"></i>
                    </div>
                    <div class="stat-number"><?= $totais[$et] ?? 0 ?></div>
                    <div class="stat-label"><?= $et ?></div>
                </div>
            <?php endforeach; ?>

            <div class="stat-card conversao">
                <div class="stat-icon">
                    <i class="fas fa-percentage"></i>
                </div>
                <div class="stat-number"><?= $taxa_conversao ?>%</div>
                <div class="stat-label">Taxa de Conversão</div>
            </div>
        </div>

        <!-- Gráfico de Mensagens -->
        <div class="health-card health-fade-in">
            <h3 class="health-card-title">
                <i class="fas fa-comments"></i>
                Mensagens nos últimos 7 dias
            </h3>
            <canvas id="graficoMensagens" class="chart-canvas"></canvas>
        </div>

        <!-- Gráficos de Análise -->
        <div class="charts-grid health-fade-in">
            <div class="chart-container">
                <h3 class="chart-title">
                    <i class="fas fa-chart-pie"></i>
                    Distribuição por Etapa
                </h3>
                <canvas id="graficoEtapas" class="chart-canvas"></canvas>
            </div>
            
            <div class="chart-container">
                <h3 class="chart-title">
                    <i class="fas fa-chart-bar"></i>
                    Novos Contatos - Últimos 7 Dias
                </h3>
                <canvas id="graficoPorDia" class="chart-canvas"></canvas>
            </div>
        </div>
    </div>

    <script>
        // Configuração global dos gráficos
        Chart.defaults.font.family = 'Inter, sans-serif';
        Chart.defaults.color = '#64748b';

        // Gráfico de Etapas (Doughnut)
        const ctx1 = document.getElementById('graficoEtapas').getContext('2d');
        const graficoEtapas = new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: <?= $labels ?>,
                datasets: [{
                    data: <?= $valores ?>,
                    backgroundColor: [
                        '#6b7280', // Novo
                        '#f59e0b', // Em Contato
                        '#3b82f6', // Negociação
                        '#10b981', // Fechado
                        '#ef4444'  // Perdido
                    ],
                    borderWidth: 0,
                    hoverOffset: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12,
                                weight: '500'
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        cornerRadius: 8,
                        displayColors: true
                    }
                },
                cutout: '60%'
            }
        });

        // Gráfico de Contatos por Dia (Bar)
        const ctx2 = document.getElementById('graficoPorDia').getContext('2d');
        const graficoDias = new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: <?= $labels_dias ?>,
                datasets: [{
                    label: 'Novos Contatos',
                    data: <?= $valores_dias ?>,
                    backgroundColor: 'rgba(37, 99, 235, 0.8)',
                    borderColor: '#2563eb',
                    borderWidth: 1,
                    borderRadius: 6,
                    borderSkipped: false
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Gráfico de Mensagens (Line)
        const ctx3 = document.getElementById('graficoMensagens').getContext('2d');
        const graficoMensagens = new Chart(ctx3, {
            type: 'line',
            data: {
                labels: <?= json_encode(array_map(function($data) { return date('d/m', strtotime($data)); }, array_keys($mensagensPorDia))) ?>,
                datasets: [{
                    label: 'Mensagens',
                    data: <?= json_encode(array_values($mensagensPorDia)) ?>,
                    borderColor: '#10b981',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    borderWidth: 3,
                    tension: 0.4,
                    fill: true,
                    pointBackgroundColor: '#10b981',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0, 0, 0, 0.8)',
                        titleColor: '#fff',
                        bodyColor: '#fff',
                        borderColor: '#e2e8f0',
                        borderWidth: 1,
                        cornerRadius: 8
                    }
                },
                scales: {
                    y: { 
                        beginAtZero: true,
                        grid: {
                            color: '#f1f5f9'
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            color: '#64748b',
                            font: {
                                size: 11
                            }
                        }
                    }
                }
            }
        });

        // Animações de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.health-fade-in');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                }, index * 100);
            });
        });
    </script>
</body>
</html>