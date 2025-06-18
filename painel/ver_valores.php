<?php
session_start();
require('../config/database.php');
require('verifica_login.php');

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Valores dos Tratamentos</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="<?php echo $css_path ?>">
    <style>
        @media (max-width: 600px) {
            table, thead, tbody, th, td, tr {
                display: block;
            }

            thead {
                display: none;
            }

            tr {
                margin-bottom: 20px;
            }

            td {
                text-align: right;
                padding-left: 50%;
                position: relative;
            }

            td::before {
                content: attr(data-label);
                position: absolute;
                left: 15px;
                width: 45%;
                padding-right: 10px;
                text-align: left;
                color: #00ffcc;
            }
        }
    </style>
</head>
<body>

    <div class="container">
        <h2>Valores dos Serviços</h2>

        <table>
                <thead>
                    <tr>
                        <th>Serviço</th>
                        <th>Custos</th>
                        <th>Total</th>
                        <th>Margem</th>
                        <th>Taxas</th>
                        <th>Impostos</th>
                        <th>Sugestão Valor</th>
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
                            <td data-label="Total">R$<?php echo number_format($custo_total, 2, ",", "."); ?></td>
                            <td data-label="Margem"><?php echo number_format($margem, 2, ",", "."); ?>%</td>
                            <td data-label="Taxas"><?php echo number_format($taxas, 2, ",", "."); ?>%</td>
                            <td data-label="Impostos"><?php echo number_format($impostos, 2, ",", "."); ?>%</td>
                            <td data-label="Sugestão Valor">R$<?php echo number_format($custo_total_sugerido, 2, ",", "."); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>

</body>
</html>
