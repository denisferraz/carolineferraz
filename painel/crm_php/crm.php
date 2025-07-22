<?php
require_once("config/database.php");
require_once("config/auth.php");

requireLogin();

$page_title = "CRM - Contatos";
$page_description = "Gerencie seus contatos e leads de forma eficiente";
$page_icon = "fas fa-users";
$show_header = true;

$current_user = getCurrentUser();
$token_emp = $current_user['token_emp'];

// Definir etapas do CRM
$etapas = ['Novo', 'Em Contato', 'Negociação', 'Fechado', 'Perdido'];

// Buscar estatísticas
$stats = [];
foreach ($etapas as $etapa) {
    $stmt = $pdo->prepare("SELECT COUNT(*) as total FROM contacts WHERE stage = ? AND token_emp = ?");
    $stmt->execute([$etapa, $token_emp]);
    $stats[$etapa] = $stmt->fetch()['total'];
}

$custom_css = "
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-4);
        margin-bottom: var(--space-6);
    }
    .stat-card {
        background: var(--health-bg-card);
        padding: var(--space-4);
        border-radius: var(--health-radius);
        box-shadow: var(--health-shadow);
        text-align: center;
        border-left: 4px solid var(--health-primary);
    }
    .stat-number {
        font-size: 2rem;
        font-weight: 700;
        color: var(--health-primary);
    }
    .stat-label {
        color: var(--health-text-secondary);
        font-size: 0.9rem;
        margin-top: var(--space-2);
    }
";

include("includes/header.php");
?>

<!-- Estatísticas -->
<div class="stats-grid">
    <?php foreach ($etapas as $etapa): ?>
        <div class="stat-card">
            <div class="stat-number"><?php echo $stats[$etapa]; ?></div>
            <div class="stat-label"><?php echo $etapa; ?></div>
        </div>
    <?php endforeach; ?>
</div>

<!-- Kanban Board -->
<div class="health-card">
    <div class="health-card-header">
        <div class="flex justify-between items-center">
            <h3 class="health-card-title">
                <i class="fas fa-columns"></i>
                Pipeline de Vendas
            </h3>
            <button class="health-btn health-btn-primary" onclick="openAddContactModal()">
                <i class="fas fa-plus"></i>
                Novo Contato
            </button>
        </div>
    </div>
    
    <div class="kanban-container">
        <?php foreach ($etapas as $etapa): ?>
            <div class="kanban-column" data-stage="<?php echo strtolower(str_replace(' ', '-', $etapa)); ?>">
                <div class="kanban-header <?php echo strtolower(str_replace(' ', '-', $etapa)); ?>">
                    <?php echo $etapa; ?> (<?php echo $stats[$etapa]; ?>)
                </div>
                
                <?php
                $stmt = $pdo->prepare("SELECT * FROM contacts WHERE stage = ? AND token_emp = ? ORDER BY last_contact DESC");
                $stmt->execute([$etapa, $token_emp]);
                while ($contato = $stmt->fetch()):
                ?>
                    <div class="contact-card" data-contact-id="<?php echo $contato['id']; ?>">
                        <div class="contact-name"><?php echo htmlspecialchars($contato['name']); ?></div>
                        <div class="contact-phone">
                            <i class="fas fa-phone"></i>
                            <?php echo htmlspecialchars($contato['phone_number']); ?>
                        </div>
                        <div class="contact-date">
                            <i class="fas fa-clock"></i>
                            <?php echo date('d/m/Y H:i', strtotime($contato['last_contact'])); ?>
                        </div>
                        <div class="mt-3 flex gap-2">
                            <a href="contato.php?id=<?php echo $contato['id']; ?>" class="health-btn health-btn-primary health-btn-sm" style="flex: 1;">
                                <i class="fas fa-eye"></i>
                                Ver
                            </a>
                            <button class="health-btn health-btn-outline health-btn-sm" onclick="openWhatsApp('<?php echo $contato['phone_number']; ?>')">
                                <i class="fab fa-whatsapp"></i>
                            </button>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<!-- Modal para Novo Contato -->
<div id="addContactModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); z-index: 9999;">
    <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); background: var(--health-bg-card); padding: var(--space-6); border-radius: var(--health-radius); width: 90%; max-width: 500px;">
        <div class="flex justify-between items-center mb-4">
            <h3 class="health-card-title">
                <i class="fas fa-user-plus"></i>
                Novo Contato
            </h3>
            <button onclick="closeAddContactModal()" class="health-btn health-btn-outline health-btn-sm">
                <i class="fas fa-times"></i>
            </button>
        </div>
        
        <form id="addContactForm" action="api/add_contact.php" method="POST">
            <div class="health-form-group">
                <label for="name" class="health-label">Nome *</label>
                <input type="text" id="name" name="name" class="health-input" required>
            </div>
            
            <div class="health-form-group">
                <label for="phone_number" class="health-label">Telefone *</label>
                <input type="tel" id="phone_number" name="phone_number" class="health-input" required oninput="formatPhone(this)">
            </div>
            
            <div class="health-form-group">
                <label for="stage" class="health-label">Etapa</label>
                <select id="stage" name="stage" class="health-select">
                    <?php foreach ($etapas as $etapa): ?>
                        <option value="<?php echo $etapa; ?>"><?php echo $etapa; ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="flex gap-3">
                <button type="submit" class="health-btn health-btn-primary" style="flex: 1;">
                    <i class="fas fa-save"></i>
                    Salvar
                </button>
                <button type="button" onclick="closeAddContactModal()" class="health-btn health-btn-outline">
                    Cancelar
                </button>
            </div>
        </form>
    </div>
</div>

<?php
$custom_js = "
    function openAddContactModal() {
        document.getElementById('addContactModal').style.display = 'block';
    }
    
    function closeAddContactModal() {
        document.getElementById('addContactModal').style.display = 'none';
        document.getElementById('addContactForm').reset();
    }
    
    function openWhatsApp(phoneNumber) {
        const cleanPhone = phoneNumber.replace(/\D/g, '');
        window.open('https://wa.me/55' + cleanPhone, '_blank');
    }
    
    // Submissão do formulário via AJAX
    document.getElementById('addContactForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const formData = new FormData(this);
        const button = this.querySelector('button[type=\"submit\"]');
        
        showButtonLoading(button);
        
        fetch('api/add_contact.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Contato adicionado com sucesso!', 'success');
                closeAddContactModal();
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erro ao adicionar contato: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            showToast('Erro de conexão', 'danger');
            console.error('Error:', error);
        })
        .finally(() => {
            button.innerHTML = '<i class=\"fas fa-save\"></i> Salvar';
            button.disabled = false;
        });
    });
    
    // Inicializar Kanban drag and drop
    initializeKanban();
";

include("includes/footer.php");
?>

