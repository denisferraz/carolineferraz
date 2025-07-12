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
        
        .linha-alerta {
            background-color: #f8d7da; /* vermelho claro de fundo */
            color: #721c24;           /* vermelho escuro para o texto */
            font-weight: bold;
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
                <i class="bi bi-bag-dash"></i>
                Saldo de Estoque <i class="bi bi-question-square-fill"onclick="ajudaEstoqueVer()"title="Ajuda?"style="color: white; cursor: pointer; font-size: 25px;"></i>
            </h1>
            <p class="health-card-subtitle">
                Veja abaixo o seu saldo atual de estoque
            </p>
        </div>
    </div>
    
<div class="table-responsive">
    <table data-step="1" class="data-table">
        <thead>
            <tr>
                <th data-step="2">Produto</th>
                <th data-step="3">Saldo</th>
                <th data-step="4">Estoque Mínimo</th>
            </tr>
        </thead>
        <tbody>
        <?php
$itens_alerta = [];
$itens_ok = [];

// Agrupar saldos por produto
$query = $conexao->prepare("
    SELECT produto, SUM(quantidade) AS total_quantidade
    FROM estoque 
    WHERE token_emp = :token_emp AND id >= :id 
    GROUP BY produto
");
$query->execute([
    'token_emp' => $_SESSION['token_emp'],
    'id' => 1
]);

$itens_completos = [];

while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
    $produto_id = $select['produto'];
    $quantidade = $select['total_quantidade'];

    // Buscar dados do produto
    $query2 = $conexao->prepare("
        SELECT produto, unidade, minimo 
        FROM estoque_item 
        WHERE token_emp = :token_emp AND id = :id
    ");
    $query2->execute([
        'token_emp' => $_SESSION['token_emp'],
        'id' => $produto_id
    ]);

    if ($select2 = $query2->fetch(PDO::FETCH_ASSOC)) {
        $item = [
            'produto' => $select2['produto'],
            'quantidade' => $quantidade,
            'unidade' => $select2['unidade'],
            'minimo' => $select2['minimo']
        ];
        $itens_completos[] = $item;
    }
}

// Ordenar por nome do produto (ordem alfabética)
usort($itens_completos, function($a, $b) {
    return strcmp($a['produto'], $b['produto']);
});

// Separar os alertas dos itens normais
foreach ($itens_completos as $item) {
    if ($item['quantidade'] <= $item['minimo']) {
        $itens_alerta[] = $item;
    } else {
        $itens_ok[] = $item;
    }
}

// Exibir os itens
foreach (array_merge($itens_alerta, $itens_ok) as $item) {
    $classe_linha = ($item['quantidade'] <= $item['minimo']) ? 'linha-alerta' : '';
    $emoji = ($item['quantidade'] <= $item['minimo']) ? '⚠️ ' : '';
    ?>
    <tr class="<?php echo $classe_linha; ?>">
        <td data-label="Produto"><?php echo $emoji . htmlspecialchars($item['produto']); ?></td>
        <td data-label="Saldo"><?php echo $item['quantidade']; ?> [<?php echo htmlspecialchars($item['unidade']); ?>]</td>
        <td data-label="Estoque Mínimo"><?php echo $item['minimo']; ?> [<?php echo htmlspecialchars($item['unidade']); ?>]</td>
    </tr>
    <?php
}
?>
        </tbody>
    </table>
</div>

</body>
</html>
