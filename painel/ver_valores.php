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
            text-align: left;
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
                <i class="bi bi-cash-stack"></i>
                Valores dos Serviço <i class="bi bi-question-square-fill"onclick="ajudaServicos()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Veja abaixo os valores sugeridos dos serviços cadastrados
            </p>
        </div>
    </div>
    <div class="table-responsive">
    <table data-step="4" class="data-table">
                <thead>
                    <tr>
                        <th data-step="2">Serviço</th>
                        <th data-step="3">Custos</th>
                        <th data-step="4">Total</th>
                        <th data-step="5">Margem</th>
                        <th data-step="6">Taxas</th>
                        <th data-step="7">Impostos</th>
                        <th data-step="8">Sugestão Valor</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_tratamentos = $conexao->prepare("SELECT * FROM tratamentos WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY tratamento ASC");
                    $query_tratamentos->execute(['id' => 1]);

                    while ($select_tratamentos = $query_tratamentos->fetch(PDO::FETCH_ASSOC)) {
                        $tratamento_id = $select_tratamentos['id'];
                        $tratamentos = $select_tratamentos['tratamento'];
                        $custos = '| ';
                        $custo_total = 0.00;
                        $custo_sugerido = 0.00;
                        $margem = 0.00;
                        $taxas = 0.00;
                        $impostos = 0.00;

                        $query_custos_tratamentos = $conexao->prepare("SELECT * FROM custos_tratamentos WHERE tratamento_id = :tratamento_id");
                        $query_custos_tratamentos->execute(['tratamento_id' => $tratamento_id]);

                        while ($select_custos_tratamentos = $query_custos_tratamentos->fetch(PDO::FETCH_ASSOC)) {
                            $custo_id = $select_custos_tratamentos['custo_id'];
                            $quantidade = $select_custos_tratamentos['quantidade'];

                            $query_custos = $conexao->prepare("SELECT * FROM custos WHERE id = :custo_id");
                            $query_custos->execute(['custo_id' => $custo_id]);

                            while ($select_custos = $query_custos->fetch(PDO::FETCH_ASSOC)) {
                                $custo_descricao = $select_custos['custo_descricao'];
                                $custo_valor = $select_custos['custo_valor'];
                                $custo_tipo = $select_custos['custo_tipo'];

                            if ($custo_tipo == 'Taxas') {
                                $taxas += $custo_valor * $quantidade;
                            }else if ($custo_tipo == 'Impostos'){
                                $impostos += $custo_valor * $quantidade;
                            }else if ($custo_tipo == 'Margem'){
                                $margem += $custo_valor * $quantidade;
                            }else{
                                $custos .= "($quantidade)$custo_descricao | ";
                                $custo_total += ($custo_valor * $quantidade);
                                $custo_sugerido += ($custo_valor * $quantidade);
                            }
                            }
                        }
                        
                        //$custo_extra = $custo_sugerido * ($taxas + $impostos + $margem)/ 100;
                        $margem_ = $custo_sugerido * $margem / 100;
                        $taxas_ = ($custo_sugerido + $margem_) * $taxas / 100;
                        $impostos_ = ($custo_sugerido + $margem_ + $taxas_) * $impostos / 100;
                        $custo_extra = $margem_ + $taxas_ + $impostos_;
                        $custo_total_sugerido = ceil($custo_sugerido + $custo_extra);
                        
                    ?>
                        <tr>
                            <td data-label="Tratamento"><?php echo $tratamentos; ?></td>
                            <td data-label="Custos"><?php echo $custos; ?></td>
                            <td class="valor-total" data-label="Total">R$<?php echo number_format($custo_total, 2, ",", "."); ?></td>
                            <td class="valor-margem" data-label="Margem"><?php echo number_format($margem, 2, ",", "."); ?>%</td>
                            <td class="valor-taxas" data-label="Taxas"><?php echo number_format($taxas, 2, ",", "."); ?>%</td>
                            <td class="valor-taxas" data-label="Impostos"><?php echo number_format($impostos, 2, ",", "."); ?>%</td>
                            <td class="valor-sugestao" data-label="Sugestão Valor">R$<?php echo number_format($custo_total_sugerido, 2, ",", "."); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>

</body>
</html>
