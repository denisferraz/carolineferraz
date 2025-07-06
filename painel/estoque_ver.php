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
    <title>Saldo Estoque</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    
    <!-- CSS externo do sistema -->
    <link rel="stylesheet" href="<?php echo $css_path ?>">
    
    <!-- CSS inline para destaque -->
    <style>
        .linha-alerta {
            background-color: #fff3cd;
            color: #664d03;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
        }
        table th, table td {
            padding: 8px;
            border: 1px solid #ccc;
            text-align: center;
        }
    </style>
</head>
<body>

<div data-step="1" class="container">
    <h2>Saldo Estoque <i class="bi bi-question-square-fill"onclick="ajudaEstoqueVer()"title="Ajuda?"style="color: darkred; cursor: pointer; font-size: 25px;"></i></h2>

    <table>
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
