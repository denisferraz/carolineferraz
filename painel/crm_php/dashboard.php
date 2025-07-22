<?php
require_once("config/database.php");
require_once("config/auth.php");

requireLogin();

$page_title = "Dashboard";
$page_description = "Visão geral do seu CRM";
$page_icon = "fas fa-chart-line";
$show_header = true;

$current_user = getCurrentUser();
$token_emp = $current_user['token_emp'];

// Buscar estatísticas gerais
$stats = [];

// Total de contatos
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM contacts WHERE token_emp = ?");
$stmt->execute([$token_emp]);
$stats['total_contacts'] = $stmt->fetch()['total'];

// Contatos por etapa
$etapas = ['Novo', 'Em Contato', 'Negociação', 'Fechado', 'Perdido'];
foreach ($etapas as $etapa) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM contacts WHERE stage = ? AND token_emp = ?");
    $stmt->execute([$etapa, $token_emp]);
    $stats['stage_' . strtolower(str_replace(' ', '_', $etapa))] = $stmt->fetch()['total'];
}

// Interações hoje
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM interactions WHERE DATE(interaction_date) = CURDATE() AND token_emp = ?");
$stmt->execute([$token_emp]);
$stats['interactions_today'] = $stmt->fetch()['total'];

// Mensagens não processadas
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM messages WHERE processed = 0 AND token_emp = ?");
$stmt->execute([$token_emp]);
$stats['unprocessed_messages'] = $stmt->fetch()['total'];

// Contatos recentes
$stmt = $pdo->prepare("SELECT * FROM contacts WHERE token_emp = ? ORDER BY created_at DESC LIMIT 5");
$stmt->execute([$token_emp]);
$recent_contacts = $stmt->fetchAll();

// Interações recentes
$stmt = $pdo->prepare("
    SELECT i.*, c.name as contact_name 
    FROM interactions i 
    JOIN contacts c ON i.contact_id = c.id 
    WHERE i.token_emp = ? 
    ORDER BY i.interaction_date DESC 
    LIMIT 10
");
$stmt->execute([$token_emp]);
$recent_interactions = $stmt->fetchAll();

$custom_css = "
    .dashboard-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: var(--space-4);
        margin-bottom: var(--space-6);
    }
    .metric-card {
        background: var(--health-bg-card);
        padding: var(--space-5);
        border-radius: var(--health-radius);
        box-shadow: var(--health-shadow);
        text-align: center;
        border-left: 4px solid var(--health-primary);
        transition: transform 0.2s ease;
    }
    .metric-card:hover {
        transform: translateY(-2px);
    }
    .metric-number {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--health-primary);
        margin-bottom: var(--space-2);
    }
    .metric-label {
        color: var(--health-text-secondary);
        font-weight: 500;
    }
    .metric-icon {
        font-size: 1.5rem;
        color: var(--health-primary);
        margin-bottom: var(--space-3);
    }
    .content-grid {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: var(--space-6);
    }
    @media (max-width: 768px) {
        .content-grid {
            grid-template-columns: 1fr;
        }
    }
";

include("includes/header.php");
?>

<!-- Métricas Principais -->
<div class="dashboard-grid">
    <div class="metric-card">
        <div class="metric-icon">
            <i class="fas fa-users"></i>
        </div>
        <div class="metric-number"><?php echo $stats['total_contacts']; ?></div>
        <div class="metric-label">Total de Contatos</div>
    </div>
    
    <div class="metric-card">
        <div class="metric-icon">
            <i class="fas fa-handshake"></i>
        </div>
        <div class="metric-number"><?php echo $stats['stage_fechado']; ?></div>
        <div class="metric-label">Negócios Fechados</div>
    </div>
    
    <div class="metric-card">
        <div class="metric-icon">
            <i class="fas fa-comments"></i>
        </div>
        <div class="metric-number"><?php echo $stats['interactions_today']; ?></div>
        <div class="metric-label">Interações Hoje</div>
    </div>
    
    <div class="metric-card">
        <div class="metric-icon">
            <i class="fas fa-envelope"></i>
        </div>
        <div class="metric-number"><?php echo $stats['unprocessed_messages']; ?></div>
        <div class="metric-label">Mensagens Pendentes</div>
    </div>
</div>

<!-- Pipeline Overview -->
<div class="health-card mb-6">
    <div class="health-card-header">
        <h3 class="health-card-title">
            <i class="fas fa-chart-pie"></i>
            Pipeline de Vendas
        </h3>
    </div>
    
    <div class="dashboard-grid">
        <?php foreach ($etapas as $etapa): ?>
            <div class="metric-card" style="border-left-color: var(--stage-<?php echo strtolower(str_replace(' ', '-', $etapa)); ?>);">
                <div class="metric-number"><?php echo $stats['stage_' . strtolower(str_replace(' ', '_', $etapa))]; ?></div>
                <div class="metric-label"><?php echo $etapa; ?></div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Conteúdo em Grid -->
<div class="content-grid">
    <!-- Contatos Recentes -->
    <div class="health-card">
        <div class="health-card-header">
            <h3 class="health-card-title">
                <i class="fas fa-user-plus"></i>
                Contatos Recentes
            </h3>
        </div>
        
        <?php if (empty($recent_contacts)): ?>
            <div class="text-center" style="padding: var(--space-6); color: var(--health-text-muted);">
                <i class="fas fa-users" style="font-size: 2rem; margin-bottom: var(--space-3);"></i>
                <p>Nenhum contato cadastrado ainda.</p>
                <a href="crm.php" class="health-btn health-btn-primary health-btn-sm">
                    <i class="fas fa-plus"></i>
                    Adicionar Contato
                </a>
            </div>
        <?php else: ?>
            <div style="max-height: 400px; overflow-y: auto;">
                <?php foreach ($recent_contacts as $contact): ?>
                    <div style="padding: var(--space-3); border-bottom: 1px solid #e2e8f0; display: flex; justify-content: space-between; align-items: center;">
                        <div>
                            <div style="font-weight: 600; color: var(--health-text-primary);">
                                <?php echo htmlspecialchars($contact['name']); ?>
                            </div>
                            <div style="font-size: 0.8rem; color: var(--health-text-secondary);">
                                <?php echo htmlspecialchars($contact['phone_number']); ?>
                            </div>
                            <div style="font-size: 0.7rem; color: var(--health-text-muted);">
                                <?php echo date('d/m/Y H:i', strtotime($contact['created_at'])); ?>
                            </div>
                        </div>
                        <div>
                            <span class="health-badge health-badge-info">
                                <?php echo $contact['stage']; ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="padding: var(--space-3); text-align: center;">
                <a href="crm.php" class="health-btn health-btn-outline health-btn-sm">
                    Ver Todos os Contatos
                </a>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- Atividades Recentes -->
    <div class="health-card">
        <div class="health-card-header">
            <h3 class="health-card-title">
                <i class="fas fa-history"></i>
                Atividades Recentes
            </h3>
        </div>
        
        <?php if (empty($recent_interactions)): ?>
            <div class="text-center" style="padding: var(--space-6); color: var(--health-text-muted);">
                <i class="fas fa-comments" style="font-size: 2rem; margin-bottom: var(--space-3);"></i>
                <p>Nenhuma atividade registrada ainda.</p>
            </div>
        <?php else: ?>
            <div style="max-height: 400px; overflow-y: auto;">
                <?php foreach ($recent_interactions as $interaction): ?>
                    <div style="padding: var(--space-3); border-bottom: 1px solid #e2e8f0;">
                        <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: var(--space-2);">
                            <div style="font-weight: 600; color: var(--health-text-primary);">
                                <?php echo htmlspecialchars($interaction['contact_name']); ?>
                            </div>
                            <div style="font-size: 0.7rem; color: var(--health-text-muted);">
                                <?php echo date('d/m H:i', strtotime($interaction['interaction_date'])); ?>
                            </div>
                        </div>
                        <div style="font-size: 0.9rem; color: var(--health-text-secondary); margin-bottom: var(--space-2);">
                            <?php echo nl2br(htmlspecialchars(substr($interaction['message_content'], 0, 100))); ?>
                            <?php if (strlen($interaction['message_content']) > 100): ?>...<?php endif; ?>
                        </div>
                        <div>
                            <span class="health-badge <?php echo $interaction['origin'] === 'client' ? 'health-badge-info' : 'health-badge-success'; ?>">
                                <?php echo $interaction['origin'] === 'client' ? 'Cliente' : 'Empresa'; ?>
                            </span>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            <div style="padding: var(--space-3); text-align: center;">
                <a href="mensagens.php" class="health-btn health-btn-outline health-btn-sm">
                    Ver Todas as Mensagens
                </a>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Ações Rápidas -->
<div class="health-card">
    <div class="health-card-header">
        <h3 class="health-card-title">
            <i class="fas fa-bolt"></i>
            Ações Rápidas
        </h3>
    </div>
    
    <div class="flex gap-4">
        <a href="crm.php" class="health-btn health-btn-primary">
            <i class="fas fa-plus"></i>
            Novo Contato
        </a>
        <a href="mensagens.php" class="health-btn health-btn-success">
            <i class="fab fa-whatsapp"></i>
            Ver Mensagens
        </a>
        <a href="relatorios.php" class="health-btn health-btn-info">
            <i class="fas fa-chart-bar"></i>
            Relatórios
        </a>
        <button class="health-btn health-btn-warning" onclick="updateMessages()">
            <i class="fas fa-sync"></i>
            Atualizar Mensagens
        </button>
    </div>
</div>

<?php
$custom_js = "
    function updateMessages() {
        const button = event.target;
        showButtonLoading(button);
        
        fetch('api/update_messages.php', {
            method: 'POST'
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Mensagens atualizadas com sucesso!', 'success');
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erro ao atualizar mensagens: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            showToast('Erro de conexão', 'danger');
            console.error('Error:', error);
        })
        .finally(() => {
            button.innerHTML = '<i class=\"fas fa-sync\"></i> Atualizar Mensagens';
            button.disabled = false;
        });
    }
    
    // Auto-refresh a cada 5 minutos
    setInterval(() => {
        location.reload();
    }, 300000);
";

include("includes/footer.php");
?>

