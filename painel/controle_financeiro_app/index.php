<?php
require_once 'includes/config_index.php';

$pageTitle = 'Dashboard';
include 'includes/header.php';

try {
    $pdo = getConnection();
    
    // Buscar resumo financeiro
    $stmt = $pdo->query("
        SELECT 
            t.nome as tipo,
            SUM(l.valor) as total
        FROM lancamentos l
        JOIN contas c ON l.conta_id = c.id
        JOIN tipos t ON c.tipo_id = t.id
        WHERE l.token_emp = '{$_SESSION['token_emp']}' 
        GROUP BY t.id, t.nome
    ");
    $resumo = $stmt->fetchAll();
    
    $receitas = 0;
    $despesas = 0;
    
    foreach ($resumo as $item) {
        if ($item['tipo'] == 'Receita') {
            $receitas = $item['total'];
        } else {
            $despesas = $item['total'];
        }
    }
    
    $saldo = $receitas - $despesas;
    
    // Buscar últimos lançamentos
    $stmt = $pdo->query("
        SELECT 
            l.id,
            l.data_lancamento,
            l.descricao,
            l.recorrente,
            l.valor,
            c.codigo,
            t.nome as tipo
        FROM lancamentos l
        JOIN contas c ON l.conta_id = c.id
        JOIN tipos t ON c.tipo_id = t.id
        WHERE l.token_emp = '{$_SESSION['token_emp']}' 
        ORDER BY l.data_lancamento DESC, l.id DESC
        LIMIT 10
    ");
    $ultimosLancamentos = $stmt->fetchAll();
    
    // Buscar resumo por grupo
    $stmt = $pdo->query("
        SELECT 
            t.nome as tipo,
            g.nome as grupo,
            SUM(l.valor) as total,
            COUNT(l.id) as quantidade
        FROM lancamentos l
        JOIN contas c ON l.conta_id = c.id
        JOIN tipos t ON c.tipo_id = t.id
        JOIN grupos_contas g ON c.grupo_id = g.id
        WHERE l.token_emp = '{$_SESSION['token_emp']}' 
        GROUP BY t.id, t.nome, g.id, g.nome
        ORDER BY t.nome, total DESC
    ");
    $resumoPorGrupo = $stmt->fetchAll();
    
} catch (Exception $e) {
    $error = "Erro ao carregar dados: " . $e->getMessage();
}
?>

<div class="grid grid-3 mb-4">
    <div class="stats-card">
        <div class="stats-icon receita">
            <i class="fas fa-arrow-up"></i>
        </div>
        <div class="stats-value text-success"><?php echo formatMoney($receitas); ?></div>
        <div class="stats-label">Total de Receitas</div>
    </div>
    
    <div class="stats-card">
        <div class="stats-icon despesa">
            <i class="fas fa-arrow-down"></i>
        </div>
        <div class="stats-value text-danger"><?php echo formatMoney($despesas); ?></div>
        <div class="stats-label">Total de Despesas</div>
    </div>
    
    <div class="stats-card">
        <div class="stats-icon saldo">
            <i class="fas fa-balance-scale"></i>
        </div>
        <div class="stats-value <?php echo $saldo >= 0 ? 'text-success' : 'text-danger'; ?>">
            <?php echo formatMoney($saldo); ?>
        </div>
        <div class="stats-label">Saldo Atual</div>
    </div>
</div>

<div class="grid grid-2">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Últimos Lançamentos</h3>
            <a href="pages/lancamentos.php" class="btn btn-primary btn-sm">
                <i class="fas fa-plus"></i> Novo Lançamento
            </a>
        </div>
        
        <?php if (!empty($ultimosLancamentos)): ?>
            <div class="table-container">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Data</th>
                            <th>Descrição</th>
                            <th>Conta</th>
                            <th>Valor</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($ultimosLancamentos as $lancamento):
                            if($lancamento['recorrente'] == 'sim'){
                                $class_rept = '<i class="fas fa-sync-alt"></i> ';
                            }else{
                                $class_rept = '';
                            }?>
                            <tr>
                                <td><?php echo formatDate($lancamento['data_lancamento']); ?></td>
                                <td><?php echo $class_rept; ?></i><?php echo htmlspecialchars($lancamento['descricao']); ?></td>
                                <td>
                                    <span class="badge <?php echo $lancamento['tipo'] == 'Receita' && $lancamento['codigo'] != 'RS2' ? 'bg-success' : 'bg-danger'; ?>">
                                        <?php echo htmlspecialchars($lancamento['codigo']); ?>
                                    </span>
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
    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Resumo por Categoria</h3>
            <a href="pages/relatorios.php" class="btn btn-secondary btn-sm">
                <i class="fas fa-chart-bar"></i> Ver Relatórios
            </a>
        </div>
        
        <?php if (!empty($resumoPorGrupo)): ?>
            <div class="resumo-categorias">
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
                
                <div class="categoria-item d-flex justify-between align-center mb-2">
                    <span><?php echo htmlspecialchars($item['grupo']); ?></span>
                    <div class="text-right">
                        <div class="<?php echo $item['tipo'] == 'Receita' ? 'text-success' : 'text-danger'; ?>">
                            <?php echo formatMoney($item['total']); ?>
                        </div>
                        <small class="text-muted"><?php echo $item['quantidade']; ?> lançamento(s)</small>
                    </div>
                </div>
                
                <?php endforeach; ?>
                </div>
        <?php else: ?>
            <p class="text-muted text-center">Nenhum dado encontrado.</p>
        <?php endif; ?>
    </div>
</div>

<style>
.badge {
    padding: 0.25rem 0.5rem;
    border-radius: 0.25rem;
    font-size: 0.75rem;
    font-weight: 500;
    color: white;
}

.bg-success {
    background-color: var(--success-color);
}

.bg-danger {
    background-color: var(--danger-color);
}

.categoria-item {
    padding: 0.5rem;
    border-radius: var(--border-radius);
    transition: var(--transition);
}

.categoria-item:hover {
    background-color: var(--light-color);
}
</style>

<?php include 'includes/footer.php'; ?>

