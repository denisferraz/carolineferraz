<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$token_emp = $_SESSION['token_emp'];
$pdo = $conexao;

if (!isset($_GET['id'])) {
    echo "Contato não encontrado.";
    exit;
}

$contato_id = intval($_GET['id']);

// Buscar dados do contato
$stmt = $pdo->prepare("SELECT * FROM contatos WHERE id = ? AND token_emp = ?");
$stmt->execute([$contato_id, $token_emp]);
$contato = $stmt->fetch();

if (!$contato) {
    echo "Contato inválido.";
    exit;
}

// Buscar interações
$stmt = $pdo->prepare("SELECT * FROM interacoes WHERE contato_id = ? AND token_emp = ? ORDER BY data DESC");
$stmt->execute([$contato_id, $token_emp]);
$interacoes = $stmt->fetchAll();

// Buscar agendamentos
$stmt = $conexao->prepare("
    SELECT * FROM agendamentos_automaticos 
    WHERE contato_id = ? AND enviado = 0
    ORDER BY agendar_para ASC
");
$stmt->execute([$contato_id]);
$agendados = $stmt->fetchAll();

$total_agendamentos = count($agendados);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM - Detalhes do Contato: <?= htmlspecialchars($contato['nome']) ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/health_theme.css">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
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
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--space-6);
        }

        /* Header do Contato */
        .contact-header {
            background: linear-gradient(135deg, var(--health-primary), var(--health-primary-dark));
            color: white;
            padding: var(--space-6);
            border-radius: var(--health-radius);
            margin-bottom: var(--space-6);
            box-shadow: var(--health-shadow-lg);
        }

        .contact-header-content {
            display: flex;
            justify-content: between;
            align-items: flex-start;
            gap: var(--space-4);
            flex-wrap: wrap;
        }

        .contact-info {
            flex: 1;
        }

        .contact-name {
            font-size: 2rem;
            font-weight: 700;
            margin-bottom: var(--space-2);
            display: flex;
            align-items: center;
            gap: var(--space-3);
        }

        .contact-number {
            font-size: 1.1rem;
            opacity: 0.9;
            font-family: 'Courier New', monospace;
            margin-bottom: var(--space-4);
        }

        .contact-actions {
            display: flex;
            gap: var(--space-3);
            align-items: center;
            flex-wrap: wrap;
        }

        /* Etapa Selector */
        .stage-selector {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--health-radius-sm);
            padding: var(--space-2) var(--space-3);
            color: white;
            font-weight: 500;
        }

        .stage-selector:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.4);
            color: black;
        }

        /* Follow-up Input */
        .followup-input {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            border-radius: var(--health-radius-sm);
            padding: var(--space-2) var(--space-3);
            color: white;
            font-weight: 500;
        }

        .followup-input:focus {
            outline: none;
            border-color: rgba(255, 255, 255, 0.4);
        }

        /* Botão Voltar */
        .back-btn {
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            padding: var(--space-2) var(--space-4);
            border-radius: var(--health-radius-sm);
            text-decoration: none;
            font-weight: 500;
            transition: all 0.2s ease;
            display: inline-flex;
            align-items: center;
            gap: var(--space-2);
        }

        .back-btn:hover {
            background: rgba(255, 255, 255, 0.2);
            color: white;
            transform: translateY(-1px);
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
        }

        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--health-shadow-lg);
        }

        .stat-card.messages {
            border-left-color: var(--health-info);
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
        }

        .stat-card.client {
            border-left-color: var(--health-success);
            background: linear-gradient(135deg, #ecfdf5, #d1fae5);
        }

        .stat-card.company {
            border-left-color: var(--health-primary);
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
        }

        .stat-card.scheduled {
            border-left-color: var(--health-warning);
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
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

        .stat-card.messages .stat-number { color: var(--health-info); }
        .stat-card.client .stat-number { color: var(--health-success); }
        .stat-card.company .stat-number { color: var(--health-primary); }
        .stat-card.scheduled .stat-number { color: var(--health-warning); }

        .stat-label {
            color: var(--health-text-secondary);
            font-weight: 500;
            font-size: 0.9rem;
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

        /* Formulários */
        .health-form {
            display: flex;
            flex-direction: column;
            gap: var(--space-4);
        }

        .form-group {
            display: flex;
            flex-direction: column;
            gap: var(--space-2);
        }

        .form-label {
            font-weight: 600;
            color: var(--health-text-primary);
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }

        .form-input,
        .form-textarea,
        .form-select {
            padding: var(--space-3);
            border: 1px solid #d1d5db;
            border-radius: var(--health-radius-sm);
            font-size: 0.875rem;
            transition: border-color 0.2s ease;
        }

        .form-input:focus,
        .form-textarea:focus,
        .form-select:focus {
            outline: none;
            border-color: var(--health-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 100px;
        }

        /* Botões */
        .health-btn {
            display: inline-flex;
            align-items: center;
            gap: var(--space-2);
            padding: var(--space-3) var(--space-4);
            border-radius: var(--health-radius-sm);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
        }

        .health-btn-primary {
            background: var(--health-primary);
            color: white;
        }

        .health-btn-primary:hover {
            background: var(--health-primary-dark);
            color: white;
            transform: translateY(-1px);
        }

        .health-btn-success {
            background: var(--health-success);
            color: white;
        }

        .health-btn-success:hover {
            background: #047857;
            color: white;
            transform: translateY(-1px);
        }

        .health-btn-warning {
            background: var(--health-warning);
            color: white;
        }

        .health-btn-warning:hover {
            background: #b45309;
            color: white;
            transform: translateY(-1px);
        }

        .health-btn-danger {
            background: var(--health-danger);
            color: white;
        }

        .health-btn-danger:hover {
            background: #b91c1c;
            color: white;
            transform: translateY(-1px);
        }

        /* Agendamentos */
        .scheduled-list {
            display: flex;
            flex-direction: column;
            gap: var(--space-3);
        }

        .scheduled-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: var(--health-radius-sm);
            padding: var(--space-4);
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: var(--space-4);
        }

        .scheduled-content {
            flex: 1;
        }

        .scheduled-message {
            color: var(--health-text-primary);
            margin-bottom: var(--space-2);
            line-height: 1.5;
        }

        .scheduled-datetime {
            color: var(--health-text-secondary);
            font-size: 0.875rem;
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }

        .scheduled-actions {
            display: flex;
            gap: var(--space-2);
        }

        .empty-state {
            text-align: center;
            padding: var(--space-8);
            color: var(--health-text-secondary);
        }

        .empty-state i {
            font-size: 3rem;
            margin-bottom: var(--space-4);
            opacity: 0.5;
        }

        /* Chat */
        .chat-container {
            max-height: 500px;
            overflow-y: auto;
            padding: var(--space-4);
            background: #f8fafc;
            border-radius: var(--health-radius-sm);
            border: 1px solid #e2e8f0;
        }

        .message {
            margin-bottom: var(--space-4);
            display: flex;
            flex-direction: column;
        }

        .message.client {
            align-items: flex-start;
        }

        .message.company {
            align-items: flex-end;
        }

        .message-bubble {
            max-width: 70%;
            padding: var(--space-3);
            border-radius: var(--health-radius-sm);
            position: relative;
        }

        .message.client .message-bubble {
            background: white;
            border: 1px solid #e2e8f0;
        }

        .message.company .message-bubble {
            background: var(--health-primary);
            color: white;
        }

        .message-text {
            line-height: 1.5;
            margin-bottom: var(--space-2);
        }

        .message-meta {
            font-size: 0.75rem;
            opacity: 0.7;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .health-container {
                padding: var(--space-4);
            }
            
            .contact-header-content {
                flex-direction: column;
            }
            
            .contact-actions {
                width: 100%;
                justify-content: flex-start;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .contact-name {
                font-size: 1.5rem;
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .scheduled-item {
                flex-direction: column;
                align-items: stretch;
            }
            
            .scheduled-actions {
                justify-content: flex-end;
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
    </style>
</head>
<body>
    <div class="health-container">
        <!-- Header do Contato -->
        <div class="contact-header health-fade-in">
            <div class="contact-header-content">
                <div class="contact-info">
                    <h1 class="contact-name">
                        <i class="fas fa-user-circle"></i>
                        <?= htmlspecialchars($contato['nome']) ?>
                    </h1>
                    <div class="contact-number">
                        <i class="fas fa-phone"></i>
                        <?= htmlspecialchars($contato['numero']) ?>
                    </div>
                </div>
                
                <div class="contact-actions">
                    <form action="atualizar_etapa.php" method="post" style="display: inline;">
                        <input type="hidden" name="id" value="<?= $contato['id'] ?>">
                        <select name="etapa" class="stage-selector" onchange="this.form.submit()">
                            <?php
                            $etapas = ['Novo', 'Em Contato', 'Negociação', 'Fechado', 'Perdido'];
                            foreach ($etapas as $etapa) {
                                $selected = ($etapa === $contato['etapa']) ? 'selected' : '';
                                echo "<option value='$etapa' $selected>$etapa</option>";
                            }
                            ?>
                        </select>
                    </form>
                    
                    <form action="atualizar_followup.php" method="post" style="display: inline;">
                        <input type="hidden" name="id" value="<?= $contato['id'] ?>">
                        <input type="date" name="followup" value="<?= $contato['followup'] ?>" 
                               class="followup-input" onchange="this.form.submit()" title="Follow-up">
                    </form>
                    
                    <a href="crm.php" class="back-btn">
                        <i class="fas fa-arrow-left"></i>
                        Voltar
                    </a>
                </div>
            </div>
        </div>

        <!-- Agendar Mensagem -->
        <div class="health-card health-fade-in">
            <h3 class="health-card-title">
                <i class="fas fa-clock"></i>
                Agendar Mensagem Futura
            </h3>
            
            <form action="agendar_mensagem.php" method="post" class="health-form">
                <input type="hidden" name="contato_id" value="<?= $contato_id ?>">
                <input type="hidden" name="numero" value="<?= $contato['numero'] ?>">
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-message"></i>
                        Mensagem:
                    </label>
                    <textarea name="mensagem" class="form-textarea" required 
                              placeholder="Digite a mensagem que será enviada automaticamente..."></textarea>
                </div>
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-calendar-plus"></i>
                        Data de Envio:
                    </label>
                    <input type="date" name="agendar_para" class="form-input" required>
                </div>
                
                <button type="submit" class="health-btn health-btn-primary">
                    <i class="fas fa-paper-plane"></i>
                    Agendar Envio
                </button>
            </form>
        </div>

        <!-- Mensagens Agendadas -->
        <div class="health-card health-fade-in">
        <?php if (!empty($agendados)): ?>
            <h3 class="health-card-title">
                <i class="fas fa-calendar-check"></i>
                Mensagens Agendadas
            </h3>
                <div class="scheduled-list">
                    <?php foreach ($agendados as $ag): ?>
                        <div class="scheduled-item">
                            <div class="scheduled-content">
                                <div class="scheduled-message">
                                <?php
                                // Para descriptografar os dados
                                $dados = base64_decode($ag['mensagem']);
                                $ag_mensagem = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
                                ?>
                                    <?= nl2br(htmlspecialchars($ag_mensagem)) ?>
                                </div>
                                <div class="scheduled-datetime">
                                    <i class="fas fa-clock"></i>
                                    <?= date('d/m/Y', strtotime($ag['agendar_para'])) ?>
                                </div>
                            </div>
                            <div class="scheduled-actions">
                                <a href="editar_agendamento.php?id=<?= $ag['id'] ?>&cid=<?= $contato_id ?>" 
                                   class="health-btn health-btn-warning" title="Editar agendamento">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="cancelar_agendamento.php?id=<?= $ag['id'] ?>&cid=<?= $contato_id ?>" 
                                   class="health-btn health-btn-danger" 
                                   onclick="return confirm('Tem certeza que deseja cancelar este agendamento?')"
                                   title="Cancelar agendamento">
                                    <i class="fas fa-trash"></i>
                                </a>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Histórico de Conversas -->
        <div class="health-card health-fade-in">
            <h3 class="health-card-title">
                <i class="fas fa-history"></i>
                Histórico de Conversas
            </h3>
            
            <?php if (empty($interacoes)): ?>
                <div class="empty-state">
                    <i class="fas fa-comments"></i>
                    <h4>Nenhuma conversa</h4>
                    <p>Ainda não há mensagens trocadas com este contato.</p>
                </div>
            <?php else: ?>
                <div class="chat-container">
                    <?php foreach (array_reverse($interacoes) as $msg): ?>
                        <div class="message <?= $msg['origem'] === 'cliente' ? 'client' : 'company' ?>">
                            <div class="message-bubble">
                                <div class="message-text">
                                    <?php
                                    // Para descriptografar os dados
                                    $dados = base64_decode($msg['mensagem']);
                                    $mensagem = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
                                    ?>
                                    <?= nl2br(htmlspecialchars($mensagem)) ?>
                                </div>
                                <div class="message-meta">
                                    <?= date('d/m/Y H:i', strtotime($msg['data'])) ?> - 
                                    <?= $msg['origem'] === 'cliente' ? htmlspecialchars($contato['nome']) : 'Empresa' ?>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Enviar Resposta -->
        <div class="health-card health-fade-in">
            <h3 class="health-card-title">
                <i class="fab fa-whatsapp"></i>
                Enviar Resposta via WhatsApp
            </h3>
            
            <form action="enviar_evolution.php" method="post" class="health-form">
                <input type="hidden" name="contato_id" value="<?= $contato_id ?>">
                <input type="hidden" name="numero" value="<?= $contato['numero'] ?>">
                
                <div class="form-group">
                    <label class="form-label">
                        <i class="fas fa-message"></i>
                        Sua mensagem:
                    </label>
                    <textarea name="mensagem" class="form-textarea" required 
                              placeholder="Digite sua resposta..."></textarea>
                </div>
                
                <button type="submit" class="health-btn health-btn-success">
                    <i class="fab fa-whatsapp"></i>
                    Enviar via WhatsApp
                </button>
            </form>
        </div>
    </div>

    <script>
        // Animações de entrada
        document.addEventListener('DOMContentLoaded', function() {
            const elements = document.querySelectorAll('.health-fade-in');
            elements.forEach((el, index) => {
                setTimeout(() => {
                    el.style.opacity = '1';
                }, index * 100);
            });
        });

        // Auto-scroll do chat para o final
        const chatContainer = document.querySelector('.chat-container');
        if (chatContainer) {
            chatContainer.scrollTop = chatContainer.scrollHeight;
        }

        // Confirmação para cancelar agendamentos
        document.querySelectorAll('a[onclick*="confirm"]').forEach(link => {
            link.addEventListener('click', function(e) {
                if (!confirm('Tem certeza que deseja cancelar este agendamento?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>