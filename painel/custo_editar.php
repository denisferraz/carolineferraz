<?php

session_start();
require('../config/database.php');
require('verifica_login.php'); 
require_once('tutorial.php');

$custo_id = mysqli_real_escape_string($conn_msqli, $_GET['id']);

$query = $conexao->prepare("SELECT * FROM custos WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
$query->execute(array('id' => $custo_id));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$custo_valor = $select['custo_valor'];
$custo_tipo = $select['custo_tipo'];
$custo_descricao = $select['custo_descricao'];
}

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

        .btn-sm {
            padding: var(--space-2) var(--space-3);
            font-size: var(--font-size-xs);
        }
    </style>
</head>
<body>

<div class="health-container">
    <!-- Header da Página -->
    <div class="health-card health-fade-in">
        <div class="health-card-header">
            <h1 class="health-card-title">
                <i class="bi bi-coin"></i>
                Editar Custo Fixo <i class="bi bi-question-square-fill"onclick="ajudaServicosCadastrarCustosFixosEditar()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Confirme os dados para editar esta configuração
            </p>
        </div>
    </div>
<form data-step="1" class="form" action="acao.php" method="POST">
    <div class="form-row">
                <div class="health-form-group">
                    
            <label class="health-label">Valor</label>
            <input class="health-input" data-step="2" value="<?php echo $custo_valor ?>" minlength="1.0" maxlength="9999.9" type="text" pattern="\d+(\.\d{1,2})?" name="custo_valor" placeholder="000.00" required>
            <label class="health-label">Tipo do Custo</label>
            <select class="health-select" data-step="3" name="custo_tipo">
                <option value="Insumos" <?= $custo_tipo == 'Insumos' ? 'selected' : '' ?>>Insumos</option>
                <option value="Gasolina" <?= $custo_tipo == 'Gasolina' ? 'selected' : '' ?>>Gasolina</option>
                <option value="Estacionamento" <?= $custo_tipo == 'Estacionamento' ? 'selected' : '' ?>>Estacionamento</option>
                <option value="Coworking" <?= $custo_tipo == 'Coworking' ? 'selected' : '' ?>>Coworking</option>
                <option value="Impostos" <?= $custo_tipo == 'Impostos' ? 'selected' : '' ?>>Impostos</option>
                <option value="Taxas" <?= $custo_tipo == 'Outros' ? 'selected' : '' ?>>Taxas</option>
                <option value="Hora" <?= $custo_tipo == 'Hora' ? 'selected' : '' ?>>Hora</option>
                <option value="Margem" <?= $custo_tipo == 'Margem' ? 'selected' : '' ?>>Margem</option>
                <option value="Aluguel" <?= $custo_tipo == 'Aluguel' ? 'selected' : '' ?>>Aluguel</option>
                <option value="Luz" <?= $custo_tipo == 'Luz' ? 'selected' : '' ?>>Luz</option>
                <option value="Internet" <?= $custo_tipo == 'Internet' ? 'selected' : '' ?>>Internet</option>
                <option value="Mobiliario" <?= $custo_tipo == 'Mobiliario' ? 'selected' : '' ?>>Mobiliario</option>
                <option value="Aluguel Equipamentos" <?= $custo_tipo == 'Aluguel Equipamentos' ? 'selected' : '' ?>>Equipamentos [Aluguel]</option>
                <option value="Compra Equipamentos" <?= $custo_tipo == 'Compra Equipamentos' ? 'selected' : '' ?>>Equipamentos [Compra]</option>
                <option value="Outros" <?= $custo_tipo == 'Outros' ? 'selected' : '' ?>>Outros</option>
            </select>
            
            <label class="health-label">Descrição Custo</label>
                <textarea class="health-input" data-step="4" class="textarea-custom" name="custo_descricao" rows="5" cols="43" required><?php echo $custo_descricao ?></textarea><br><br>
                <input type="hidden" name="custo_id" value="<?php echo $custo_id; ?>" />
                <input type="hidden" name="id_job" value="editar_custos" />
            <div data-step="5"><button class="health-btn health-btn-primary" type="submit"><i class="bi bi-pencil"></i> Editar</button></div>

            </div>
        </div>
    </form>

</body>
</html>
