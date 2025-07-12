<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $config_empresa; ?></title>
    
    <!-- CSS Tema Saúde -->
    <link rel="stylesheet" href="css/health_theme.css">
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
    </style>
</head>
<body>

<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-graph-up"></i>
                Relatórios Gerenciais <i class="bi bi-question-square-fill"onclick="ajudaRelatorios()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Selecione da lista abaixo, um relatorio que lhe atenda e selecione o formato do mesmo
            </p>
        </div>
    </div>
    <form class="form" action="gerar.php" method="POST">
<div data-step="1" class="form-section health-fade-in">
            <div class="form-row">
                <div class="health-form-group">
                    
                <label class="health-label">Relatório</label>
                <select class="health-select" data-step="2" name="relatorio">
                <option value="Gerencial">Gerencial</option>
                <option value="Estornos Dia">Estornos Dia</option>
                <option value="Estornos Mes">Estornos Mês</option>
                <option value="Estornos Ano">Estornos Ano</option>
                <option value="Lançamentos Dia">Lançamentos Dia</option>
                <option value="Lançamentos Mes">Lançamentos Mês</option>
                <option value="Lançamentos Ano">Lançamentos Ano</option>
                <option value="Pagamentos Dia">Pagamentos Dia</option>
                <option value="Pagamentos Mes">Pagamentos Mês</option>
                <option value="Pagamentos Ano">Pagamentos Ano</option>
                <option value="Despesas Dia">Despesas Dia</option>
                <option value="Despesas Mes">Despesas Mês</option>
                <option value="Despesas Ano">Despesas Ano</option>
                <option value="Consultas Dia">Consultas Dia</option>
                <option value="Consultas Mes">Consultas Mês</option>
                <option value="Consultas Ano">Consultas Ano</option>
                <option value="Cancelamentos Dia">Cancelamentos Dia</option>
                <option value="Cancelamentos Mes">Cancelamentos Mês</option>
                <option value="Cancelamentos Ano">Cancelamentos Ano</option>
                <option value="No-Shows Dia">No-Shows Dia</option>
                <option value="No-Shows Mes">No-Shows Mês</option>
                <option value="No-Shows Ano">No-Shows Ano</option>
                </select>
                <br>
                <label class="health-label">Formato</label>
                <select class="health-select" data-step="3" name="relatorio_tipo">
                <option value="pdf">PDF</option>
                <option value="excel">Excel</option>
                </select>
                <br>
                <label class="health-label">Data</label>
                <input class="health-input" data-step="4" type="date" max="<?php echo $config_atendimento_dia_max ?>" value="<?php echo $hoje ?>" name="relatorio_inicio" required>
                <br><br>
                <div data-step="5"><button class="health-btn health-btn-primary" type="submit"><i class="bi bi-check-lg"></i> Gerar Relatório</button></div>

            </div>
        </div>
    </form>

</body>
</html>
