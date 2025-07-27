<?php
require('../../config/database.php');
require('../verifica_login.php');

$id = intval($_GET['id']);
$cid = intval($_GET['cid']);

$stmt = $conexao->prepare("SELECT * FROM agendamentos_automaticos WHERE id = ?");
$stmt->execute([$id]);
$agendamento = $stmt->fetch();

if (!$agendamento) exit("Agendamento não encontrado.");

// Buscar dados do contato para exibir informações contextuais
$stmt_contato = $conexao->prepare("SELECT nome, numero FROM contatos WHERE id = ?");
$stmt_contato->execute([$cid]);
$contato = $stmt_contato->fetch();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM - Editar Agendamento</title>
    
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
            max-width: 800px;
            margin: 0 auto;
            padding: var(--space-6);
        }

        /* Header */
        .health-header {
            background: linear-gradient(135deg, var(--health-warning), #b45309);
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

        /* Card de Informações do Contato */
        .contact-info-card {
            background: var(--health-bg-card);
            border-radius: var(--health-radius);
            box-shadow: var(--health-shadow);
            padding: var(--space-5);
            margin-bottom: var(--space-6);
            border: 1px solid #e2e8f0;
            border-left: 4px solid var(--health-info);
        }

        .contact-info-title {
            font-size: 1.1rem;
            font-weight: 600;
            color: var(--health-text-primary);
            margin-bottom: var(--space-3);
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }

        .contact-details {
            display: flex;
            flex-direction: column;
            gap: var(--space-2);
        }

        .contact-detail {
            display: flex;
            align-items: center;
            gap: var(--space-2);
            color: var(--health-text-secondary);
        }

        .contact-name {
            font-weight: 600;
            color: var(--health-text-primary);
        }

        .contact-number {
            font-family: 'Courier New', monospace;
            background: #f1f5f9;
            padding: var(--space-1) var(--space-2);
            border-radius: var(--health-radius-sm);
        }

        /* Formulário */
        .health-form {
            background: var(--health-bg-card);
            border-radius: var(--health-radius);
            box-shadow: var(--health-shadow);
            padding: var(--space-6);
            border: 1px solid #e2e8f0;
        }

        .form-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--health-text-primary);
            margin-bottom: var(--space-5);
            display: flex;
            align-items: center;
            gap: var(--space-2);
            padding-bottom: var(--space-3);
            border-bottom: 2px solid #f1f5f9;
        }

        .form-group {
            margin-bottom: var(--space-5);
        }

        .form-label {
            display: block;
            font-weight: 600;
            color: var(--health-text-primary);
            margin-bottom: var(--space-2);
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }

        .form-input,
        .form-textarea {
            width: 100%;
            padding: var(--space-3);
            border: 2px solid #e2e8f0;
            border-radius: var(--health-radius-sm);
            font-size: 0.875rem;
            transition: all 0.2s ease;
            background: white;
        }

        .form-input:focus,
        .form-textarea:focus {
            outline: none;
            border-color: var(--health-warning);
            box-shadow: 0 0 0 3px rgba(217, 119, 6, 0.1);
        }

        .form-textarea {
            resize: vertical;
            min-height: 120px;
            font-family: inherit;
        }

        /* Botões */
        .form-actions {
            display: flex;
            gap: var(--space-3);
            margin-top: var(--space-6);
            padding-top: var(--space-4);
            border-top: 1px solid #f1f5f9;
        }

        .health-btn {
            display: inline-flex;
            align-items: center;
            gap: var(--space-2);
            padding: var(--space-3) var(--space-5);
            border-radius: var(--health-radius-sm);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
            font-family: inherit;
        }

        .health-btn-success {
            background: var(--health-success);
            color: white;
        }

        .health-btn-success:hover {
            background: #047857;
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--health-shadow-lg);
        }

        .health-btn-secondary {
            background: #6b7280;
            color: white;
        }

        .health-btn-secondary:hover {
            background: #4b5563;
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--health-shadow-lg);
        }

        /* Informações Adicionais */
        .info-box {
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
            border: 1px solid #bfdbfe;
            border-radius: var(--health-radius-sm);
            padding: var(--space-4);
            margin-bottom: var(--space-5);
        }

        .info-box-title {
            font-weight: 600;
            color: var(--health-primary);
            margin-bottom: var(--space-2);
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }

        .info-box-content {
            color: var(--health-text-secondary);
            font-size: 0.875rem;
            line-height: 1.5;
        }

        /* Preview da Mensagem */
        .message-preview {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: var(--health-radius-sm);
            padding: var(--space-4);
            margin-top: var(--space-3);
        }

        .message-preview-title {
            font-size: 0.875rem;
            font-weight: 600;
            color: var(--health-text-secondary);
            margin-bottom: var(--space-2);
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }

        .message-preview-content {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: var(--health-radius-sm);
            padding: var(--space-3);
            color: var(--health-text-primary);
            min-height: 60px;
            white-space: pre-wrap;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .health-container {
                padding: var(--space-4);
            }
            
            .health-header h1 {
                font-size: 1.5rem;
            }
            
            .form-actions {
                flex-direction: column;
            }
            
            .health-btn {
                justify-content: center;
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

        /* Validação */
        .form-input:invalid,
        .form-textarea:invalid {
            border-color: var(--health-danger);
        }

        .form-input:valid,
        .form-textarea:valid {
            border-color: var(--health-success);
        }
    </style>
</head>
<body>
    <div class="health-container">
        <!-- Header -->
        <div class="health-header health-fade-in">
            <h1>
                <i class="fas fa-edit"></i>
                Editar Agendamento
            </h1>
            <p>Modifique os detalhes do agendamento automático de mensagem</p>
        </div>

        <!-- Informações do Contato -->
        <?php if ($contato): ?>
        <div class="contact-info-card health-fade-in">
            <div class="contact-info-title">
                <i class="fas fa-user"></i>
                Informações do Contato
            </div>
            <div class="contact-details">
                <div class="contact-detail">
                    <i class="fas fa-user-circle"></i>
                    <span class="contact-name"><?= htmlspecialchars($contato['nome']) ?></span>
                </div>
                <div class="contact-detail">
                    <i class="fas fa-phone"></i>
                    <span class="contact-number"><?= htmlspecialchars($contato['numero']) ?></span>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Formulário de Edição -->
        <div class="health-form health-fade-in">
            <div class="form-title">
                <i class="fas fa-calendar-edit"></i>
                Detalhes do Agendamento
            </div>

            <form action="salvar_edicao_agendamento.php" method="post" id="editForm">
                <input type="hidden" name="id" value="<?= $id ?>">
                <input type="hidden" name="cid" value="<?= $cid ?>">

                <div class="form-group">
                    <label class="form-label" for="mensagem">
                        <i class="fas fa-message"></i>
                        Mensagem:
                    </label>
                    <?php
                    // Para descriptografar os dados
                    $dados = base64_decode($agendamento['mensagem']);
                    $ag_mensagem = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
                    ?>
                    <textarea 
                        name="mensagem" 
                        id="mensagem"
                        class="form-textarea" 
                        required
                        placeholder="Digite a mensagem que será enviada automaticamente..."
                        oninput="updatePreview()"
                    ><?= htmlspecialchars($ag_mensagem) ?></textarea>
                </div>

                <div class="form-group">
                    <label class="form-label" for="agendar_para">
                        <i class="fas fa-calendar-alt"></i>
                        Data de Envio:
                    </label>
                    <input 
                        type="date" 
                        name="agendar_para" 
                        id="agendar_para"
                        class="form-input" 
                        value="<?= date('Y-m-d', strtotime($agendamento['agendar_para'])) ?>" 
                        required
                        min="<?= date('Y-m-d') ?>"
                    >
                </div>

                <div class="form-actions">
                    <button type="submit" class="health-btn health-btn-success">
                        <i class="fas fa-save"></i>
                        Salvar Alterações
                    </button>
                    <a href="contato.php?id=<?= $cid ?>" class="health-btn health-btn-secondary">
                      <i class="fas fa-arrow-left"></i>
                        Voltar
                    </a>
                </div>
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

        // Validação do formulário
        document.getElementById('editForm').addEventListener('submit', function(e) {
            const mensagem = document.getElementById('mensagem').value.trim();
            const dataHora = document.getElementById('agendar_para').value;
            
            if (!mensagem) {
                e.preventDefault();
                alert('Por favor, digite uma mensagem.');
                document.getElementById('mensagem').focus();
                return;
            }
            
            if (!dataHora) {
                e.preventDefault();
                alert('Por favor, selecione uma data e hora.');
                document.getElementById('agendar_para').focus();
                return;
            }
            
            // Verificar se a data é no futuro
            const agendamento = new Date(dataHora);
            const agora = new Date();
            
            if (agendamento <= agora) {
                e.preventDefault();
                alert('A data e hora devem ser no futuro.');
                document.getElementById('agendar_para').focus();
                return;
            }
            
            // Confirmação antes de salvar
            const confirmacao = confirm('Tem certeza que deseja salvar as alterações neste agendamento?');
            if (!confirmacao) {
                e.preventDefault();
            }
        });

        // Contador de caracteres para a mensagem
        document.getElementById('mensagem').addEventListener('input', function() {
            const length = this.value.length;
            const maxLength = 1000; // Limite sugerido para WhatsApp
            
            // Remover contador anterior se existir
            const existingCounter = document.querySelector('.char-counter');
            if (existingCounter) {
                existingCounter.remove();
            }
            
            // Adicionar novo contador
            const counter = document.createElement('div');
            counter.className = 'char-counter';
            counter.style.cssText = 'font-size: 0.75rem; color: #64748b; text-align: right; margin-top: 0.25rem;';
            counter.textContent = `${length}/${maxLength} caracteres`;
            
            if (length > maxLength) {
                counter.style.color = '#dc2626';
            } else if (length > maxLength * 0.8) {
                counter.style.color = '#d97706';
            }
            
            this.parentNode.appendChild(counter);
        });

        // Trigger inicial do contador
        document.getElementById('mensagem').dispatchEvent(new Event('input'));
    </script>
</body>
</html>