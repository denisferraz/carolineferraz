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

        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 16px;
        }
        
        .data-table th,
        .data-table td {
            padding: 12px;
            text-align: center;
            border-bottom: 1px solid var(--health-gray-900);
        }

        .data-table th {
            text-align: center;
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
                <i class="bi bi-coin"></i>
                Cadastrar Custos Fixos <i class="bi bi-question-square-fill"onclick="ajudaServicosCadastrarCustosFixos()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Preencha os dados para cadastrar um custo fixo
            </p>
        </div>
    </div>
    <form data-step="1" class="form" action="acao.php" method="POST">

    <div class="form-row">
                <div class="health-form-group">

            <label class="health-label">Valor</label>
            <input class="health-input" data-step="2" minlength="1.0" maxlength="9999.9" type="text" pattern="\d+(\.\d{1,2})?" name="custo_valor" placeholder="000.00" required>
            <label class="health-label">Tipo do Custo</label>
            <select class="health-select" data-step="3" name="custo_tipo" required>
                <option value="Insumos">Insumos</option>
                <option value="Gasolina">Gasolina</option>
                <option value="Estacionamento">Estacionamento</option>
                <option value="Coworking">Coworking</option>
                <option value="Impostos">Impostos</option>
                <option value="Taxas">Taxas</option>
                <option value="Hora">Hora</option>
                <option value="Margem">Margem</option>
                <option value="Aluguel">Aluguel</option>
                <option value="Luz">Luz</option>
                <option value="Internet">Internet</option>
                <option value="Mobiliario">Mobiliario</option>
                <option value="Aluguel Equipamentos">Equipamentos [Aluguel]</option>
                <option value="Compra Equipamentos">Equipamentos [Compra]</option>
                <option value="Outros">Outros</option>
                </select>


                <label class="health-label">Descrição Custo Fixo</label>
                <textarea class="health-input" data-step="4" name="custo_descricao" class="textarea-custom" rows="5" cols="43" required></textarea><br><br>
                <input type="hidden" name="id_job" value="lancar_custos" />
                <div data-step="5"><button class="health-btn health-btn-success" type="submit"><i class="bi bi-check-lg"></i> Cadastrar</button></div>

            </div>
            </div>
    </form>
<div class="table-responsive">
    <table data-step="6" class="data-table">
            <thead>
                <tr>
                    <th>Tipo</th>
                    <th>Valor</th>
                    <th>Descrição</th>
                    <th data-step="7">Editar</th>
                    <th data-step="8">Excluir</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM custos WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY custo_tipo DESC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $id = $select['id'];
                    $tipo = $select['custo_tipo'];
                    $valor = $select['custo_valor'];
                    $descricao = $select['custo_descricao'];
                    
                    if($tipo == 'Taxas' || $tipo == 'Impostos' || $tipo == 'Margem'){
                        $valor = number_format($valor ,2,",",".") . '%';
                    }else{
                        $valor = 'R$' . number_format($valor ,2,",",".");
                    }
                ?>
                <tr>
                    <td data-label="Tipo"><?php echo $tipo; ?></td>
                    <td data-label="Valor"><?php echo $valor; ?></td>
                    <td data-label="Descrição"><?php echo $descricao; ?></td>
                    <td data-label="Editar"><a href="javascript:void(0)" onclick='window.open("custo_editar.php?id=<?php echo $id ?>","iframe-home")'><button class="health-btn health-btn-primary btn-mini"><i class="bi bi-pencil"></i> Editar</button></a></td>
                    <td data-label="Excluir"><a href="javascript:void(0)" onclick='window.open("custo_excluir.php?id=<?php echo $id ?>","iframe-home")'><button class="health-btn health-btn-danger btn-mini"><i class="bi bi-trash"></i> Excluir</button></a></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
</body>
</html>
