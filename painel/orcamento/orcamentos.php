<?php
session_start();
require('../../config/database.php');
require('../verifica_login.php');

$stmt = $conexao->query("SELECT * FROM orcamentos WHERE token_emp = '{$_SESSION['token_emp']}' ORDER BY data_criacao DESC");
$orcamentos = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config_empresa; ?></title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="../css/health_theme.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    
<style>
        /* Estilos específicos para esta página */
        .form-section {
            background: white;
            border-radius: 12px;
            padding: 24px;
            margin-bottom: 24px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            border-left: 4px solid var(--health-primary);
        }
        
        .form-section-title {
            font-size: 1.2rem;
            font-weight: 600;
            color: var(--health-gray-800);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .form-row {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 16px;
            margin-bottom: 16px;
        }
        
        .form-actions {
            display: flex;
            gap: 12px;
            justify-content: flex-end;
            margin-top: 24px;
            flex-wrap: wrap;
        }
        
        .erro-campo {
            border-color: var(--health-danger) !important;
            box-shadow: 0 0 0 3px rgba(220, 38, 38, 0.1) !important;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        
        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid var(--health-gray-900);
        }
        
        .data-table th {
            background: var(--health-gray-300);
            font-weight: 600;
            color: var(--health-gray-800);
        }
        
        .data-table tr:hover {
            background: var(--health-gray-200);
        }

        .valor-sugestao {
            background: var(--health-success-light);
            color: var(--health-success);
        }
        
        .valor-margem {
            background: var(--health-warning-light);
            color: var(--health-warning);
        }
        
        .valor-taxas {
            background: var(--health-danger-light);
            color: var(--health-danger);
        }
        
        .valor-total {
            background: var(--health-info-light);
            color: var(--health-info);
        }

    @media (max-width: 768px) {
        .table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }

        .table-responsive .data-table {
            min-width: 600px; /* ou o mínimo necessário para sua tabela não quebrar */
        }

        .data-table th, .data-table td {
            padding: 8px;
            font-size: 0.8rem;
        }
    }
    </style>
</head>
<body>

<div class="section-content health-fade-in">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-card-checklist"></i>
                Orçamentos Cadastrados
            </h1>
            <p class="health-card-subtitle">
                Veja abaixo todos os orçamentos gerados e acompanhe os status de cada um
            </p>
        </div>
    </div>
    <div class="table-responsive">
    <table data-step="4" class="data-table">
                <thead>
                    <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Telefone</th>
                    <th>Data</th>
                    <th>Total</th>
                    <th>Ações</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($orcamentos as $orc): ?>
        <tr>
            <td><?= $orc['nome'] ?></td>
            <td><?= $orc['email'] ?></td>
            <td><?= $orc['telefone'] ?></td>
            <td><?= date('d/m/Y H:i', strtotime($orc['data_criacao'])) ?></td>
            <td>R$ <?= number_format($orc['total'], 2, ',', '.') ?></td>
            <td>
                <a href="editar_orcamento.php?id=<?= $orc['id'] ?>"><button type="submit" class="health-btn health-btn-primary"><i class="bi bi-pencil"></i>Editar</button></a>
                <a href="gerar_pdf.php?id=<?= $orc['id'] ?>" target="_blank"><button type="submit" class="health-btn health-btn-success"><i class="bi bi-file-pdf"></i>PDF</button></a>
            </td>
        </tr>
    <?php endforeach; ?>
                </tbody>
            </table>
    </div>

</body>
</html>
