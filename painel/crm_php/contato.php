<?php
require_once("config/database.php");
require_once("config/auth.php");

requireLogin();

$current_user = getCurrentUser();
$token_emp = $current_user['token_emp'];

if (!isset($_GET['id'])) {
    $_SESSION['toast_message'] = 'Contato não encontrado.';
    $_SESSION['toast_type'] = 'danger';
    header('Location: crm.php');
    exit;
}

$contact_id = intval($_GET['id']);

// Buscar dados do contato
$stmt = $pdo->prepare("SELECT * FROM contacts WHERE id = ? AND token_emp = ?");
$stmt->execute([$contact_id, $token_emp]);
$contato = $stmt->fetch();

if (!$contato) {
    $_SESSION['toast_message'] = 'Contato inválido.';
    $_SESSION['toast_type'] = 'danger';
    header('Location: crm.php');
    exit;
}

// Buscar interações
$stmt = $pdo->prepare("SELECT * FROM interactions WHERE contact_id = ? AND token_emp = ? ORDER BY interaction_date DESC");
$stmt->execute([$contact_id, $token_emp]);
$interacoes = $stmt->fetchAll();

$page_title = "Contato - " . htmlspecialchars($contato['name']);
$page_description = "Histórico de conversas e interações";
$page_icon = "fas fa-user";
$show_header = true;

$etapas = ['Novo', 'Em Contato', 'Negociação', 'Fechado', 'Perdido'];

$custom_css = "
    .contact-header {
        background: linear-gradient(135deg, var(--health-primary), var(--health-primary-dark));
        color: white;
        padding: var(--space-6);
        border-radius: var(--health-radius);
        margin-bottom: var(--space-6);
    }
    .contact-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: var(--space-4);
        margin-top: var(--space-4);
    }
    .contact-info-item {
        display: flex;
        align-items: center;
        gap: var(--space-2);
    }
    .stage-selector {
        display: inline-block;
        margin-left: var(--space-3);
    }
";

include("includes/header.php");
?>

<!-- Header do Contato -->
<div class="contact-header">
    <div class="flex justify-between items-start">
        <div>
            <h2 style="margin: 0; font-size: 1.8rem;">
                <i class="fas fa-user"></i>
                <?php echo htmlspecialchars($contato['name']); ?>
            </h2>
            <div class="contact-info">
                <div class="contact-info-item">
                    <i class="fas fa-phone"></i>
                    <span><?php echo htmlspecialchars($contato['phone_number']); ?></span>
                </div>
                <div class="contact-info-item">
                    <i class="fas fa-calendar"></i>
                    <span>Cadastrado em <?php echo date('d/m/Y', strtotime($contato['created_at'])); ?></span>
                </div>
                <div class="contact-info-item">
                    <i class="fas fa-clock"></i>
                    <span>Último contato: <?php echo date('d/m/Y H:i', strtotime($contato['last_contact'])); ?></span>
                </div>
            </div>
        </div>
        <div class="flex gap-3">
            <button class="health-btn health-btn-success" onclick="openWhatsApp('<?php echo $contato['phone_number']; ?>')">
                <i class="fab fa-whatsapp"></i>
                WhatsApp
            </button>
            <a href="crm.php" class="health-btn health-btn-outline" style="color: white; border-color: white;">
                <i class="fas fa-arrow-left"></i>
                Voltar
            </a>
        </div>
    </div>
</div>

<!-- Controles -->
<div class="health-card mb-4">
    <div class="flex justify-between items-center">
        <div>
            <label class="health-label">Etapa atual:</label>
            <select class="health-select stage-selector" onchange="updateStage(<?php echo $contact_id; ?>, this.value)">
                <?php foreach ($etapas as $etapa): ?>
                    <option value="<?php echo $etapa; ?>" <?php echo $etapa === $contato['stage'] ? 'selected' : ''; ?>>
                        <?php echo $etapa; ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="health-badge health-badge-info">
            <?php echo count($interacoes); ?> interação(ões)
        </div>
    </div>
</div>

<!-- Histórico de Conversas -->
<div class="health-card">
    <div class="health-card-header">
        <h3 class="health-card-title">
            <i class="fas fa-comments"></i>
            Histórico de Conversas
        </h3>
    </div>
    
    <div class="message-container">
        <?php if (empty($interacoes)): ?>
            <div class="text-center" style="padding: var(--space-8); color: var(--health-text-muted);">
                <i class="fas fa-comments" style="font-size: 3rem; margin-bottom: var(--space-4);"></i>
                <p>Nenhuma conversa registrada ainda.</p>
            </div>
        <?php else: ?>
            <?php foreach ($interacoes as $msg): ?>
                <div class="message <?php echo $msg['origin']; ?>">
                    <?php echo nl2br(htmlspecialchars($msg['message_content'])); ?>
                    <div class="message-meta">
                        <?php echo date('d/m/Y H:i', strtotime($msg['interaction_date'])); ?> - 
                        <?php echo $msg['origin'] === 'client' ? htmlspecialchars($contato['name']) : 'Empresa'; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Enviar Mensagem -->
<div class="health-card">
    <div class="health-card-header">
        <h3 class="health-card-title">
            <i class="fab fa-whatsapp"></i>
            Enviar Mensagem via WhatsApp
        </h3>
    </div>
    
    <form id="messageForm">
        <div class="health-form-group">
            <label for="message" class="health-label">Mensagem:</label>
            <textarea id="message" name="message" class="health-textarea" placeholder="Digite sua mensagem..." rows="4" required></textarea>
        </div>
        <button type="submit" class="health-btn health-btn-success">
            <i class="fas fa-paper-plane"></i>
            Enviar via WhatsApp
        </button>
    </form>
</div>

<?php
$custom_js = "
    function openWhatsApp(phoneNumber) {
        const cleanPhone = phoneNumber.replace(/\D/g, '');
        window.open('https://wa.me/55' + cleanPhone, '_blank');
    }
    
    function updateStage(contactId, newStage) {
        const select = event.target;
        const originalValue = select.getAttribute('data-original');
        
        fetch('api/update_contact_stage.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                contact_id: contactId,
                stage: newStage
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                showToast('Etapa atualizada com sucesso!', 'success');
                select.setAttribute('data-original', newStage);
                setTimeout(() => location.reload(), 1000);
            } else {
                showToast('Erro ao atualizar etapa: ' + data.message, 'danger');
                select.value = originalValue;
            }
        })
        .catch(error => {
            showToast('Erro de conexão', 'danger');
            select.value = originalValue;
            console.error('Error:', error);
        });
    }
    
    // Submissão do formulário de mensagem
    document.getElementById('messageForm').addEventListener('submit', function(e) {
        e.preventDefault();
        
        const message = document.getElementById('message').value.trim();
        if (!message) {
            showToast('Digite uma mensagem', 'warning');
            return;
        }
        
        const button = this.querySelector('button[type=\"submit\"]');
        showButtonLoading(button);
        
        // Registrar interação no sistema
        fetch('api/add_interaction.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                contact_id: " . $contact_id . ",
                message: message,
                origin: 'company'
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Abrir WhatsApp
                const cleanPhone = '" . $contato['phone_number'] . "'.replace(/\D/g, '');
                const whatsappUrl = 'https://wa.me/55' + cleanPhone + '?text=' + encodeURIComponent(message);
                window.open(whatsappUrl, '_blank');
                
                showToast('Mensagem registrada! WhatsApp aberto.', 'success');
                document.getElementById('message').value = '';
                setTimeout(() => location.reload(), 2000);
            } else {
                showToast('Erro ao registrar mensagem: ' + data.message, 'danger');
            }
        })
        .catch(error => {
            showToast('Erro de conexão', 'danger');
            console.error('Error:', error);
        })
        .finally(() => {
            button.innerHTML = '<i class=\"fas fa-paper-plane\"></i> Enviar via WhatsApp';
            button.disabled = false;
        });
    });
    
    // Salvar valor original do select
    document.querySelector('.stage-selector').setAttribute('data-original', '" . $contato['stage'] . "');
";

include("includes/footer.php");
?>

