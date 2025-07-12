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
                <i class="bi bi-arrow-up"></i>
                Cadastrar Saida de Estoque <i class="bi bi-question-square-fill"onclick="ajudaEstoqueProdutosSaidas()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Preencha os dados para cadastrar uma saida de estoque
            </p>
        </div>
    </div>
    <form data-step="1" class="form" action="acao.php" method="POST">

    <div class="form-row">
                <div class="health-form-group">

            <label class="health-label">Produto</label>
            <select class="health-select" data-step="2" name="produto" required>
            <?php
                $query = $conexao->prepare("SELECT * FROM estoque_item WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id ORDER BY produto DESC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $produto = $select['produto'];
                    $produto_id = $select['id'];
                ?>
                <option value="<?php echo $produto_id; ?>"><?php echo $produto; ?></option>
                <?php
                }
                ?>
                </select>

                <label class="health-label">Quantidade</label>
                <input class="health-input" data-step="3" type="number" name="produto_quantidade" min="1" max="9999" step="1" required>

                <label class="health-label">Destino</label>
                <input class="health-input" data-step="4" type="text" name="produto_lote" minlength="1" maxlength="50">
                
                <input data-step="5" type="hidden" name="produto_validade" value="<?php echo $hoje; ?>">
                
            <br><br> 
            <input type="hidden" name="id_job" value="lancar_saida" />
            <div data-step="6"><button class="health-btn health-btn-success" type="submit"><i class="bi bi-check-lg"></i> Confirmar</button></div>

            </div></div>
    </form>
    
<div class="table-responsive">
    <table data-step="6" class="data-table">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Produto</th>
                    <th>Saida [Unidade]</th>
                    <th>Destino</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = $conexao->prepare("SELECT * FROM estoque WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id AND tipo = 'Saida' ORDER BY data_entrada DESC");
                $query->execute(['id' => 1]);

                while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
                    $produto_id = $select['produto'];
                    $quantidade = $select['quantidade'];
                    $data_entrada = $select['data_entrada'];
                    $lote = $select['lote'];
                    $validade = $select['validade'];

                    $query2 = $conexao->prepare("SELECT * FROM estoque_item WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id");
                    $query2->execute(['id' => $produto_id]);
                    while ($select2 = $query2->fetch(PDO::FETCH_ASSOC)) {
                        $produto = $select2['produto'];
                        $unidade = $select2['unidade'];
                    }
                ?>
                <tr>
                    <td data-label="Data"><?php echo date('d/m/Y', strtotime("$data_entrada")); ?></td>
                    <td data-label="Produto"><?php echo $produto; ?></td>
                    <td data-label="Saida [Unidade]"><?php echo $quantidade; ?> [<?php echo $unidade; ?>]</td>
                    <td data-label="Lote"><?php echo $lote; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
</body>
</html>