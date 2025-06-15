<?php
require_once '../includes/config.php';

$pageTitle = 'Gerenciar Contas';
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
                        INSERT INTO contas (codigo, descricao, tipo_id, grupo_id, sub_grupo_id)
                        VALUES (?, ?, ?, ?, ?)
                    ");
                    $stmt->execute([
                        sanitize($_POST['codigo']),
                        sanitize($_POST['descricao']),
                        $_POST['tipo_id'],
                        $_POST['grupo_id'],
                        $_POST['sub_grupo_id'] ?: null
                    ]);
                    $message = 'Conta adicionada com sucesso!';
                    $messageType = 'success';
                    break;
                    
                case 'edit':
                    $stmt = $pdo->prepare("
                        UPDATE contas 
                        SET codigo = ?, descricao = ?, tipo_id = ?, grupo_id = ?, sub_grupo_id = ?
                        WHERE id = ?
                    ");
                    $stmt->execute([
                        sanitize($_POST['codigo']),
                        sanitize($_POST['descricao']),
                        $_POST['tipo_id'],
                        $_POST['grupo_id'],
                        $_POST['sub_grupo_id'] ?: null,
                        $_POST['id']
                    ]);
                    $message = 'Conta atualizada com sucesso!';
                    $messageType = 'success';
                    break;
                    
                case 'toggle':
                    $stmt = $pdo->prepare("UPDATE contas SET ativa = !ativa WHERE id = ?");
                    $stmt->execute([$_POST['id']]);
                    $message = 'Status da conta alterado com sucesso!';
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
    
    // Buscar tipos
    $stmt = $pdo->query("SELECT * FROM tipos ORDER BY nome");
    $tipos = $stmt->fetchAll();
    
    // Buscar grupos
    $stmt = $pdo->query("
        SELECT g.*, t.nome as tipo_nome 
        FROM grupos_contas g 
        JOIN tipos t ON g.tipo_id = t.id 
        ORDER BY t.nome, g.nome
    ");
    $grupos = $stmt->fetchAll();
    
    // Buscar sub-grupos
    $stmt = $pdo->query("
        SELECT sg.*, g.nome as grupo_nome, t.nome as tipo_nome
        FROM sub_grupos_contas sg 
        JOIN grupos_contas g ON sg.grupo_id = g.id
        JOIN tipos t ON g.tipo_id = t.id
        ORDER BY t.nome, g.nome, sg.nome
    ");
    $subGrupos = $stmt->fetchAll();
    
    // Buscar contas
    $stmt = $pdo->query("
        SELECT 
            c.*,
            t.nome as tipo_nome,
            g.nome as grupo_nome,
            sg.nome as sub_grupo_nome,
            COUNT(l.id) as total_lancamentos
        FROM contas c
        JOIN tipos t ON c.tipo_id = t.id
        JOIN grupos_contas g ON c.grupo_id = g.id
        LEFT JOIN sub_grupos_contas sg ON c.sub_grupo_id = sg.id
        LEFT JOIN lancamentos l ON c.id = l.conta_id
        GROUP BY c.id
        ORDER BY t.nome, g.nome, c.codigo
    ");
    $contas = $stmt->fetchAll();
    
} catch (Exception $e) {
    $error = "Erro ao carregar dados: " . $e->getMessage();
}
?>

<?php if ($message): ?>
    <div class="alert alert-<?php echo $messageType; ?>">
        <?php echo $message; ?>
    </div>
<?php endif; ?>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">Contas Cadastradas</h3>
        <button onclick="openModal('modalConta')" class="btn btn-primary">
            <i class="fas fa-plus"></i> Nova Conta
        </button>
    </div>
    
    <div class="mb-3" style="padding: 0 1.5rem;">
        <input type="text" id="searchInput" class="form-control" placeholder="Buscar contas..." 
               onkeyup="filterTable(this, 'tabelaContas')">
    </div>
    
    <?php if (!empty($contas)): ?>
        <div class="table-container">
            <table class="table" id="tabelaContas">
                <thead>
                    <tr>
                        <th>Código</th>
                        <th>Descrição</th>
                        <th>Tipo</th>
                        <th>Grupo</th>
                        <th>Sub-grupo</th>
                        <th>Lançamentos</th>
                        <th>Status</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($contas as $conta): ?>
                        <tr class="<?php echo !$conta['ativa'] ? 'text-muted' : ''; ?>">
                            <td>
                                <span class="badge <?php echo $conta['tipo_nome'] == 'Receita' ? 'bg-success' : 'bg-danger'; ?>">
                                    <?php echo htmlspecialchars($conta['codigo']); ?>
                                </span>
                            </td>
                            <td><?php echo htmlspecialchars($conta['descricao']); ?></td>
                            <td><?php echo htmlspecialchars($conta['tipo_nome']); ?></td>
                            <td><?php echo htmlspecialchars($conta['grupo_nome']); ?></td>
                            <td><?php echo htmlspecialchars($conta['sub_grupo_nome'] ?: '-'); ?></td>
                            <td>
                                <span class="badge bg-secondary">
                                    <?php echo $conta['total_lancamentos']; ?>
                                </span>
                            </td>
                            <td>
                                <span class="badge <?php echo $conta['ativa'] ? 'bg-success' : 'bg-secondary'; ?>">
                                    <?php echo $conta['ativa'] ? 'Ativa' : 'Inativa'; ?>
                                </span>
                            </td>
                            <td>
                                <button onclick="editarConta(<?php echo htmlspecialchars(json_encode($conta)); ?>)" 
                                        class="btn btn-sm btn-secondary">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="action" value="toggle">
                                    <input type="hidden" name="id" value="<?php echo $conta['id']; ?>">
                                    <button type="submit" class="btn btn-sm <?php echo $conta['ativa'] ? 'btn-warning' : 'btn-success'; ?>"
                                            title="<?php echo $conta['ativa'] ? 'Desativar' : 'Ativar'; ?>">
                                        <i class="fas fa-<?php echo $conta['ativa'] ? 'eye-slash' : 'eye'; ?>"></i>
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <p class="text-muted text-center">Nenhuma conta encontrada.</p>
    <?php endif; ?>
</div>

<!-- Resumo por Tipo -->
<div class="grid grid-2 mt-4">
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Resumo por Tipo</h3>
        </div>
        <?php 
        $resumoTipos = [];
        foreach ($contas as $conta) {
            if (!isset($resumoTipos[$conta['tipo_nome']])) {
                $resumoTipos[$conta['tipo_nome']] = ['total' => 0, 'ativas' => 0];
            }
            $resumoTipos[$conta['tipo_nome']]['total']++;
            if ($conta['ativa']) {
                $resumoTipos[$conta['tipo_nome']]['ativas']++;
            }
        }
        ?>
        <?php foreach ($resumoTipos as $tipo => $dados): ?>
            <div class="d-flex justify-between align-center mb-2" style="padding: 0 1.5rem;">
                <span><?php echo $tipo; ?></span>
                <div>
                    <span class="badge bg-success"><?php echo $dados['ativas']; ?> ativas</span>
                    <span class="badge bg-secondary"><?php echo $dados['total']; ?> total</span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Resumo por Grupo</h3>
        </div>
        <?php 
        $resumoGrupos = [];
        foreach ($contas as $conta) {
            if (!isset($resumoGrupos[$conta['grupo_nome']])) {
                $resumoGrupos[$conta['grupo_nome']] = ['total' => 0, 'ativas' => 0];
            }
            $resumoGrupos[$conta['grupo_nome']]['total']++;
            if ($conta['ativa']) {
                $resumoGrupos[$conta['grupo_nome']]['ativas']++;
            }
        }
        arsort($resumoGrupos);
        $top5Grupos = array_slice($resumoGrupos, 0, 5, true);
        ?>
        <?php foreach ($top5Grupos as $grupo => $dados): ?>
            <div class="d-flex justify-between align-center mb-2" style="padding: 0 1.5rem;">
                <span><?php echo htmlspecialchars($grupo); ?></span>
                <div>
                    <span class="badge bg-success"><?php echo $dados['ativas']; ?></span>
                    <span class="badge bg-secondary"><?php echo $dados['total']; ?></span>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal Conta -->
<div id="modalConta" class="modal">
    <div class="modal-content">
        <div class="modal-header">
            <h3 class="modal-title" id="modalTitle">Nova Conta</h3>
            <span class="close" onclick="closeModal('modalConta')">&times;</span>
        </div>
        <form id="formConta" method="POST">
            <input type="hidden" name="action" value="add" id="formAction">
            <input type="hidden" name="id" id="contaId">
            
            <div class="form-group">
                <label class="form-label">Código *</label>
                <input type="text" name="codigo" id="codigo" class="form-control" required maxlength="10">
            </div>
            
            <div class="form-group">
                <label class="form-label">Descrição *</label>
                <input type="text" name="descricao" id="descricao" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Tipo *</label>
                <select name="tipo_id" id="tipoId" class="form-control" required onchange="carregarGrupos()">
                    <option value="">Selecione um tipo</option>
                    <?php foreach ($tipos as $tipo): ?>
                        <option value="<?php echo $tipo['id']; ?>"><?php echo $tipo['nome']; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Grupo *</label>
                <select name="grupo_id" id="grupoId" class="form-control" required onchange="carregarSubGrupos()">
                    <option value="">Selecione um grupo</option>
                </select>
            </div>
            
            <div class="form-group">
                <label class="form-label">Sub-grupo</label>
                <select name="sub_grupo_id" id="subGrupoId" class="form-control">
                    <option value="">Selecione um sub-grupo (opcional)</option>
                </select>
            </div>
            
            <div class="d-flex justify-between">
                <button type="button" onclick="closeModal('modalConta')" class="btn btn-secondary">
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
// Dados para os selects
const grupos = <?php echo json_encode($grupos); ?>;
const subGrupos = <?php echo json_encode($subGrupos); ?>;

function carregarGrupos() {
    const tipoId = document.getElementById('tipoId').value;
    const grupoSelect = document.getElementById('grupoId');
    const subGrupoSelect = document.getElementById('subGrupoId');
    
    // Limpar selects
    grupoSelect.innerHTML = '<option value="">Selecione um grupo</option>';
    subGrupoSelect.innerHTML = '<option value="">Selecione um sub-grupo (opcional)</option>';
    
    if (tipoId) {
        const gruposFiltrados = grupos.filter(g => g.tipo_id == tipoId);
        gruposFiltrados.forEach(grupo => {
            const option = document.createElement('option');
            option.value = grupo.id;
            option.textContent = grupo.nome;
            grupoSelect.appendChild(option);
        });
    }
}

function carregarSubGrupos() {
    const grupoId = document.getElementById('grupoId').value;
    const subGrupoSelect = document.getElementById('subGrupoId');
    
    // Limpar select
    subGrupoSelect.innerHTML = '<option value="">Selecione um sub-grupo (opcional)</option>';
    
    if (grupoId) {
        const subGruposFiltrados = subGrupos.filter(sg => sg.grupo_id == grupoId);
        subGruposFiltrados.forEach(subGrupo => {
            const option = document.createElement('option');
            option.value = subGrupo.id;
            option.textContent = subGrupo.nome;
            subGrupoSelect.appendChild(option);
        });
    }
}

function editarConta(conta) {
    document.getElementById('modalTitle').textContent = 'Editar Conta';
    document.getElementById('formAction').value = 'edit';
    document.getElementById('contaId').value = conta.id;
    document.getElementById('codigo').value = conta.codigo;
    document.getElementById('descricao').value = conta.descricao;
    document.getElementById('tipoId').value = conta.tipo_id;
    
    carregarGrupos();
    setTimeout(() => {
        document.getElementById('grupoId').value = conta.grupo_id;
        carregarSubGrupos();
        setTimeout(() => {
            if (conta.sub_grupo_id) {
                document.getElementById('subGrupoId').value = conta.sub_grupo_id;
            }
        }, 100);
    }, 100);
    
    openModal('modalConta');
}

// Reset form when opening for new entry
document.querySelector('[onclick="openModal(\'modalConta\')"]').addEventListener('click', function() {
    document.getElementById('modalTitle').textContent = 'Nova Conta';
    document.getElementById('formAction').value = 'add';
    document.getElementById('formConta').reset();
    document.getElementById('grupoId').innerHTML = '<option value="">Selecione um grupo</option>';
    document.getElementById('subGrupoId').innerHTML = '<option value="">Selecione um sub-grupo (opcional)</option>';
});
</script>

<style>
.bg-secondary {
    background-color: var(--secondary-color) !important;
}

.text-muted {
    opacity: 0.6;
}
</style>

<?php include '../includes/footer.php'; ?>

