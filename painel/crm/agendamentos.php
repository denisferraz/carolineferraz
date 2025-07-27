<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$token_emp = $_SESSION['token_emp'];

$stmt = $conexao->prepare("
  SELECT a.*, c.nome FROM agendamentos_automaticos a
  LEFT JOIN contatos c ON a.contato_id = c.id
  WHERE a.token_emp = ? AND a.enviado = 0
  ORDER BY agendar_para ASC
");
$stmt->execute([$token_emp]);
$agendamentos = $stmt->fetchAll();

// Contar agendamentos por status
$total_agendamentos = count($agendamentos);
$agendamentos_hoje = 0;
$agendamentos_atrasados = 0;
$agendamentos_futuros = 0;

$hoje = date('Y-m-d');
$agora = date('Y-m-d H:i:s');

foreach ($agendamentos as $ag) {
    $data_agendamento = date('Y-m-d', strtotime($ag['agendar_para']));
    
    if ($data_agendamento == $hoje) {
        $agendamentos_hoje++;
    } elseif ($ag['agendar_para'] < $agora) {
        $agendamentos_atrasados++;
    } else {
        $agendamentos_futuros++;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM - Agendamentos Automáticos</title>
    
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

        .stat-card.total {
            border-left-color: var(--health-primary);
            background: linear-gradient(135deg, #eff6ff, #dbeafe);
        }

        .stat-card.hoje {
            border-left-color: var(--status-hoje);
            background: linear-gradient(135deg, #fffbeb, #fef3c7);
        }

        .stat-card.atrasado {
            border-left-color: var(--status-atrasado);
            background: linear-gradient(135deg, #fef2f2, #fecaca);
        }

        .stat-card.futuro {
            border-left-color: var(--status-futuro);
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
        .stat-card.hoje .stat-number { color: var(--status-hoje); }
        .stat-card.atrasado .stat-number { color: var(--status-atrasado); }
        .stat-card.futuro .stat-number { color: var(--status-futuro); }

        .stat-label {
            color: var(--health-text-secondary);
            font-weight: 500;
            font-size: 0.9rem;
        }

        /* Tabela */
        .health-table-container {
            background: var(--health-bg-card);
            border-radius: var(--health-radius);
            box-shadow: var(--health-shadow);
            overflow: hidden;
            border: 1px solid #e2e8f0;
        }

        .health-table-header {
            background: linear-gradient(135deg, #f8fafc, #f1f5f9);
            padding: var(--space-5);
            border-bottom: 1px solid #e2e8f0;
        }

        .health-table-title {
            font-size: 1.25rem;
            font-weight: 600;
            color: var(--health-text-primary);
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }

        .health-table {
            width: 100%;
            border-collapse: collapse;
        }

        .health-table th {
            background: #f8fafc;
            padding: var(--space-4);
            text-align: left;
            font-weight: 600;
            color: var(--health-text-primary);
            border-bottom: 2px solid #e2e8f0;
            font-size: 0.9rem;
        }

        .health-table td {
            padding: var(--space-4);
            border-bottom: 1px solid #f1f5f9;
            vertical-align: top;
        }

        .health-table tr:hover {
            background-color: #f8fafc;
        }

        /* Status badges */
        .status-badge {
            display: inline-block;
            padding: var(--space-1) var(--space-3);
            border-radius: var(--health-radius-sm);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }

        .status-hoje {
            background: #fef3c7;
            color: #92400e;
        }

        .status-atrasado {
            background: #fecaca;
            color: #991b1b;
        }

        .status-futuro {
            background: #d1fae5;
            color: #065f46;
        }

        /* Botões */
        .health-btn {
            display: inline-flex;
            align-items: center;
            gap: var(--space-2);
            padding: var(--space-2) var(--space-3);
            border-radius: var(--health-radius-sm);
            font-size: 0.875rem;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.2s ease;
            border: none;
            cursor: pointer;
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

        /* Informações do contato */
        .contact-info {
            display: flex;
            flex-direction: column;
            gap: var(--space-1);
        }

        .contact-name {
            font-weight: 600;
            color: var(--health-text-primary);
        }

        .contact-number {
            font-size: 0.875rem;
            color: var(--health-text-secondary);
            font-family: 'Courier New', monospace;
        }

        /* Mensagem */
        .message-content {
            max-width: 300px;
            line-height: 1.5;
            color: var(--health-text-primary);
        }

        /* Data e hora */
        .datetime-info {
            display: flex;
            flex-direction: column;
            gap: var(--space-1);
        }

        .date-text {
            font-weight: 600;
            color: var(--health-text-primary);
        }

        .time-text {
            font-size: 0.875rem;
            color: var(--health-text-secondary);
        }

        /* Ações */
        .actions-container {
            display: flex;
            gap: var(--space-2);
        }

        /* Estado vazio */
        .empty-state {
            text-align: center;
            padding: var(--space-8);
            color: var(--health-text-secondary);
        }

        .empty-state i {
            font-size: 4rem;
            margin-bottom: var(--space-4);
            opacity: 0.5;
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .health-container {
                padding: var(--space-4);
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
            }
            
            .health-header h1 {
                font-size: 1.5rem;
            }
            
            .health-table {
                font-size: 0.875rem;
            }
            
            .health-table th,
            .health-table td {
                padding: var(--space-3);
            }
        }

        @media (max-width: 480px) {
            .stats-grid {
                grid-template-columns: 1fr;
            }
            
            .actions-container {
                flex-direction: column;
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
        <!-- Header -->
        <div class="health-header health-fade-in">
            <h1>
                <i class="fas fa-calendar-alt"></i>
                CRM - Agendamentos Automáticos
            </h1>
            <p>Gerencie e monitore seus agendamentos de mensagens automáticas</p>
        </div>

        <!-- Estatísticas -->
        <div class="stats-grid health-fade-in">
            <div class="stat-card total">
                <div class="stat-icon">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <div class="stat-number"><?= $total_agendamentos ?></div>
                <div class="stat-label">Total de Agendamentos</div>
            </div>

            <div class="stat-card hoje">
                <div class="stat-icon">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-number"><?= $agendamentos_hoje ?></div>
                <div class="stat-label">Para Hoje</div>
            </div>

            <div class="stat-card atrasado">
                <div class="stat-icon">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-number"><?= $agendamentos_atrasados ?></div>
                <div class="stat-label">Atrasados</div>
            </div>

            <div class="stat-card futuro">
                <div class="stat-icon">
                    <i class="fas fa-calendar-plus"></i>
                </div>
                <div class="stat-number"><?= $agendamentos_futuros ?></div>
                <div class="stat-label">Futuros</div>
            </div>
        </div>

        <!-- Tabela de Agendamentos -->
        <div class="health-table-container health-fade-in">
            <div class="health-table-header">
                <h3 class="health-table-title">
                    <i class="fas fa-list"></i>
                    Agendamentos Pendentes
                </h3>
            </div>

            <?php if (empty($agendamentos)): ?>
                <div class="empty-state">
                    <i class="fas fa-calendar-times"></i>
                    <h4>Nenhum agendamento pendente</h4>
                    <p>Não há agendamentos automáticos pendentes no momento.</p>
                </div>
            <?php else: ?>
                <table class="health-table">
                    <thead>
                        <tr>
                            <th>Contato</th>
                            <th>Mensagem</th>
                            <th>Agendado Para</th>
                            <th>Status</th>
                            <th>Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($agendamentos as $ag): 
                            $data_agendamento = date('Y-m-d', strtotime($ag['agendar_para']));
                            $status_class = '';
                            $status_text = '';
                            
                            if ($data_agendamento == $hoje) {
                                $status_class = 'status-hoje';
                                $status_text = 'Hoje';
                            } elseif ($ag['agendar_para'] < $agora) {
                                $status_class = 'status-atrasado';
                                $status_text = 'Atrasado';
                            } else {
                                $status_class = 'status-futuro';
                                $status_text = 'Futuro';
                            }
                        ?>
                        <tr>
                            <td>
                                <div class="contact-info">
                                    <span class="contact-name"><?= htmlspecialchars($ag['nome'] ?? 'Desconhecido') ?></span>
                                    <span class="contact-number"><?= htmlspecialchars($ag['numero']) ?></span>
                                </div>
                            </td>
                            <td>
                            <?php
                                // Para descriptografar os dados
                                $dados = base64_decode($ag['mensagem']);
                                $ag_mensagem = openssl_decrypt($dados, $metodo, $chave, 0, $iv);
                                ?>
                                <div class="message-content">
                                    <?= nl2br(htmlspecialchars($ag_mensagem)) ?>
                                </div>
                            </td>
                            <td>
                                <div class="datetime-info">
                                    <span class="date-text"><?= date('d/m/Y', strtotime($ag['agendar_para'])) ?></span>
                                </div>
                            </td>
                            <td>
                                <span class="status-badge <?= $status_class ?>"><?= $status_text ?></span>
                            </td>
                            <td>
                                <div class="actions-container">
                                    <a href="editar_agendamento.php?id=<?= $ag['id'] ?>&cid=<?= $ag['contato_id'] ?>" 
                                       class="health-btn health-btn-warning" 
                                       title="Editar agendamento">
                                        <i class="fas fa-edit"></i>
                                        Editar
                                    </a>
                                    <a href="cancelar_agendamento.php?id=<?= $ag['id'] ?>&cid=<?= $ag['contato_id'] ?>" 
                                       class="health-btn health-btn-danger" 
                                       onclick="return confirm('Tem certeza que deseja cancelar este agendamento?')"
                                       title="Cancelar agendamento">
                                        <i class="fas fa-trash"></i>
                                        Cancelar
                                    </a>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
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

        // Auto-refresh da página a cada 30 segundos
        setTimeout(function() {
            location.reload();
        }, 30000);
    </script>
</body>
</html>