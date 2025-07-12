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
                <i class="bi bi-door-closed-fill"></i>
                Fechar Agenda <i class="bi bi-question-square-fill"onclick="ajudaDisponibilidadeFechar()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Preencha os dados abaixo para fechar a sua agenda no periodo informado
            </p>
        </div>
    </div>
    <form class="form" action="acao.php" method="POST">
<div class="form-section health-fade-in">
            <div class="form-row">
                <div class="health-form-group">

                <label class="health-label">Data Início</label>
                <input class="health-input" data-step="2" type="date" max="<?php echo $config_atendimento_dia_max ?>" name="fechar_inicio" required>

                <label class="health-label">Data Fim</label>
                <input class="health-input" data-step="3" type="date" max="<?php echo $config_atendimento_dia_max ?>" name="fechar_fim" required>

                <label class="health-label">Hora Início</label>
                <select class="health-select" data-step="4" class="form-control" name="hora_inicio">
                    <?php
                    $atendimento_hora_comeco = strtotime("$config_atendimento_hora_comeco");
                    $atendimento_hora_fim = strtotime("$config_atendimento_hora_fim");
                    $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
                    $rodadas = 0;
                    while($rodadas <= ($atendimento_hora_fim - $atendimento_hora_comeco + $atendimento_hora_intervalo)) {
                        echo '<option value="'.date('H:i:s', $atendimento_hora_comeco).'">'.date('H:i', $atendimento_hora_comeco).'</option>';
                        $rodadas++;
                        $atendimento_hora_comeco += $atendimento_hora_intervalo;
                    }
                    ?>
                </select>

                <label class="health-label">Hora Fim</label>
                <select class="health-select" data-step="5" class="form-control" name="hora_fim">
                    <?php
                    $atendimento_hora_comeco = strtotime("$config_atendimento_hora_comeco");
                    $atendimento_hora_fim = strtotime("$config_atendimento_hora_fim");
                    $atendimento_hora_intervalo = $config_atendimento_hora_intervalo * 60;
                    $rodadas = 0;
                    while($rodadas <= ($atendimento_hora_fim - $atendimento_hora_comeco + $atendimento_hora_intervalo)) {
                        echo '<option value="'.date('H:i:s', $atendimento_hora_comeco).'">'.date('H:i', $atendimento_hora_comeco).'</option>';
                        $rodadas++;
                        $atendimento_hora_comeco += $atendimento_hora_intervalo;
                    }
                    ?>
                </select>
                <br><br>
                <div data-step="6">
                <label class="health-label">Dias da Semana</label>
                <input id="dia_segunda" type="checkbox" name="dia_segunda" checked>
                <label for="dia_segunda">Segunda-Feira</label><br>
                <input id="dia_terca" type="checkbox" name="dia_terca" checked>
                <label for="dia_terca">Terça-Feira</label><br>
                <input id="dia_quarta" type="checkbox" name="dia_quarta" checked>
                <label for="dia_quarta">Quarta-Feira</label><br>
                <input id="dia_quinta" type="checkbox" name="dia_quinta" checked>
                <label for="dia_quinta">Quinta-Feira</label><br>
                <input id="dia_sexta" type="checkbox" name="dia_sexta" checked>
                <label for="dia_sexta">Sexta-Feira</label><br>
                <input id="dia_sabado" type="checkbox" name="dia_sabado" checked>
                <label for="dia_sabado">Sábado</label><br>
                <input id="dia_domingo" type="checkbox" name="dia_domingo" checked>
                <label for="dia_domingo">Domingo</label>
                </div>
                <br>
                <input type="hidden" name="id_job" value="disponibilidade_fechar" />
                <input type="hidden" name="atendimento_local" value="N/A" />
                <div data-step="7"><button class="health-btn health-btn-primary" type="submit"><i class="bi bi-x-lg"></i> Fechar Agenda</button></div>
            </div>
        </div>
    </form>
</body>
</html>
