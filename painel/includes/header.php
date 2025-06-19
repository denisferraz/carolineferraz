<?php
session_start();
$email = $_SESSION['email'];

//Checar Pendentes
$query_alteracao = $conexao->query("SELECT * FROM alteracoes WHERE alt_status = 'Pendente'");
$alteracao_qtd = $query_alteracao->rowCount();

//Checar Estoque minimo
$itens_abaixo_minimo = 0;
$query = $conexao->prepare("
    SELECT produto, SUM(quantidade) AS total_quantidade, MAX(data_entrada) as ultima_entrada
    FROM estoque 
    WHERE token_emp = '{$_SESSION['token_emp']}' AND id >= :id 
    GROUP BY produto 
    ORDER BY produto DESC
    ");
$query->execute([
    'id' => 1
    ]);
            
while ($select = $query->fetch(PDO::FETCH_ASSOC)) {
    $produto_id = $select['produto'];
    $quantidade = $select['total_quantidade'];
    $data_entrada = $select['ultima_entrada'];

$query2 = $conexao->prepare("
        SELECT produto, unidade, minimo 
        FROM estoque_item 
        WHERE token_emp = '{$_SESSION['token_emp']}' AND id = :id
    ");
$query2->execute([
        'id' => $produto_id
        ]);
        $select2 = $query2->fetch(PDO::FETCH_ASSOC);
        $produto = $select2['produto'];
        $unidade = $select2['unidade'];
        $minimo = $select2['minimo'];
        
    if ($quantidade < $minimo) {
        $itens_abaixo_minimo++;
    }
}


//Criar Alertas
$alerta = '';
$class_alerta = 0;
if ($itens_abaixo_minimo > 0) {
    $class_alerta = 1;
    $alerta .= "Você possui $itens_abaixo_minimo produto com estoque abaixo do minimo!<br>";
}
if ($alteracao_qtd > 0) {
    $class_alerta = 1;
    $alerta .= "Você possui $alteracao_qtd solicitações pendentes!<br>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
</head>
<body>
<div class="bg-light p-3 shadow-sm d-flex justify-content-between align-items-center">
    
    <h5 class="m-0">
        Painel Administrativo
        <?php if($class_alerta == 1){ ?>
        <span class="badge bg-danger" style="font-size: 0.8rem; margin-left: 10px; text-align: left; line-height: 1.5;">
            <?= $alerta ?>
        </span>
        <?php } ?>
    </h5>


    <div class="d-flex align-items-center gap-2">
        <span class="text-secondary"><?= $usuario ?></span>
        <a href="logout.php" class="btn btn-outline-danger btn-sm">Sair</a>
    </div>
</div>

</body>
</html>
