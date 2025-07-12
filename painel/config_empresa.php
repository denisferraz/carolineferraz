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
                <i class="bi bi-building-gear"></i>
                Editar Configurações da Empresa <i class="bi bi-question-square-fill"onclick="ajudaConfigEmpresa()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Confirme os dados para editar esta configuração
            </p>
        </div>
    </div>
<form data-step="1" class="form" action="acao.php" method="POST">
<div class="form-row">
                <div class="health-form-group">

    <label class="health-label">Profissional</label>
    <input class="health-input" data-step="2" type="text" minlength="5" maxlength="30" name="config_empresa" value="<?php echo $config_empresa; ?>" required>
    <label class="health-label">Email</label>
    <input class="health-input" data-step="3" type="text" minlength="10" maxlength="35" name="config_email" value="<?php echo $config_email; ?>" required>
    <label class="health-label">Telefone</label>
    <input class="health-input" data-step="4" type="text" minlength="8" maxlength="18" name="config_telefone" value="<?php echo $config_telefone; ?>" required>
    <label class="health-label">CNPJ/CPF</label>
    <input class="health-input" data-step="5" type="text" minlength="2" maxlength="18" name="config_cnpj" value="<?php echo $config_cnpj; ?>" required>
    <label class="health-label">Endereco</label>
    <textarea class="health-input" data-step="6" class="textarea-custom" name="config_endereco" rows="5" cols="43" required><?php echo $config_endereco ?></textarea><br><br>
    <br><br>
    <input type="hidden" name="id_job" value="editar_configuracoes_empresa">
    <div data-step="7"><button class="health-btn health-btn-success" type="submit"><i class="bi bi-check-lg"></i> Atualizar Dados</button></div>
</div>
</div>
</form>

</body>
</html>
