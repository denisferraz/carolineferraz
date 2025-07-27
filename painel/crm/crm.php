<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$etapas = ['Novo', 'Em Contato', 'Negociação', 'Fechado', 'Perdido'];
$token_emp = $_SESSION['token_emp'];
$hoje = date('Y-m-d');

// Buscar estatísticas
$stats = [];
foreach ($etapas as $etapa) {
    $stmt = $conexao->prepare("SELECT COUNT(*) as total FROM contatos WHERE etapa = ? AND token_emp = ?");
    $stmt->execute([$etapa, $token_emp]);
    $stats[$etapa] = $stmt->fetch()['total'];
}

// Total de contatos
$stmt = $conexao->prepare("SELECT COUNT(*) as total FROM contatos WHERE token_emp = ?");
$stmt->execute([$token_emp]);
$total_contatos = $stmt->fetch()['total'];

// Follow-ups de hoje
$stmt = $conexao->prepare("SELECT * FROM contatos WHERE token_emp = ? AND followup <= ? ORDER BY nome ASC");
$stmt->execute([$token_emp, $hoje]);
$followups = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM - Painel de Contatos WhatsApp</title>
    
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

        /* Cards */
        .health-card {
            background: var(--health-bg-card);
            border-radius: var(--health-radius);
            box-shadow: var(--health-shadow);
            padding: var(--space-6);
            margin-bottom: var(--space-6);
            border: 1px solid #e2e8f0;
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
            transform: translateY(-2px);
        }

        .stat-number {
            font-size: 2rem;
            font-weight: 700;
            color: var(--health-primary);
            margin-bottom: var(--space-2);
        }

        .stat-label {
            color: var(--health-text-secondary);
            font-weight: 500;
        }

        /* Filtros */
        .filters-section {
            background: var(--health-bg-card);
            padding: var(--space-5);
            border-radius: var(--health-radius);
            box-shadow: var(--health-shadow);
            margin-bottom: var(--space-6);
        }

        .filters-grid {
            display: grid;
            grid-template-columns: 2fr 1fr auto;
            gap: var(--space-4);
            align-items: end;
        }

        .health-input,
        .health-select {
            width: 100%;
            padding: var(--space-3);
            border: 1px solid #d1d5db;
            border-radius: var(--health-radius-sm);
            font-size: 0.9rem;
            transition: border-color 0.2s ease;
        }

        .health-input:focus,
        .health-select:focus {
            outline: none;
            border-color: var(--health-primary);
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        .health-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-2);
            padding: var(--space-3) var(--space-4);
            border-radius: var(--health-radius-sm);
            font-weight: 500;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: all 0.2s ease;
            font-size: 0.9rem;
        }

        .health-btn-primary {
            background-color: var(--health-primary);
            color: white;
        }

        .health-btn-primary:hover {
            background-color: var(--health-primary-dark);
            transform: translateY(-1px);
        }

        .health-btn-outline {
            background-color: transparent;
            color: var(--health-primary);
            border: 1px solid var(--health-primary);
        }

        .health-btn-outline:hover {
            background-color: var(--health-primary);
            color: white;
        }

        .health-btn-sm {
            padding: var(--space-2) var(--space-3);
            font-size: 0.8rem;
        }

        /* Alertas */
        .health-alert {
            padding: var(--space-4);
            border-radius: var(--health-radius-sm);
            margin-bottom: var(--space-4);
            display: flex;
            align-items: flex-start;
            gap: var(--space-3);
        }

        .health-alert-success {
            background-color: #dcfce7;
            color: #166534;
            border-left: 4px solid var(--health-success);
        }

        .health-alert-warning {
            background-color: #fef3c7;
            color: #92400e;
            border-left: 4px solid var(--health-warning);
        }

        .health-alert-info {
            background-color: #e0f2fe;
            color: #0c4a6e;
            border-left: 4px solid var(--health-info);
        }

        /* Kanban Board */
        .kanban-container {
            display: flex;
            gap: var(--space-4);
            overflow-x: auto;
            padding-bottom: var(--space-4);
        }

        .kanban-column {
            width: 400px;
            background: var(--health-bg-secondary);
            border-radius: var(--health-radius);
            padding: var(--space-4);
            max-height: 80vh;
            overflow-y: auto;
        }

        .kanban-header {
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: var(--space-4);
            text-align: center;
            padding: var(--space-3);
            border-radius: var(--health-radius-sm);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: var(--space-2);
        }

        .kanban-header.novo { background-color: var(--stage-novo); }
        .kanban-header.em-contato { background-color: var(--stage-contato); }
        .kanban-header.negociação { background-color: var(--stage-negociacao); }
        .kanban-header.fechado { background-color: var(--stage-fechado); }
        .kanban-header.perdido { background-color: var(--stage-perdido); }

        .contact-card {
            background: var(--health-bg-card);
            border-radius: var(--health-radius-sm);
            padding: var(--space-4);
            margin-bottom: var(--space-3);
            box-shadow: var(--health-shadow-sm);
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .contact-card:hover {
            box-shadow: var(--health-shadow);
            transform: translateY(-2px);
        }

        .contact-name {
            font-weight: 600;
            color: var(--health-text-primary);
            margin-bottom: var(--space-2);
            font-size: 1rem;
        }

        .contact-phone {
            color: var(--health-text-secondary);
            font-size: 0.9rem;
            margin-bottom: var(--space-3);
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }

        .contact-actions {
            display: flex;
            gap: var(--space-2);
        }

        .health-badge {
            display: inline-flex;
            align-items: center;
            gap: var(--space-1);
            padding: var(--space-1) var(--space-2);
            border-radius: var(--health-radius-sm);
            font-size: 0.75rem;
            font-weight: 500;
            margin-bottom: var(--space-2);
        }

        .health-badge-warning {
            background-color: #fef3c7;
            color: #92400e;
        }

        /* Follow-up Section */
        .followup-section {
            background: linear-gradient(135deg, #fef3c7, #fde68a);
            border: 1px solid #f59e0b;
            border-radius: var(--health-radius);
            padding: var(--space-5);
            margin-bottom: var(--space-6);
        }

        .followup-title {
            color: #92400e;
            font-weight: 600;
            font-size: 1.1rem;
            margin-bottom: var(--space-3);
            display: flex;
            align-items: center;
            gap: var(--space-2);
        }

        .followup-list {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--space-3);
        }

        .followup-item {
            background: rgba(255, 255, 255, 0.8);
            padding: var(--space-3);
            border-radius: var(--health-radius-sm);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .followup-contact {
            font-weight: 600;
            color: var(--health-text-primary);
        }

        .followup-phone {
            font-size: 0.8rem;
            color: var(--health-text-secondary);
        }

        /* Responsividade */
        @media (max-width: 768px) {
            .health-container {
                padding: var(--space-4);
            }
            
            .filters-grid {
                grid-template-columns: 1fr;
                gap: var(--space-3);
            }
            
            .kanban-container {
                flex-direction: column;
            }
            
            .kanban-column {
                min-width: 100%;
            }
            
            .stats-grid {
                grid-template-columns: repeat(2, 1fr);
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
                <i class="fas fa-info" style="padding:14px; border: 4px solid #fff; border-radius:4px;"></i>
                CRM - Painel de Contatos WhatsApp
            </h1>
            <p>Gerencie seus contatos e leads de forma eficiente e profissional</p>
        </div>

        <!-- Notificações -->
        <?php if (!empty($_SESSION['notificacao_followup'])): ?>
            <div class="health-alert health-alert-success health-fade-in">
                <i class="fas fa-check-circle"></i>
                <span><?= $_SESSION['notificacao_followup'] ?> notificações de follow-up enviadas com sucesso!</span>
            </div>
            <?php unset($_SESSION['notificacao_followup']); ?>
        <?php endif; ?>

        <!-- Follow-ups de Hoje -->
        <?php if ($followups): ?>
            <div class="followup-section health-fade-in">
                <div class="followup-title">
                    <i class="fas fa-calendar-check"></i>
                    Acompanhamentos de hoje (<?= count($followups) ?>)
                </div>
                <div class="followup-list">
                    <?php foreach ($followups as $c): ?>
                        <div class="followup-item">
                            <div>
                                <div class="followup-contact"><?= htmlspecialchars($c['nome']) ?></div>
                                <div class="followup-phone"><?= $c['numero'] ?></div>
                            </div>
                            <a href="contato.php?id=<?= $c['id'] ?>" class="health-btn health-btn-primary health-btn-sm">
                                <i class="fas fa-eye"></i>
                                Ver
                            </a>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>

        <!-- Filtros -->
        <div class="filters-section health-fade-in">
            <form method="get" class="filters-grid">
                <div>
                    <label style="display: block; margin-bottom: var(--space-2); font-weight: 500; color: var(--health-text-primary);">
                        <i class="fas fa-search"></i>
                        Buscar por nome ou número
                    </label>
                    <input type="text" name="busca" class="health-input" placeholder="Digite o nome ou número do contato..." value="<?= htmlspecialchars($_GET['busca'] ?? '') ?>">
                </div>
                <div>
                    <label style="display: block; margin-bottom: var(--space-2); font-weight: 500; color: var(--health-text-primary);">
                        <i class="fas fa-filter"></i>
                        Filtrar por etapa
                    </label>
                    <select name="etapa" class="health-select">
                        <option value="">Todas as etapas</option>
                        <?php
                        foreach ($etapas as $etapa) {
                            $selected = (($_GET['etapa'] ?? '') === $etapa) ? 'selected' : '';
                            echo "<option value='$etapa' $selected>$etapa</option>";
                        }
                        ?>
                    </select>
                </div>
                <div>
                    <button class="health-btn health-btn-primary" style="width: 100%;">
                        <i class="fas fa-search"></i>
                        Filtrar
                    </button>
                </div>
            </form>
        </div>

        <!-- Filtro Ativo -->
        <?php 
        $etapa_filtro = $_GET['etapa'] ?? '';
        if ($etapa_filtro): 
        ?>
            <div class="health-alert health-alert-info health-fade-in">
                <i class="fas fa-info-circle"></i>
                <div>
                    Mostrando apenas contatos da etapa: <strong><?= htmlspecialchars($etapa_filtro) ?></strong>
                    <a href="crm.php" class="health-btn health-btn-outline health-btn-sm" style="margin-left: var(--space-3);">
                        <i class="fas fa-times"></i>
                        Limpar filtro
                    </a>
                </div>
            </div>
        <?php endif; ?>

        <!-- Kanban Board -->
        <div class="health-card health-fade-in">
            <div class="kanban-container">
                <?php
                $lista_etapas = $etapa_filtro ? [$etapa_filtro] : $etapas;
                foreach ($lista_etapas as $etapa_for):
                ?>
                    <div class="kanban-column">
                        <div class="kanban-header <?= strtolower(str_replace(' ', '-', $etapa_for)) ?>">
                            <i class="fas fa-users"></i>
                            <?= $etapa_for ?> (<?= $stats[$etapa_for] ?>)
                        </div>
                        
                        <?php
                        $busca = $_GET['busca'] ?? '';
                        
                        $sql = "SELECT * FROM contatos WHERE token_emp = :token AND etapa = :etapa";
                        $params = [
                            'token' => $token_emp,
                            'etapa' => $etapa_for
                        ];

                        if ($busca) {
                            $sql .= " AND (nome LIKE :busca OR numero LIKE :busca)";
                            $params['busca'] = "%$busca%";
                        }
                        
                        $sql .= " ORDER BY ultimo_contato DESC";
                        $stmt = $conexao->prepare($sql);
                        $stmt->execute($params);

                        while ($contato = $stmt->fetch(PDO::FETCH_ASSOC)):
                        ?>
                            <div class="contact-card">
                                <?php if ($contato['followup'] == date('Y-m-d')): ?>
                                    <div class="health-badge health-badge-warning">
                                        <i class="fas fa-calendar-check"></i>
                                        Follow-up hoje
                                    </div>
                                <?php endif; ?>
                                
                                <div class="contact-name"><?= htmlspecialchars($contato['nome']) ?></div>
                                <div class="contact-phone">
                                    <i class="fas fa-phone"></i>
                                    <?= $contato['numero'] ?>
                                </div>
                                
                                <div class="contact-actions">
                                    <a href="contato.php?id=<?= $contato['id'] ?>" class="health-btn health-btn-primary health-btn-sm" style="flex: 1;">
                                        <i class="fas fa-eye"></i>
                                        Ver histórico
                                    </a>
                                    </div><br><div class="contact-actions">
                                    <a href="excluir.php?contato_id=<?= $contato['id'] ?>" class="health-btn health-btn-danger health-btn-sm" style="flex: 1;">
                                        <i class="fas fa-trash"></i>
                                        Excluir Historico
                                    </a>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                <?php endforeach; ?>
            </div>
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

        // Auto-refresh a cada 5 minutos
        setTimeout(() => {
            location.reload();
        }, 300000);
    </script>
</body>
</html>
