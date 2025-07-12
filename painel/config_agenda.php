<?php

session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');
?>

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
                <i class="bi bi-journal-bookmark"></i>
                Editar Configurações da Agenda <i class="bi bi-question-square-fill"onclick="ajudaConfigAgenda()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Confirme os dados para editar esta configuração
            </p>
        </div>
    </div>
<form data-step="1" class="form" action="acao.php" method="POST">

<div class="form-row">
                <div class="health-form-group">

    <label class="health-label">Hora Inicial de Atendimento</label>
    <input class="health-input" data-step="2" type="time" name="atendimento_hora_comeco" value="<?php echo date('H:i', strtotime("$config_atendimento_hora_comeco")) ?>" required>
    <label class="health-label">Hora Final de Atendimento</label>
    <input class="health-input" data-step="3" type="time" name="atendimento_hora_fim" value="<?php echo date('H:i', strtotime("$config_atendimento_hora_fim")) ?>" required>
    <label class="health-label">Intervalo entre Atendimentos (em minutos)</label>
    <input class="health-input" data-step="4" type="number" min="1" max="999" name="atendimento_hora_intervalo" value="<?php echo $config_atendimento_hora_intervalo; ?>" required>
    <label class="health-label">Data Maxima de Agendamento</label>
    <input class="health-input" data-step="5" type="date" name="atendimento_dia_max" min="<?php echo $hoje; ?>" value="<?php echo $config_atendimento_dia_max; ?>" required>
    <br><br>
    <br>
    <div data-step="6">
    <label class="health-label">Dias da Semana</label><br>
    <input id="dia_segunda" type="checkbox" name=dia_segunda <?php if($config_dia_segunda == 1){?>checked<?php } ?>>
    <label for="dia_segunda">Segunda-Feira</label>
    <br>
    <input id="dia_terca" type="checkbox" name=dia_terca <?php if($config_dia_terca == 2){?>checked<?php } ?>>
    <label for="dia_terca">Terça-Feira</label>
    <br>
    <input id="dia_quarta" type="checkbox" name=dia_quarta <?php if($config_dia_quarta == 3){?>checked<?php } ?>>
    <label for="dia_quarta">Quarta-Feira</label>
    <br>
    <input id="dia_quinta" type="checkbox" name=dia_quinta <?php if($config_dia_quinta == 4){?>checked<?php } ?>>
    <label for="dia_quinta">Quinta-Feira</label>
    <br>
    <input id="dia_sexta" type="checkbox" name=dia_sexta <?php if($config_dia_sexta == 5){?>checked<?php } ?>>
    <label for="dia_sexta">Sexta-Feira</label>
    <br>
    <input id="dia_sabado" type="checkbox" name=dia_sabado <?php if($config_dia_sabado == 6){?>checked<?php } ?>>
    <label for="dia_sabado">Sabado</label>
    <br>
    <input id="dia_domingo" type="checkbox" name=dia_domingo <?php if($config_dia_domingo == 0){?>checked<?php } ?>>
    <label for="dia_domingo">Domingo</label>
    </div>
    <br><br>
    <input type="hidden" name="id_job" value="editar_configuracoes_agenda">
    <div data-step="7"><button class="health-btn health-btn-success" type="submit"><i class="bi bi-check-lg"></i> Atualizar Dados</button></div>
</div>
</div>
</form>
</div>
</body>
</html>