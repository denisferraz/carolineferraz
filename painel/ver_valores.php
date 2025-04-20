<?php
session_start();
require('../conexao.php');
require('verifica_login.php');

// Pega o tema atual do usuário
$query = $conexao->prepare("SELECT tema_painel FROM painel_users WHERE email = :email");
$query->execute(array('email' => $_SESSION['email']));
$result = $query->fetch(PDO::FETCH_ASSOC);
$tema = $result ? $result['tema_painel'] : 'escuro'; // padrão é escuro

// Define o caminho do CSS
$css_path = "css/style_$tema.css";

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
$aut_acesso = $query_check->fetch(PDO::FETCH_ASSOC)['aut_painel'];

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta página';
    exit;
}
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
        <h2>Valores dos Tratamentos</h2>

        <table>
                <thead>
                    <tr>
                        <th>Tratamento</th>
                        <th>Custos</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $query_tratamentos = $conexao->prepare("SELECT * FROM tratamentos WHERE id >= :id ORDER BY tratamento DESC");
                    $query_tratamentos->execute(['id' => 1]);

                    while ($select_tratamentos = $query_tratamentos->fetch(PDO::FETCH_ASSOC)) {
                        $tratamento_id = $select_tratamentos['id'];
                        $tratamentos = $select_tratamentos['tratamento'];
                        $custos = '| ';
                        $custo_total = 0.00;

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

                                $custos .= "($quantidade)$custo_descricao | ";

                                if ($custo_tipo != 'Valor Hora') {
                                    $custo_total += ($custo_valor * 1.30 * $quantidade);
                                } else {
                                    $custo_total += ($custo_valor * $quantidade);
                                }
                            }
                        }
                    ?>
                        <tr>
                            <td data-label="Tratamento"><?php echo $tratamentos; ?></td>
                            <td data-label="Custos"><?php echo $custos; ?></td>
                            <td data-label="Total">R$<?php echo number_format($custo_total, 2, ",", "."); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
    </div>

</body>
</html>
