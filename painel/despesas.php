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
    <title>Cadastrar Despesa</title>

    <link rel="stylesheet" href="css/style.css">
    <script>
    function formatar(mascara, documento){
    var i = documento.value.length;
    var saida = mascara.substring(0,1);
    var texto = mascara.substring(i)
    if (texto.substring(0,1) != saida){
            documento.value += texto.substring(0,1);
    }
    }   
    </script>
</head>
<body>

    <form class="form" action="acao.php" method="POST">
        <div class="card">
            <div class="card-top">
                <h2 class="title-cadastro">Veja suas Despesas</h2>
            </div>

            <div class="card-group">
            <FONT COLOR="black">
                <fieldset>
                    <legend align="center"><b>Despesas</b></legend>
                    <br>
                    <table border="1px" align="center">
                        <tr>
                            <td align="center"><b>Data</b></td>
                            <td align="center"><b>Tipo</b></td>
                            <td align="center"><b>Valor</b></td>
                            <td align="center"><b>Descrição</b></td>
                        </tr>
<?php
$query = $conexao->prepare("SELECT * FROM despesas WHERE id >= :id ORDER BY despesa_dia DESC");
$query->execute(array('id' => '1'));
while($select = $query->fetch(PDO::FETCH_ASSOC)){
$despesa_dia = $select['despesa_dia'];
$despesa_valor = $select['despesa_valor'];
$despesa_tipo = $select['despesa_tipo'];
$despesa_descricao = $select['despesa_descricao'];
?>
                        <tr>
                            <td align="center"><?php echo date('d/m/Y', strtotime("$despesa_dia")) ?></td>
                            <td align="left"><?php echo $despesa_tipo ?></td>
                            <td align="left">R$<?php echo number_format($despesa_valor ,2,",",".") ?></td>
                            <td align="center"><?php echo $despesa_descricao ?></td>
                        </tr>
                        <?php
}
?>
                    </table>
                </fieldset>
            </FONT>
            </div>
        </div>
    </form>

</body>
</html>

<?php
}
?>