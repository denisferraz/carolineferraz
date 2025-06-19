<?php

require_once '../includes/config.php';

$pageTitle = 'Lançamentos Recorrentes';
include '../includes/header.php';

$message = '';
$messageType = '';

// Processar formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $pdo = getConnection();
        
        if (isset($_POST['action'])) {
            switch ($_POST['action']) {
                case 'add':
                    $stmt = $pdo->prepare("
                        INSERT INTO lancamentos_recorrentes (token_emp, data_lancamento, repeticoes, periodo, conta_id, descricao, valor, observacoes)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        $_SESSION['token_emp'],
                        $_POST['data_lancamento'],
                        $_POST['repeticoes'],
                        $_POST['periodo'],
                        $_POST['conta_id'],
                        sanitize($_POST['descricao']),
                        number_format(floatval(str_replace(['R$', '.', ','], ['', '', '.'], $_POST['valor'])), 2, '.', ''),
                        sanitize($_POST['observacoes'])
                    ]);
                    $message = 'Recorrente adicionado com sucesso!';
                    $messageType = 'success';
                    break;
                    
                case 'edit':
                    $stmt = $pdo->prepare("
                        UPDATE lancamentos_recorrentes 
                        SET data_lancamento = ?, repeticoes = ?, periodo = ?, conta_id = ?, descricao = ?, valor = ?, observacoes = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([
                        $_POST['data_lancamento'],
                        $_POST['repeticoes'],
                        $_POST['periodo'],
                        $_POST['conta_id'],
                        sanitize($_POST['descricao']),
                        number_format(floatval(str_replace(['R$', '.', ','], ['', '', '.'], $_POST['valor'])), 2, '.', ''),
                        sanitize($_POST['observacoes']),
                        $_POST['id']
                    ]);
                    $message = 'Recorrente atualizado com sucesso!';
                    $messageType = 'success';
                    break;
                    
                case 'delete':
                    $stmt = $pdo->prepare("DELETE FROM lancamentos_recorrentes WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                    $message = 'Recorrente excluído com sucesso!';
                    $messageType = 'success';
                    break;
            }
        }
    } catch (Exception $e) {
        $message = 'Erro: ' . $e->getMessage();
        $messageType = 'danger';
    }
}

try {
    $pdo = getConnection();
    
    // Buscar contas para o select
    $stmt = $pdo->query("
        SELECT 
            c.id,
            c.codigo,
            c.descricao,
            t.nome as tipo,
            g.nome as grupo
        FROM contas c
        JOIN tipos t ON c.tipo_id = t.id
        JOIN grupos_contas g ON c.grupo_id = g.id
        WHERE c.ativa = 1
        ORDER BY t.nome, g.nome, c.codigo
    ");
    $contas = $stmt->fetchAll();
    
    // Buscar lançamentos com filtros
    $where = "l.token_emp = ?";
    $params = [$_SESSION['token_emp']];

    if (!empty($_GET['data_inicio'])) {
        $where .= " AND l.data_lancamento >= ?";
        $params[] = $_GET['data_inicio'];
    }

    if (!empty($_GET['data_fim'])) {
        $where .= " AND l.data_lancamento <= ?";
        $params[] = $_GET['data_fim'];
    }

    if (!empty($_GET['tipo'])) {
        $where .= " AND t.nome = ?";
        $params[] = $_GET['tipo'];
    }

    if (!empty($_GET['conta_id'])) {
        $where .= " AND c.id = ?";
        $params[] = $_GET['conta_id'];
    }

    $stmt = $pdo->prepare("
        SELECT 
            l.id,
            l.data_lancamento,
            l.repeticoes,
            l.periodo,
            l.conta_id,
            l.descricao,
            l.valor,
            l.observacoes,
            c.codigo,
            c.descricao as conta_descricao,
            t.nome as tipo,
            g.nome as grupo
        FROM lancamentos_recorrentes l
        JOIN contas c ON l.conta_id = c.id
        JOIN tipos t ON c.tipo_id = t.id
        JOIN grupos_contas g ON c.grupo_id = g.id
        WHERE $where
        ORDER BY l.data_lancamento DESC, l.id DESC
    ");
    $stmt->execute($params);
    $lancamentos = $stmt->fetchAll();
    
} catch (Exception $e) {
    $error = "Erro ao carregar dados: " . $e->getMessage();
}
?>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $messageType; ?>">
        <?php echo $message; ?>
    </div>
<?php endif;?>

<div class="card mb-4">
    <div class="card-header">
        <h3 class="card-title">Filtros</h3>
    </div>
    <form method="GET" class="form-row">
        <div class="form-group">
            <label class="form-label">Data Início</label>
            <input type="date" name="data_inicio" class="form-control" value="<?php echo $_GET['data_inicio'] ?? ''; ?>">
        </div>
        <div class="form-group">
            <label class="form-label">Data Fim</label>
            <input type="date" name="data_fim" class="form-control" value="<?php echo $_GET['data_fim'] ?? ''; ?>">
        </div>
        <div class="form-group">
            <label class="form-label">Tipo</label>
            <select name="tipo" class="form-control">
                <option value="">Todos</option>
                <option value="Receita" <?php echo ($_GET['tipo'] ?? '') == 'Receita' ? 'selected' : ''; ?>>Receita</option>
                <option value="Despesa" <?php echo ($_GET['tipo'] ?? '') == 'Despesa' ? 'selected' : ''; ?>>Despesa</option>
            </select>
        </div>
        <div class="form-group">
            <label class="form-label">Conta</label>
            <select name="conta_id" class="form-control">
                <option value="">Todas</option>
                <?php foreach ($contas as $conta): ?>
                    <option value="<?php echo $conta['id']; ?>" <?php echo ($_GET['conta_id'] ?? '') == $conta['id'] ? 'selected' : ''; ?>>
                        <?php echo $conta['codigo'] . ' - ' . $conta['descricao']; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group d-flex align-center">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-search"></i> Filtrar
            </button>
            <a href="lancamentos.php" class="btn btn-secondary ml-2">
                <i class="fas fa-times"></i> Limpar
            </a>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Lançamentos Recorrentes</h3>
        <button onclick="openModal('modalLancamento')" class="btn btn-primary">
            <i class="fas fa-plus"></i> Novo Lançamento Recorrente
        </button>
    </div>
    
    <?php if (!empty($lancamentos)): ?>
        <div class="table-container">
            <table class="table" id="tabelaLancamentos">
                <thead>
                    <tr>
                        <th>Data</th>
                        <th>Recorrencia</th>
                        <th>Descrição</th>
                        <th>Codigo</th>
                        <th>Grupo</th>
                        <th>Valor</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lancamentos as $lancamento): ?>
                        <tr>
                            <td><?php echo formatDate($lancamento['data_lancamento']); ?></td>
                            <td><?php echo htmlspecialchars($lancamento['repeticoes']); ?>x - <?php echo htmlspecialchars($lancamento['periodo']); ?></td>
                            <td>
                                <?php echo htmlspecialchars($lancamento['descricao']); ?>
                                <?php if ($lancamento['observacoes']): ?>
                                    <br><small class="text-muted"><?php echo htmlspecialchars($lancamento['observacoes']); ?></small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge <?php echo $lancamento['tipo'] == 'Receita' && $lancamento['codigo'] != 'RS2' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo $lancamento['codigo']; ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($lancamento['grupo']); ?></td>
                            <td class="<?php echo $lancamento['tipo'] == 'Receita' && $lancamento['codigo'] != 'RS2' ? 'text-success' : 'text-danger'; ?>">
                                <?php echo formatMoney($lancamento['valor']); ?>
                            </td>
                            <td>
                                <button onclick="editarLancamento(<?php echo htmlspecialchars(json_encode($lancamento)); ?>)" 
                                        class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" style="display: inline;" onsubmit="return confirmDelete()">
                                    <input type="hidden" name="action" value="delete">
                                    <input type="hidden" name="id" value="<?php echo $lancamento['id']; ?>">
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">Nenhum lançamento recorrente registrado.</p>
    <?php endif; ?>
</div>

<!-- Modal Lançamento -->
<div id="modalLancamento" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Novo Lançamento Recorrente</h3>
            <span class="close" onclick="closeModal('modalLancamento')">&times;</span>
        </div>
        <form id="formLancamento" method="POST" onsubmit="return prepararValor();">
            <input type="hidden" name="action" value="add" id="formAction">
            <input type="hidden" name="id" id="lancamentoId">
            
            <div class="form-group">
                <label class="form-label">Data Inicio</label>
                <input type="date" name="data_lancamento" id="dataLancamento" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Repetições *</label>
                <input type="text" name="repeticoes" id="repeticoes" class="form-control" required>
            </div>

            <div class="form-group">
                <label class="form-label">Periodo *</label>
                <select name="periodo" id="periodo" class="form-control" required>
                    <option value="" disabled selected>Selecione um Periodo</option>
                    <option value="Diario">Diário</option>
                    <option value="Mensal">Mensal</option>
                    <option value="Anual">Anual</option>
                    </optgroup>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Conta *</label>
                <select name="conta_id" id="contaId" class="form-control" required>
                    <option value="" disabled selected>Selecione uma Conta</option>
                    <?php 
                    $tipoAtual = '';
                    foreach ($contas as $conta): 
                        if ($tipoAtual != $conta['tipo']):
                            if ($tipoAtual != '') echo '</optgroup>';
                            $tipoAtual = $conta['tipo'];
                            echo '<optgroup label="' . $conta['tipo'] . '">';
                        endif;
                    ?>
                        <option value="<?php echo $conta['id']; ?>">
                            <?php echo $conta['codigo'] . ' - ' . $conta['descricao']; ?>
                        </option>
                    <?php endforeach; ?>
                    </optgroup>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Descrição *</label>
                <input type="text" name="descricao" id="descricao" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Valor *</label>
                <input type="text" name="valor" id="valor" class="form-control currency-input" required placeholder="R$ 0,00">
            </div>
            
            <div class="form-group">
                <label class="form-label">Observações</label>
                <textarea name="observacoes" id="observacoes" class="form-control" rows="3"></textarea>
            </div>
            
            <div class="d-flex justify-between">
                <button type="button" onclick="closeModal('modalLancamento')" class="btn btn-secondary">
                    Cancelar
                </button>
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> Salvar
                </button>
            </div>
        </form>
    </div>
</div>

<!-- JavaScript -->
<script src="../js/script.js"></script>

<script>

// Definir data atual como padrão
document.getElementById('dataLancamento').value = new Date().toISOString().split('T')[0];

function editarLancamento(lancamento) {
    document.getElementById('modalTitle').textContent = 'Editar Lançamento';
    document.getElementById('formAction').value = 'edit';
    document.getElementById('lancamentoId').value = lancamento.id;
    document.getElementById('dataLancamento').value = lancamento.data_lancamento;

    // CONVERSÃO EM STRING PARA EVITAR FALHA NA COMPARAÇÃO
    document.getElementById('contaId').value = String(lancamento.conta_id);

    document.getElementById('descricao').value = lancamento.descricao;
    document.getElementById('repeticoes').value = lancamento.repeticoes;
    document.getElementById('periodo').value = lancamento.periodo;
    document.getElementById('valor').value = formatMoney(parseFloat(lancamento.valor));
    document.getElementById('observacoes').value = lancamento.observacoes || '';
    
    openModal('modalLancamento');
}

function prepararValor() {
    const valorInput = document.getElementById('valor');
    if (valorInput && valorInput.value) {
        let bruto = valorInput.value.trim();

        // Mantém sinal de negativo, remove R$ e espaços
        bruto = bruto.replace(/^(-)?R\$\s?/, '$1');

        // Remove pontos de milhar
        let limpo = bruto.replace(/\./g, '');

        // Troca vírgula por ponto (para float)
        //limpo = limpo.replace(',', '.');

        valorInput.value = limpo || '0';
    }
    return true;
}


// Reset form when opening for new entry
document.querySelector('[onclick="openModal(\'modalLancamento\')"]').addEventListener('click', function() {
    document.getElementById('modalTitle').textContent = 'Novo Lançamento';
    document.getElementById('formAction').value = 'add';
    document.getElementById('formLancamento').reset();
    document.getElementById('dataLancamento').value = new Date().toISOString().split('T')[0];
});
</script>

<style>
.ml-2 {
    margin-left: 0.5rem;
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

<?php include '../includes/footer.php'; ?>

