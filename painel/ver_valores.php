<?php

session_start();
require('../conexao.php');
require('verifica_login.php');

$query_check = $conexao->query("SELECT * FROM $tabela_painel_users WHERE email = '{$_SESSION['email']}'");
while($select_check = $query_check->fetch(PDO::FETCH_ASSOC)){
    $aut_acesso = $select_check['aut_painel'];
}

if($aut_acesso == 1){
    echo 'Você não tem permissão para acessar esta pagina';
}else{  

?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <title>Valores Tratamentos</title>

    <link rel="stylesheet" href="css/style.css">
</head>
<body>

        <div class="card">
            <div class="card-top">
                <h2 class="title-cadastro">Valores dos Tratamentos</h2>
            </div>

            <div class="card-group">
            <br>
            <FONT COLOR="black">
                <fieldset>
                    <legend align="center"><b>Tratamentos</b></legend>
                    <br>
                    <table border="1px" align="center">
                        <tr>
                            <td align="center"><b>Tratamento</b></td>
                            <td align="center"><b>Custos</b></td>
                            <td align="center"><b>Total</b></td>
                        </tr>
<?php
$query_tratamentos = $conexao->prepare("SELECT * FROM tratamentos WHERE id >= :id ORDER BY tratamento DESC");
$query_tratamentos->execute(array('id' => '1'));
while($select_tratamentos = $query_tratamentos->fetch(PDO::FETCH_ASSOC)){
$tratamento_id = $select_tratamentos['id'];
$tratamentos = $select_tratamentos['tratamento'];
$custos = '| ';
$custo_total = 0.00;

$query_custos_tratamentos = $conexao->prepare("SELECT * FROM custos_tratamentos WHERE tratamento_id = :tratamento_id");
$query_custos_tratamentos->execute(array('tratamento_id' => $tratamento_id));
while($select_custos_tratamentos = $query_custos_tratamentos->fetch(PDO::FETCH_ASSOC)){
$custo_id = $select_custos_tratamentos['custo_id'];
$quantidade = $select_custos_tratamentos['quantidade'];

$query_custos = $conexao->prepare("SELECT * FROM custos WHERE id = :custo_id");
$query_custos->execute(array('custo_id' => $custo_id));
while($select_custos = $query_custos->fetch(PDO::FETCH_ASSOC)){
$custo_descricao = $select_custos['custo_descricao'];
$custo_valor = $select_custos['custo_valor'];
$custo_tipo = $select_custos['custo_tipo'];

$custos .= "($quantidade)$custo_descricao | ";

if($custo_tipo != 'Valor Hora'){
    $custo_total += ($custo_valor * 1.30 * $quantidade);
}else{
    $custo_total += ($custo_valor * $quantidade);
}
}
}
?>
                        <tr>
                            <td align="left"><?php echo $tratamentos; ?></td>
                            <td align="left"><?php echo $custos; ?></td>
                            <td align="left">R$<?php echo number_format($custo_total ,2,",",".") ?></td>
                        </tr>
                        <?php
}
?>
                    </table>
                </fieldset>
            </FONT>

            </div>
        </div>

</body>
</html>

<?php
}
?>