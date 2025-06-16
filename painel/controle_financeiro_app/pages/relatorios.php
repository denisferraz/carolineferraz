<?php
require_once '../includes/config.php';

$pageTitle = 'Relatórios';
include '../includes/header.php';

try {
    $pdo = getConnection();
    
    // Período para análise (padrão: último ano)
    $dataInicio = $_GET['data_inicio'] ?? date('Y-01-01');
    $dataFim = $_GET['data_fim'] ?? date('Y-m-d');
    
    // Resumo geral
    $stmt = $pdo->prepare("
        SELECT 
            t.nome as tipo,
            SUM(l.valor) as total,
            COUNT(l.id) as quantidade
        FROM lancamentos l
        JOIN contas c ON l.conta_id = c.id
        JOIN tipos t ON c.tipo_id = t.id
        WHERE l.token_emp = ? AND l.data_lancamento BETWEEN ? AND ?
        GROUP BY t.id, t.nome
    ");
    $stmt->execute([$_SESSION['token_emp'], $dataInicio, $dataFim]);
    $resumoGeral = $stmt->fetchAll();
    
    $receitas = 0;
    $despesas = 0;
    foreach ($resumoGeral as $item) {
        if ($item['tipo'] == 'Receita') {
            $receitas = $item['total'];
        } else {
            $despesas = $item['total'];
        }
    }
    
    // Resumo por grupo
    $stmt = $pdo->prepare("
        SELECT 
            t.nome as tipo,
            g.nome as grupo,
            SUM(l.valor) as total,
            COUNT(l.id) as quantidade,
            AVG(l.valor) as media
        FROM lancamentos l
        JOIN contas c ON l.conta_id = c.id
        JOIN tipos t ON c.tipo_id = t.id
        JOIN grupos_contas g ON c.grupo_id = g.id
        WHERE l.token_emp = ? AND l.data_lancamento BETWEEN ? AND ?
        GROUP BY t.id, t.nome, g.id, g.nome
        ORDER BY t.nome, total DESC
    ");
    $stmt->execute([$_SESSION['token_emp'], $dataInicio, $dataFim]);
    $resumoPorGrupo = $stmt->fetchAll();
    
    // Evolução mensal
    $stmt = $pdo->prepare("
        SELECT 
            DATE_FORMAT(l.data_lancamento, '%Y-%m') as mes,
            t.nome as tipo,
            SUM(l.valor) as total
        FROM lancamentos l
        JOIN contas c ON l.conta_id = c.id
        JOIN tipos t ON c.tipo_id = t.id
        WHERE l.token_emp = ? AND  l.data_lancamento BETWEEN ? AND ?
        GROUP BY DATE_FORMAT(l.data_lancamento, '%Y-%m'), t.id, t.nome
        ORDER BY mes, t.nome
    ");
    $stmt->execute([$_SESSION['token_emp'], $dataInicio, $dataFim]);
    $evolucaoMensal = $stmt->fetchAll();
    
    // Maiores lançamentos
    $stmt = $pdo->prepare("
        SELECT 
            l.data_lancamento,
            l.descricao,
            l.valor,
            c.codigo,
            t.nome as tipo,
            g.nome as grupo
        FROM lancamentos l
        JOIN contas c ON l.conta_id = c.id
        JOIN tipos t ON c.tipo_id = t.id
        JOIN grupos_contas g ON c.grupo_id = g.id
        WHERE l.token_emp = ? AND  l.data_lancamento BETWEEN ? AND ?
        ORDER BY l.valor DESC
        LIMIT 10
    ");
    $stmt->execute([$_SESSION['token_emp'], $dataInicio, $dataFim]);
    $maioresLancamentos = $stmt->fetchAll();
    
} catch (Exception $e) {
    $error = "Erro ao carregar relatórios: " . $e->getMessage();
}
?>

<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Período de Análise</h3>
    </div>
    <form method="GET" class="form-row">
        <div class="form-group">
            <label class="form-label">Data Início</label>
            <input type="date" name="data_inicio" class="form-control" value="<?php echo $dataInicio; ?>">
        </div>
        <div class="form-group">
            <label class="form-label">Data Fim</label>
            <input type="date" name="data_fim" class="form-control" value="<?php echo $dataFim; ?>">
        </div>
        <div class="form-group d-flex align-center">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-chart-bar"></i> Gerar Relatório
            </button>
        </div>
    </form>
</div>

<!-- Resumo Geral -->
<div class="grid grid-3 mb-4">
    <div class="stats-card">
        <div class="stats-icon receita">
            <i class="fas fa-arrow-up"></i>
        </div>
        <div class="stats-value text-success"><?php echo formatMoney($receitas); ?></div>
        <div class="stats-label">Receitas no Período</div>
    </div>
    
    <div class="stats-card">
        <div class="stats-icon despesa">
            <i class="fas fa-arrow-down"></i>
        </div>
        <div class="stats-value text-danger"><?php echo formatMoney($despesas); ?></div>
        <div class="stats-label">Despesas no Período</div>
    </div>
    
    <div class="stats-card">
        <div class="stats-icon saldo">
            <i class="fas fa-balance-scale"></i>
        </div>
        <div class="stats-value <?php echo ($receitas - $despesas) >= 0 ? 'text-success' : 'text-danger'; ?>">
            <?php echo formatMoney($receitas - $despesas); ?>
        </div>
        <div class="stats-label">Resultado do Período</div>
    </div>
</div>

<div class="grid grid-2">
    <!-- Resumo por Grupo -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Resumo por Categoria</h3>
        </div>
        
        <?php if (!empty($resumoPorGrupo)): ?>
            <?php 
            $tipoAtual = '';
            foreach ($resumoPorGrupo as $item): 
                if ($tipoAtual != $item['tipo']):
                    if ($tipoAtual != '') echo '</div>';
                    $tipoAtual = $item['tipo'];
            ?>
                    <h4 class="mt-3 mb-2 <?php echo $item['tipo'] == 'Receita' ? 'text-success' : 'text-danger'; ?>">
                        <?php echo $item['tipo']; ?>s
                    </h4>
                    <div class="categoria-list">
            <?php endif; ?>
            
            <div class="categoria-item">
                <div class="d-flex justify-between align-center mb-1">
                    <strong><?php echo htmlspecialchars($item['grupo']); ?></strong>
                    <span class="<?php echo $item['tipo'] == 'Receita' ? 'text-success' : 'text-danger'; ?>">
                        <?php echo formatMoney($item['total']); ?>
                    </span>
                </div>
                <div class="d-flex justify-between text-muted">
                    <small><?php echo $item['quantidade']; ?> lançamento(s)</small>
                    <small>Média: <?php echo formatMoney($item['media']); ?></small>
                </div>
                <div class="progress-bar mt-1">
                    <div class="progress-fill <?php echo $item['tipo'] == 'Receita' ? 'bg-success' : 'bg-danger'; ?>" 
                         style="width: <?php echo ($item['total'] / max($receitas, $despesas)) * 100; ?>%"></div>
                </div>
            </div>
            
            <?php endforeach; ?>
            </div>
        <?php else: ?>
            <p class="text-muted text-center">Nenhum dado encontrado para o período selecionado.</p>
        <?php endif; ?>
    </div>
    
    <!-- Maiores Lançamentos -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Maiores Lançamentos</h3>
        </div>
        
        <?php if (!empty($maioresLancamentos)): ?>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Categoria</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($maioresLancamentos as $lancamento): ?>
                            <tr>
                                <td><?php echo formatDate($lancamento['data_lancamento']); ?></td>
                                <td><?php echo htmlspecialchars($lancamento['descricao']); ?></td>
                                <td>
                                    <span class="badge <?php echo $lancamento['tipo'] == 'Receita' && $lancamento['codigo'] != 'RS2' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo htmlspecialchars($lancamento['codigo']); ?>
                                    </span>
                                    <br><small><?php echo htmlspecialchars($lancamento['grupo']); ?></small>
                                </td>
                                <td class="<?php echo $lancamento['tipo'] == 'Receita' && $lancamento['codigo'] != 'RS2' ? 'text-success' : 'text-danger'; ?>">
                                    <?php echo formatMoney($lancamento['valor']); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p class="text-muted text-center">Nenhum lançamento encontrado.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Evolução Mensal -->
<?php if (!empty($evolucaoMensal)): ?>
<div class="card mt-4">
    <div class="card-header">
        <h3 class="card-title">Evolução Mensal</h3>
    </div>
    
    <div class="chart-container">
        <canvas id="chartEvolucao" width="400" height="200"></canvas>
    </div>
    
    <div class="table-container mt-3">
        <table class="table">
            <thead>
                <tr>
                    <th>Mês</th>
                    <th>Receitas</th>
                    <th>Despesas</th>
                    <th>Resultado</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $dadosMensais = [];
                foreach ($evolucaoMensal as $item) {
                    $mes = $item['mes'];
                    if (!isset($dadosMensais[$mes])) {
                        $dadosMensais[$mes] = ['receitas' => 0, 'despesas' => 0];
                    }
                    if ($item['tipo'] == 'Receita') {
                        $dadosMensais[$mes]['receitas'] = $item['total'];
                    } else {
                        $dadosMensais[$mes]['despesas'] = $item['total'];
                    }
                }
                
                foreach ($dadosMensais as $mes => $dados):
                    $resultado = $dados['receitas'] - $dados['despesas'];
                ?>
                    <tr>
                        <td><?php echo date('m/Y', strtotime($mes . '-01')); ?></td>
                        <td class="text-success"><?php echo formatMoney($dados['receitas']); ?></td>
                        <td class="text-danger"><?php echo formatMoney($dados['despesas']); ?></td>
                        <td class="<?php echo $resultado >= 0 ? 'text-success' : 'text-danger'; ?>">
                            <?php echo formatMoney($resultado); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>
<?php endif; ?>

<style>
.progress-bar {
    width: 100%;
    height: 4px;
    background-color: var(--border-color);
    border-radius: 2px;
    overflow: hidden;
}

.progress-fill {
    height: 100%;
    transition: width 0.3s ease;
}

.chart-container {
    padding: 2rem;
    text-align: center;
}

.categoria-item {
    padding: 1rem;
    border-bottom: 1px solid var(--border-color);
}

.categoria-item:last-child {
    border-bottom: none;
}

.form-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1rem;
    align-items: end;
    padding: 1.5rem;
}

@media (max-width: 768px) {
    .form-row {
        grid-template-columns: 1fr;
    }
}
</style>

<!-- JavaScript -->
<script src="../js/script.js"></script>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Gráfico de evolução mensal
<?php if (!empty($dadosMensais)): ?>
const ctx = document.getElementById('chartEvolucao').getContext('2d');
const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [<?php echo "'" . implode("','", array_map(function($mes) { return date('m/Y', strtotime($mes . '-01')); }, array_keys($dadosMensais))) . "'"; ?>],
        datasets: [{
            label: 'Receitas',
            data: [<?php echo implode(',', array_column($dadosMensais, 'receitas')); ?>],
            borderColor: 'var(--success-color)',
            backgroundColor: 'rgba(16, 185, 129, 0.1)',
            tension: 0.4
        }, {
            label: 'Despesas',
            data: [<?php echo implode(',', array_column($dadosMensais, 'despesas')); ?>],
            borderColor: 'var(--danger-color)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Evolução Mensal - Receitas vs Despesas'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function(value) {
                        return 'R$ ' + value.toLocaleString('pt-BR');
                    }
                }
            }
        }
    }
});
<?php endif; ?>
</script>

<?php include '../includes/footer.php'; ?>

