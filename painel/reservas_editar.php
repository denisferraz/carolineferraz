<?php
session_start();
require('../config/database.php');
require('verifica_login.php');
require_once('tutorial.php');

$hoje = date('Y-m-d');
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
    
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
    
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

    <script>
        function buscar(palavra) {
            $.ajax({
                type: 'POST',
                dataType: 'html',
                url: 'reservas_buscar.php',
                beforeSend: function () {
                    $("#resultado").html("Carregando...");
                },
                data: { palavra: palavra },
                success: function (msg) {
                    $("#resultado").html(msg);
                }
            });
        }

        $(document).ready(function () {
            $('#buscar').on('click', function () {
                buscar($("#palavra").val());
            });
        });
    </script>
</head>
<body>
    
<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-eye"></i>
                Buscar uma Consulta <i class="bi bi-question-square-fill"onclick="ajudaConsultaBuscar()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Preencha os dados para buscar uma consulta
            </p>
        </div>
    </div>
<form class="form" action="reservas_buscar.php" method="POST">
<div data-step="1" class="form-row">
                <div class="health-form-group">

                <label class="health-label">E-mail</label>
                <input class="health-input" data-step="2" type="text" minlength="5" maxlength="35" name="busca" placeholder="Para total, deixe em branco">
                <label class="health-label">Atendimento Dia - Início</label>
                <input class="health-input" data-step="3" type="date" name="busca_inicio" max="<?php echo $config_atendimento_dia_max ?>" value="<?php echo $hoje ?>" required>
                <label class="health-label">Atendimento Dia - Fim</label>
                <input class="health-input" data-step="4" type="date" name="busca_fim" max="<?php echo $config_atendimento_dia_max ?>" value="<?php echo $hoje ?>" required>
                <br><br>
                <div data-step="5"><button class="health-btn health-btn-primary" type="submit"><i class="bi bi-check-lg"></i> Buscar</button></div>
        </div>
    </div>
    </form>

</body>
</html>
